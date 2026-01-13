@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}">Products</a>
                <span>/</span>
                @if($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a>
                    <span>/</span>
                @endif
                <span>{{ Str::limit($product->name, 40) }}</span>
            </div>
        </div>
    </div>

    <!-- Product Detail Section -->
    <section class="section">
        <div class="container">
            <div class="product-detail">
                <!-- Product Gallery -->
                <div class="product-gallery">
                    <div class="main-image">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             id="mainImage"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&size=400&background=0079c1&color=fff&bold=true&format=svg';">
                    </div>
                    <div class="thumbnail-images">
                        <div class="thumbnail active">
                            <img src="{{ $product->image_url }}"
                                 alt="View 1"
                                 onclick="changeImage(this)"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&size=100&background=0079c1&color=fff&bold=true&format=svg';">
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <span class="product-brand">{{ $product->brand }}</span>
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <span class="product-sku">SKU: {{ $product->sku }}</span>

                    <div class="product-pricing">
                        <div class="price-row">
                            <span class="list-price">{{ $product->formatted_list_price }}</span>
                            <span class="discount">Save {{ $product->discount }}%</span>
                        </div>
                        <div class="net-price">{{ $product->formatted_price }}</div>
                        <span class="price-vat">excl. VAT ({{ $product->formatted_price_with_vat }} incl. VAT)</span>
                    </div>

                    <div class="product-stock mb-3">
                        @if($product->isInStock())
                            <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock - {{ number_format($product->stock) }} units available</span>
                        @else
                            <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>

                    @if($product->description)
                        <p class="product-description mb-3" style="color: var(--light-gray); line-height: 1.8;">
                            {{ $product->description }}
                        </p>
                    @endif

                    <!-- Quantity Selector -->
                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="quantity-input">
                            <button type="button" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="addToCart({{ $product->id }}, document.getElementById('quantity').value)" @if(!$product->isInStock()) disabled @endif>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn btn-outline" onclick="addToWishlist({{ $product->id }})">
                            <i class="far fa-heart"></i> Add to Wishlist
                        </button>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <label>Category:</label>
                            <span>{{ $product->category ? $product->category->name : 'N/A' }}</span>
                        </div>
                        @if($product->subcategory)
                        <div class="meta-item">
                            <label>Subcategory:</label>
                            <span>{{ $product->subcategory }}</span>
                        </div>
                        @endif
                        <div class="meta-item">
                            <label>Brand:</label>
                            <span>{{ $product->brand }}</span>
                        </div>
                        @if($product->warranty)
                        <div class="meta-item">
                            <label>Warranty:</label>
                            <span>{{ $product->warranty }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
                <div class="related-products" style="margin-top: 60px;">
                    <div class="section-header">
                        <h2 class="section-title">Related <span>Products</span></h2>
                        <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="product-grid">
                        @foreach($relatedProducts as $related)
                            @include('partials.product-card', ['product' => $related, 'showBadge' => $related->discount > 40])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
function changeImage(thumbnail) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = thumbnail.querySelector('img').src;

    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.closest('.thumbnail').classList.add('active');
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}
</script>
@endpush
