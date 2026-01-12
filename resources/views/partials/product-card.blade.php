<div class="product-card">
    <div class="product-image">
        @if(isset($showBadge) && $showBadge)
            <span class="product-badge badge-sale">-{{ $product->discount }}%</span>
        @elseif(isset($isNew) && $isNew)
            <span class="product-badge badge-new">New</span>
        @endif
        <img src="{{ $product->image_url }}"
             alt="{{ $product->name }}"
             loading="lazy"
             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&size=300&background=0079c1&color=fff&bold=true&format=svg';">
        <div class="product-actions">
            <button class="product-action-btn" title="Add to Wishlist" onclick="addToWishlist({{ $product->id }})">
                <i class="far fa-heart"></i>
            </button>
            <a href="{{ route('products.show', $product) }}" class="product-action-btn" title="View Details">
                <i class="fas fa-eye"></i>
            </a>
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
                <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock ({{ number_format($product->stock) }})</span>
            @else
                <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
            @endif
        </div>
        <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})" @if(!$product->isInStock()) disabled @endif>
            <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
    </div>
</div>
