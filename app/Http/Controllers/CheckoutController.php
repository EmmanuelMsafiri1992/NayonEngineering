<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display the checkout page
     */
    public function index(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        // Fetch all products in one query to avoid N+1
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Build cart items with product data and calculate subtotal
        $cartItems = [];
        $subtotal = 0;
        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $itemTotal = $product->net_price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[$productId] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->net_price,
                    'total' => $itemTotal,
                ];
            }
        }

        // If no valid products found, clear cart and redirect
        if (empty($cartItems)) {
            session()->forget('cart');
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        $currency = $this->currencyService->getCurrentCurrency();
        $exchangeRate = $this->currencyService->getExchangeRate();

        // Convert to current currency for display
        $displaySubtotal = $this->currencyService->convert($subtotal, $currency);
        $displayVat = $this->currencyService->convert($vat, $currency);
        $displayTotal = $this->currencyService->convert($total, $currency);

        // Get payment gateway settings
        $paystackEnabled = Setting::get('paystack_enabled', false);
        $paystackPublicKey = Setting::get('paystack_public_key', '');
        $payfastEnabled = Setting::get('payfast_enabled', false);
        $payfastMerchantId = Setting::get('payfast_merchant_id', '');

        $currencyService = $this->currencyService;

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'vat',
            'total',
            'displaySubtotal',
            'displayVat',
            'displayTotal',
            'currency',
            'exchangeRate',
            'paystackEnabled',
            'paystackPublicKey',
            'payfastEnabled',
            'payfastMerchantId',
            'currencyService'
        ));
    }

    /**
     * Process the checkout and create an order
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|in:paystack,payfast,cod',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        // Fetch all products in one query to avoid N+1
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Calculate totals in ZAR (base currency) and build items array
        $subtotal = 0;
        $items = [];
        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $itemTotal = $product->net_price * $item['quantity'];
                $subtotal += $itemTotal;
                $items[] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->net_price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image,
                ];
            }
        }

        // If no valid products found, clear cart and redirect
        if (empty($items)) {
            session()->forget('cart');
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        // Get current currency and exchange rate
        $currency = $this->currencyService->getCurrentCurrency();
        $exchangeRate = $currency === 'MZN' ? $this->currencyService->getExchangeRate() : 1;

        // Determine payment method
        $paymentMethod = $validated['payment_method'] ?? 'cod';

        // Create the order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'items' => $items,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
            'notes' => $validated['notes'],
            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'locale' => app()->getLocale(),
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
        ]);

        // Store order ID in session for payment callback
        session(['pending_order_id' => $order->id]);

        // Handle payment based on selected method
        if ($paymentMethod === 'paystack') {
            $paystackEnabled = Setting::get('paystack_enabled');
            if (!empty($paystackEnabled) && $paystackEnabled != '0') {
                return redirect()->route('payment.initiate', $order);
            }
        } elseif ($paymentMethod === 'payfast') {
            $payfastEnabled = Setting::get('payfast_enabled');
            if (!empty($payfastEnabled) && $payfastEnabled != '0') {
                return redirect()->route('payfast.initiate', $order);
            }
        }

        // COD or no payment gateway enabled - show success
        session()->forget('cart');
        return redirect()->route('checkout.success', $order)->with('success', __('messages.order_placed'));
    }

    /**
     * Display order success page
     */
    public function success(Order $order): View
    {
        return view('checkout.success', compact('order'));
    }

    /**
     * Display order failed page
     */
    public function failed(Order $order): View
    {
        return view('checkout.failed', compact('order'));
    }
}
