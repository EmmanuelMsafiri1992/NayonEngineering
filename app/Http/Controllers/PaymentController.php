<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Get Paystack secret key from environment (preferred) or database fallback
     */
    protected function getPaystackSecretKey(): ?string
    {
        // Prefer environment variable for security
        $envKey = env('PAYSTACK_SECRET_KEY');
        if ($envKey) {
            return $envKey;
        }
        // Fallback to database setting for backwards compatibility
        return Setting::get('paystack_secret_key');
    }

    /**
     * Initiate Paystack payment
     */
    public function initiate(Order $order)
    {
        $secretKey = $this->getPaystackSecretKey();

        if (!$secretKey) {
            return redirect()->route('checkout.failed', $order)
                ->with('error', 'Payment gateway is not configured.');
        }

        // Paystack expects amount in kobo (smallest currency unit)
        // For ZAR, multiply by 100 (cents)
        // For MZN, multiply by 100 (centavos)
        $amountInSmallestUnit = (int) ($order->total * 100);

        // If currency is MZN, convert to display currency amount
        if ($order->currency === 'MZN') {
            $amountInMzn = $order->total * $order->exchange_rate;
            $amountInSmallestUnit = (int) ($amountInMzn * 100);
        }

        try {
            $response = Http::withToken($secretKey)
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $order->customer_email,
                    'amount' => $amountInSmallestUnit,
                    'currency' => $order->currency === 'MZN' ? 'GHS' : 'ZAR', // Paystack supports ZAR, use GHS for MZN equivalent
                    'reference' => $order->order_number . '-' . time(),
                    'callback_url' => route('payment.callback'),
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                    ],
                ]);

            if ($response->successful() && $response->json('status')) {
                $data = $response->json('data');

                // Update order with payment reference
                $order->update([
                    'payment_reference' => $data['reference'],
                ]);

                // Redirect to Paystack checkout
                return redirect($data['authorization_url']);
            }

            Log::error('Paystack initialization failed', [
                'response' => $response->json(),
                'order_id' => $order->id,
            ]);

            return redirect()->route('checkout.failed', $order)
                ->with('error', 'Failed to initialize payment. Please try again.');

        } catch (\Exception $e) {
            Log::error('Paystack payment error', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->route('checkout.failed', $order)
                ->with('error', 'Payment service unavailable. Please try again later.');
        }
    }

    /**
     * Handle Paystack callback
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('home')->with('error', 'Invalid payment reference.');
        }

        $secretKey = $this->getPaystackSecretKey();

        try {
            $response = Http::withToken($secretKey)
                ->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful() && $response->json('status')) {
                $data = $response->json('data');

                // Find order by reference
                $order = Order::where('payment_reference', $reference)->first();

                if (!$order) {
                    // Try to find by order number from metadata
                    $orderId = $data['metadata']['order_id'] ?? null;
                    $order = Order::find($orderId);
                }

                if (!$order) {
                    return redirect()->route('home')->with('error', 'Order not found.');
                }

                if ($data['status'] === 'success') {
                    // Payment successful
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => Order::STATUS_CONFIRMED,
                        'paid_at' => now(),
                    ]);

                    // Clear the cart
                    session()->forget('cart');
                    session()->forget('pending_order_id');

                    return redirect()->route('checkout.success', $order)
                        ->with('success', __('messages.payment_success'));
                } else {
                    // Payment failed
                    $order->update([
                        'payment_status' => 'failed',
                    ]);

                    return redirect()->route('checkout.failed', $order)
                        ->with('error', __('messages.payment_failed'));
                }
            }

            return redirect()->route('home')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Paystack callback error', [
                'error' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return redirect()->route('home')->with('error', 'Payment verification error.');
        }
    }

    /**
     * Handle Paystack webhook
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        $secretKey = $this->getPaystackSecretKey();
        $signature = $request->header('x-paystack-signature');

        if (!$signature) {
            return response()->json(['error' => 'No signature'], 400);
        }

        $computedSignature = hash_hmac('sha512', $request->getContent(), $secretKey);

        if ($signature !== $computedSignature) {
            Log::warning('Invalid Paystack webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        if ($event === 'charge.success') {
            $data = $payload['data'] ?? [];
            $reference = $data['reference'] ?? null;

            if ($reference) {
                $order = Order::where('payment_reference', $reference)->first();

                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => Order::STATUS_CONFIRMED,
                        'paid_at' => now(),
                    ]);

                    Log::info('Order paid via webhook', ['order_id' => $order->id]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
