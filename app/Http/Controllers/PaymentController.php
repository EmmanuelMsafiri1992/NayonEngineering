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
     * Check if PayFast is in test mode
     */
    protected function isPayFastTestMode(): bool
    {
        $testMode = Setting::get('payfast_test_mode');
        return !isset($testMode) || (!empty($testMode) && $testMode != '0');
    }

    /**
     * Get PayFast URL based on mode
     */
    protected function getPayFastUrl(): string
    {
        return $this->isPayFastTestMode()
            ? 'https://sandbox.payfast.co.za/eng/process'
            : 'https://www.payfast.co.za/eng/process';
    }

    /**
     * Get PayFast validation URL based on mode
     */
    protected function getPayFastValidateUrl(): string
    {
        return $this->isPayFastTestMode()
            ? 'https://sandbox.payfast.co.za/eng/query/validate'
            : 'https://www.payfast.co.za/eng/query/validate';
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

    /**
     * Initiate PayFast payment
     */
    public function initiatePayfast(Order $order)
    {
        $merchantId = Setting::get('payfast_merchant_id');
        $merchantKey = Setting::get('payfast_merchant_key');
        $passphrase = Setting::get('payfast_passphrase');

        if (!$merchantId || !$merchantKey) {
            return redirect()->route('checkout.failed', $order)
                ->with('error', 'PayFast payment gateway is not configured.');
        }

        // PayFast only supports ZAR
        $amount = $order->total;

        // Generate unique payment reference
        $paymentReference = $order->order_number . '-' . time();

        // Update order with payment reference and method
        $order->update([
            'payment_reference' => $paymentReference,
            'payment_method' => 'payfast',
        ]);

        // Build PayFast data array
        $data = [
            'merchant_id' => $merchantId,
            'merchant_key' => $merchantKey,
            'return_url' => route('payfast.return'),
            'cancel_url' => route('payfast.cancel', $order),
            'notify_url' => route('payfast.notify'),
            'm_payment_id' => $paymentReference,
            'amount' => number_format($amount, 2, '.', ''),
            'item_name' => 'Order #' . $order->order_number,
            'item_description' => 'Payment for order ' . $order->order_number,
            'email_address' => $order->customer_email,
            'name_first' => explode(' ', $order->customer_name)[0] ?? '',
            'name_last' => explode(' ', $order->customer_name)[1] ?? '',
        ];

        // Generate signature
        $signature = $this->generatePayFastSignature($data, $passphrase);
        $data['signature'] = $signature;

        // Store order ID in session for return handling
        session(['payfast_order_id' => $order->id]);

        // Return view that auto-submits to PayFast
        return view('payment.payfast-redirect', [
            'payfast_url' => $this->getPayFastUrl(),
            'data' => $data,
        ]);
    }

    /**
     * Generate PayFast signature
     */
    protected function generatePayFastSignature(array $data, ?string $passphrase = null): string
    {
        // Create parameter string
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        // Remove last ampersand
        $pfOutput = substr($pfOutput, 0, -1);

        // Add passphrase if set
        if ($passphrase) {
            $pfOutput .= '&passphrase=' . urlencode(trim($passphrase));
        }

        return md5($pfOutput);
    }

    /**
     * Handle PayFast return (success)
     */
    public function payfastReturn(Request $request)
    {
        $orderId = session('payfast_order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Payment session expired.');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Clear session
        session()->forget('payfast_order_id');
        session()->forget('cart');
        session()->forget('pending_order_id');

        // Note: The actual payment confirmation happens via ITN (notify)
        // Here we just show a pending message or success if already confirmed
        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order)
                ->with('success', 'Payment completed successfully!');
        }

        // Payment is still pending confirmation from PayFast ITN
        return redirect()->route('checkout.success', $order)
            ->with('info', 'Payment is being processed. You will receive confirmation shortly.');
    }

    /**
     * Handle PayFast cancel
     */
    public function payfastCancel(Order $order)
    {
        session()->forget('payfast_order_id');

        $order->update([
            'payment_status' => 'cancelled',
        ]);

        return redirect()->route('checkout.failed', $order)
            ->with('error', 'Payment was cancelled.');
    }

    /**
     * Handle PayFast ITN (Instant Transaction Notification)
     */
    public function payfastNotify(Request $request)
    {
        // Log the ITN request
        Log::info('PayFast ITN received', $request->all());

        $pfData = $request->all();

        // Verify the signature
        $passphrase = Setting::get('payfast_passphrase');
        $pfParamString = '';

        foreach ($pfData as $key => $val) {
            if ($key !== 'signature') {
                $pfParamString .= $key . '=' . urlencode($val) . '&';
            }
        }
        $pfParamString = substr($pfParamString, 0, -1);

        if ($passphrase) {
            $pfParamString .= '&passphrase=' . urlencode($passphrase);
        }

        $signature = md5($pfParamString);

        if ($signature !== ($pfData['signature'] ?? '')) {
            Log::warning('PayFast ITN: Invalid signature', [
                'expected' => $signature,
                'received' => $pfData['signature'] ?? 'none',
            ]);
            return response('Invalid signature', 400);
        }

        // Verify the payment with PayFast
        $validHosts = [
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        ];

        $validIps = [];
        foreach ($validHosts as $pfHostname) {
            $ips = gethostbynamel($pfHostname);
            if ($ips !== false) {
                $validIps = array_merge($validIps, $ips);
            }
        }
        $validIps = array_unique($validIps);

        $requestIp = $request->ip();
        if (!in_array($requestIp, $validIps)) {
            Log::warning('PayFast ITN: Invalid source IP', ['ip' => $requestIp]);
            // In production, you might want to reject this
            // For now, we'll continue but log the warning
        }

        // Find the order
        $paymentReference = $pfData['m_payment_id'] ?? null;
        $order = Order::where('payment_reference', $paymentReference)->first();

        if (!$order) {
            Log::error('PayFast ITN: Order not found', ['reference' => $paymentReference]);
            return response('Order not found', 404);
        }

        // Check payment status
        $paymentStatus = $pfData['payment_status'] ?? '';

        if ($paymentStatus === 'COMPLETE') {
            // Verify the amount
            $pfAmount = (float) ($pfData['amount_gross'] ?? 0);
            $orderAmount = (float) $order->total;

            if (abs($pfAmount - $orderAmount) > 0.01) {
                Log::error('PayFast ITN: Amount mismatch', [
                    'expected' => $orderAmount,
                    'received' => $pfAmount,
                ]);
                return response('Amount mismatch', 400);
            }

            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'status' => Order::STATUS_CONFIRMED,
                'paid_at' => now(),
            ]);

            Log::info('PayFast ITN: Order paid', ['order_id' => $order->id]);
        } elseif ($paymentStatus === 'FAILED') {
            $order->update([
                'payment_status' => 'failed',
            ]);
            Log::info('PayFast ITN: Payment failed', ['order_id' => $order->id]);
        } elseif ($paymentStatus === 'PENDING') {
            Log::info('PayFast ITN: Payment pending', ['order_id' => $order->id]);
        }

        return response('OK', 200);
    }
}
