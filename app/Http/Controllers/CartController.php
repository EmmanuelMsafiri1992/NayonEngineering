<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->net_price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];
            }
        }

        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        return view('cart.index', compact('cartItems', 'subtotal', 'vat', 'total'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->get('quantity', 1);
        $product = Product::findOrFail($productId);

        // Check stock availability
        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;
        $requestedTotal = $currentQty + $quantity;

        if ($product->stock < $requestedTotal) {
            return response()->json([
                'success' => false,
                'message' => $product->stock <= 0
                    ? 'This product is out of stock'
                    : "Only {$product->stock} items available in stock",
                'cartCount' => $this->getCartCount(),
            ], 422);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'added_at' => now(),
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cartCount' => $this->getCartCount(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cartCount' => $this->getCartCount(),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = $request->product_id;
        $cart = session()->get('cart', []);

        unset($cart[$productId]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cartCount' => $this->getCartCount(),
        ]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cartCount' => 0,
        ]);
    }

    private function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
}
