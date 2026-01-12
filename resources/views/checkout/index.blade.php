@extends('layouts.app')

@section('title', __('messages.checkout'))

@section('content')
<div class="checkout-page">
    <div class="container">
        <h1 class="page-title">{{ __('messages.checkout') }}</h1>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            <div class="checkout-grid">
                <!-- Billing Details -->
                <div class="checkout-details">
                    <div class="checkout-section">
                        <h2>{{ __('messages.billing_details') }}</h2>

                        <div class="form-group">
                            <label for="customer_name">{{ __('messages.full_name') }} *</label>
                            <input type="text" id="customer_name" name="customer_name"
                                   value="{{ old('customer_name', auth()->user()?->name) }}" required>
                            @error('customer_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_email">{{ __('messages.email') }} *</label>
                            <input type="email" id="customer_email" name="customer_email"
                                   value="{{ old('customer_email', auth()->user()?->email) }}" required>
                            @error('customer_email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_phone">{{ __('messages.phone') }} *</label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                   value="{{ old('customer_phone') }}" required>
                            @error('customer_phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_address">{{ __('messages.address') }} *</label>
                            <textarea id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">{{ __('messages.notes') }}</label>
                            <textarea id="notes" name="notes" rows="2"
                                      placeholder="{{ __('messages.notes_placeholder') }}">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="checkout-summary">
                    <div class="checkout-section">
                        <h2>{{ __('messages.order_summary') }}</h2>

                        <div class="order-items">
                            @foreach($cart as $productId => $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder.png') }}"
                                         alt="{{ $item['name'] }}">
                                </div>
                                <div class="item-details">
                                    <span class="item-name">{{ $item['name'] }}</span>
                                    <span class="item-qty">x {{ $item['quantity'] }}</span>
                                </div>
                                <div class="item-price">
                                    @php
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $displayItemTotal = $currency === 'MZN' ? $itemTotal * $exchangeRate : $itemTotal;
                                    @endphp
                                    {{ $currency === 'MZN' ? 'MT' : 'R' }} {{ number_format($displayItemTotal, 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>{{ __('messages.subtotal') }}</span>
                                <span>{{ $currency === 'MZN' ? 'MT' : 'R' }} {{ number_format($displaySubtotal, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>{{ __('messages.vat') }}</span>
                                <span>{{ $currency === 'MZN' ? 'MT' : 'R' }} {{ number_format($displayVat, 2) }}</span>
                            </div>
                            <div class="total-row total-final">
                                <span>{{ __('messages.total') }}</span>
                                <span>{{ $currency === 'MZN' ? 'MT' : 'R' }} {{ number_format($displayTotal, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="payment-method">
                            <h3>{{ __('messages.payment_method') }}</h3>
                            @if($paystackEnabled && $paystackPublicKey)
                                <div class="payment-option">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Paystack (Card, Bank Transfer)</span>
                                </div>
                                <p class="payment-note">
                                    {{ __('messages.payment_processing') }}
                                </p>
                            @else
                                <div class="payment-option">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Pay on Delivery / Bank Transfer</span>
                                </div>
                                <p class="payment-note">
                                    Order now and pay upon delivery or via bank transfer.
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-block checkout-btn">
                            @if($paystackEnabled && $paystackPublicKey)
                                <i class="fas fa-lock"></i> {{ __('messages.pay_now') }}
                            @else
                                <i class="fas fa-shopping-bag"></i> {{ __('messages.place_order') }}
                            @endif
                        </button>

                        <p class="secure-note">
                            <i class="fas fa-shield-alt"></i>
                            Your information is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.checkout-page {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.page-title {
    font-size: 28px;
    margin-bottom: 30px;
    color: #333;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

.checkout-section {
    background: #fff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.checkout-section h2 {
    font-size: 18px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #0079C1;
}

.form-group .error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}

.order-items {
    margin-bottom: 20px;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 50px;
    height: 50px;
    border-radius: 6px;
    overflow: hidden;
    margin-right: 12px;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-name {
    display: block;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.item-qty {
    color: #777;
    font-size: 13px;
}

.item-price {
    font-weight: 600;
    color: #333;
}

.order-totals {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #555;
}

.total-final {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    border-top: 2px solid #eee;
    margin-top: 10px;
    padding-top: 15px;
}

.payment-method {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.payment-method h3 {
    font-size: 16px;
    margin-bottom: 15px;
    color: #333;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.payment-option i {
    font-size: 20px;
    color: #0079C1;
}

.payment-note {
    font-size: 13px;
    color: #777;
    margin-top: 10px;
}

.checkout-btn {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.secure-note {
    text-align: center;
    font-size: 12px;
    color: #777;
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.secure-note i {
    color: #28a745;
}

@media (max-width: 900px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
