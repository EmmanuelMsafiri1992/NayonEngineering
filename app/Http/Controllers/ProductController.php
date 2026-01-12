<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('category');

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Subcategory filter
        if ($request->filled('subcategory')) {
            $query->where('subcategory', $request->subcategory);
        }

        // Search filter
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // Price range filter
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Sorting
        switch ($request->get('sort', 'default')) {
            case 'price_asc':
                $query->orderBy('net_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('net_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'discount':
                $query->orderByDesc('discount');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->ordered()->withCount('activeProducts')->get();
        $currentCategory = $request->filled('category')
            ? Category::find($request->category)
            : null;

        $brands = Product::active()
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('products.index', compact(
            'products',
            'categories',
            'currentCategory',
            'brands'
        ));
    }

    public function show(Product $product): View
    {
        $product->load('category');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        return redirect()->route('products.index', ['q' => $request->q]);
    }
}
