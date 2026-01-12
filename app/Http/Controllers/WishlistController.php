<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlist = session()->get('wishlist', []);
        $products = Product::whereIn('id', $wishlist)->get();

        return view('wishlist.index', compact('products'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
                'wishlistCount' => count($wishlist),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product already in wishlist',
            'wishlistCount' => count($wishlist),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
            'wishlistCount' => count($wishlist),
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $message = 'Product removed from wishlist';
        } else {
            $wishlist[] = $productId;
            $message = 'Product added to wishlist';
        }

        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'message' => $message,
            'wishlistCount' => count($wishlist),
            'inWishlist' => in_array($productId, $wishlist),
        ]);
    }

    public function moveToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;

        // Add to cart
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'quantity' => 1,
                'added_at' => now(),
            ];
        }
        session()->put('cart', $cart);

        // Remove from wishlist
        $wishlist = session()->get('wishlist', []);
        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'message' => 'Product moved to cart',
            'wishlistCount' => count($wishlist),
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}
