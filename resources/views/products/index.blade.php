@extends('layouts.app')

@section('title', $currentCategory ? $currentCategory->name : 'Products')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                @if($currentCategory)
                    <a href="{{ route('products.index') }}">Products</a>
                    <span>/</span>
                    <span>{{ $currentCategory->name }}</span>
                @else
                    <span>Products</span>
                @endif
            </div>
            <h1 class="page-title">
                @if(request('q'))
                    Search Results for "{{ request('q') }}"
                @elseif($currentCategory)
                    {{ $currentCategory->name }}
                @else
                    All Products
                @endif
            </h1>
        </div>
    </div>

    <!-- Products Section -->
    <section class="section">
        <div class="container">
            <div class="products-layout">
                <!-- Sidebar -->
                <aside class="sidebar">
                    <!-- Categories -->
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Categories</h3>
                        <ul class="category-list">
                            <li>
                                <a href="{{ route('products.index') }}" class="{{ !request('category') ? 'active' : '' }}">
                                    All Products
                                    <span>{{ \App\Models\Product::active()->count() }}</span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                   class="{{ request('category') == $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                    <span>{{ $category->active_products_count }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subcategories -->
                    @if($currentCategory && !empty($currentCategory->subcategories))
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Subcategories</h3>
                        <ul class="category-list">
                            @foreach($currentCategory->subcategories as $sub)
                            <li>
                                <a href="{{ route('products.index', ['category' => $currentCategory->id, 'subcategory' => $sub]) }}"
                                   class="{{ request('subcategory') == $sub ? 'active' : '' }}">
                                    {{ $sub }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Price Filter -->
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Price Range</h3>
                        <form action="{{ route('products.index') }}" method="GET" class="price-range">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                            <button type="submit" class="filter-btn">Apply Filter</button>
                        </form>
                    </div>

                    <!-- Brand Filter -->
                    @if($brands->count() > 1)
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Brands</h3>
                        <ul class="category-list">
                            @foreach($brands as $brand)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->query(), ['brand' => $brand])) }}"
                                   class="{{ request('brand') == $brand ? 'active' : '' }}">
                                    {{ $brand }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </aside>

                <!-- Products Content -->
                <div class="products-content">
                    <!-- Toolbar -->
                    <div class="products-toolbar">
                        <div class="results-count">
                            Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                        </div>
                        <div class="toolbar-right">
                            <select class="sort-select" onchange="window.location.href=this.value">
                                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'default'])) }}"
                                    {{ request('sort') == 'default' ? 'selected' : '' }}>Default Sorting</option>
                                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}"
                                    {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}"
                                    {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'name_asc'])) }}"
                                    {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'discount'])) }}"
                                    {{ request('sort') == 'discount' ? 'selected' : '' }}>Biggest Discount</option>
                            </select>
                            <div class="view-modes">
                                <button class="view-mode active" data-view="grid"><i class="fas fa-th"></i></button>
                                <button class="view-mode" data-view="list"><i class="fas fa-list"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    @if($products->isEmpty())
                        <div class="no-products" style="text-align: center; padding: 60px 20px;">
                            <i class="fas fa-box-open" style="font-size: 64px; color: var(--light-gray); margin-bottom: 20px;"></i>
                            <h3>No Products Found</h3>
                            <p style="color: var(--light-gray);">Try adjusting your filters or search terms</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 20px;">View All Products</a>
                        </div>
                    @else
                        <div class="product-grid">
                            @foreach($products as $product)
                                @include('partials.product-card', ['product' => $product, 'showBadge' => $product->discount > 40])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="pagination">
                                {{ $products->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
