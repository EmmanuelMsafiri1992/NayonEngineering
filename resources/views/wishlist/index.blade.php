@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Wishlist</span>
            </div>
            <h1 class="page-title">My Wishlist</h1>
        </div>
    </div>

    <!-- Wishlist Section -->
    <section class="section">
        <div class="container">
            @if($products->isEmpty())
                <div class="empty-wishlist" style="text-align: center; padding: 80px 20px;">
                    <i class="far fa-heart" style="font-size: 80px; color: var(--light-gray); margin-bottom: 30px;"></i>
                    <h2>Your Wishlist is Empty</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">Save items you love by clicking the heart icon on products.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
                </div>
            @else
                <div class="product-grid">
                    @foreach($products as $product)
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <div class="product-image">
                            @if($product->discount > 40)
                                <span class="product-badge badge-sale">-{{ $product->discount }}%</span>
                            @endif
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            <div class="product-actions" style="opacity: 1;">
                                <button type="button" onclick="removeFromWishlist({{ $product->id }})" class="product-action-btn" title="Remove from Wishlist" style="background-color: var(--danger-red); color: white; border-color: var(--danger-red);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <span class="product-brand">{{ $product->brand }}</span>
                            <h3 class="product-name">
                                <a href="{{ route('products.show', $product) }}">
                                    {{ Str::limit($product->name, 50) }}
                                </a>
                            </h3>
                            <span class="product-sku">SKU: {{ $product->sku }}</span>
                            <div class="product-pricing">
                                <div class="price-row">
                                    <span class="list-price">{{ $product->formatted_list_price }}</span>
                                    <span class="discount">Save {{ $product->discount }}%</span>
                                </div>
                                <div class="net-price">{{ $product->formatted_price }}</div>
                                <span class="price-vat">excl. VAT</span>
                            </div>
                            <div class="product-stock">
                                @if($product->isInStock())
                                    <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                                @else
                                    <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                                @endif
                            </div>
                            <button type="button" onclick="moveToCart({{ $product->id }})" class="add-to-cart-btn" @if(!$product->isInStock()) disabled @endif>
                                <i class="fas fa-shopping-cart"></i> Move to Cart
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
function removeFromWishlist(productId) {
    fetch('{{ route("wishlist.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`.product-card[data-product-id="${productId}"]`).remove();
            showToast(data.message, 'success');
            updateWishlistCount(data.wishlistCount);

            // Check if wishlist is empty
            if (document.querySelectorAll('.product-card').length === 0) {
                location.reload();
            }
        }
    });
}

function moveToCart(productId) {
    fetch('{{ route("wishlist.moveToCart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`.product-card[data-product-id="${productId}"]`).remove();
            showToast(data.message, 'success');
            updateWishlistCount(data.wishlistCount);
            updateCartCount(data.cartCount);

            // Check if wishlist is empty
            if (document.querySelectorAll('.product-card').length === 0) {
                location.reload();
            }
        }
    });
}
</script>
@endpush
