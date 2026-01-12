<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()->ordered()->get();

        // Featured products (highest discount)
        $featuredProducts = Product::active()
            ->inStock()
            ->orderByDesc('discount')
            ->limit(8)
            ->get();

        // Best sellers (highest stock)
        $bestSellers = Product::active()
            ->inStock()
            ->orderByDesc('stock')
            ->limit(8)
            ->get();

        // New arrivals (latest products)
        $newArrivals = Product::active()
            ->inStock()
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'bestSellers',
            'newArrivals'
        ));
    }
}
