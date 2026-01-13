@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Shopping Cart</span>
            </div>
            <h1 class="page-title">Shopping Cart</h1>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="section">
        <div class="container">
            @if(empty($cartItems))
                <div class="empty-cart" style="text-align: center; padding: 80px 20px;">
                    <i class="fas fa-shopping-cart" style="font-size: 80px; color: var(--light-gray); margin-bottom: 30px;"></i>
                    <h2>Your Cart is Empty</h2>
                    <p style="color: var(--light-gray); margin-bottom: 30px;">Looks like you haven't added any products to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @else
                <form id="cartForm">
                    @csrf
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr data-product-id="{{ $item['product']->id }}">
                                <td>
                                    <div class="cart-product">
                                        <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                                        <div class="cart-product-info">
                                            <h4><a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a></h4>
                                            <p>SKU: {{ $item['product']->sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item['product']->formatted_price }}</td>
                                <td>
                                    <div class="quantity-input" style="display: inline-flex;">
                                        <button type="button" onclick="updateQuantity({{ $item['product']->id }}, -1)">-</button>
                                        <input type="number" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" readonly>
                                        <button type="button" onclick="updateQuantity({{ $item['product']->id }}, 1)">+</button>
                                    </div>
                                </td>
                                <td><strong>{{ $currencyService->formatPrice($item['total']) }}</strong></td>
                                <td>
                                    <button type="button" onclick="removeFromCart({{ $item['product']->id }})" class="remove-btn" title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom: 20px;">{{ __('messages.order_summary') }}</h3>
                    <div class="summary-row">
                        <span>{{ __('messages.subtotal') }}</span>
                        <span>{{ $currencyService->formatPrice($subtotal) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ __('messages.vat') }}</span>
                        <span>{{ $currencyService->formatPrice($vat) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>{{ __('messages.total') }}</span>
                        <span>{{ $currencyService->formatPrice($total) }}</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
function updateQuantity(productId, change) {
    const row = document.querySelector(`tr[data-product-id="${productId}"]`);
    const input = row.querySelector('input[type="number"]');
    let quantity = parseInt(input.value) + change;

    if (quantity < 1) quantity = 1;
    if (quantity > parseInt(input.max)) quantity = parseInt(input.max);

    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item?')) {
        fetch('{{ route("cart.remove") }}', {
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
                location.reload();
            }
        });
    }
}
</script>
@endpush
