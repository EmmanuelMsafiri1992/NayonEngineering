@extends('layouts.app')

@section('title', __('messages.order_placed'))

@section('content')
<div class="checkout-result">
    <div class="container">
        <div class="result-card success">
            <div class="result-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <h1>{{ __('messages.order_placed') }}</h1>

            @if($order->payment_status === 'paid')
                <p class="result-message">
                    {{ __('messages.payment_success') }}
                </p>
            @else
                <p class="result-message">
                    Your order has been placed successfully. We will contact you shortly with payment details.
                </p>
            @endif

            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.order_number') }}:</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.order_date') }}:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.total') }}:</span>
                    <span class="info-value">
                        @php
                            $currencyService = app(\App\Services\CurrencyService::class);
                            $displayTotal = $order->currency === 'MZN' ? $order->total * $order->exchange_rate : $order->total;
                        @endphp
                        {{ $currencyService->format($displayTotal, $order->currency) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.payment_status') }}:</span>
                    <span class="info-value status-{{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="order-items-summary">
                <h3>{{ __('messages.items') }}</h3>
                @foreach($order->items as $item)
                <div class="summary-item">
                    <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                    <span>
                        @php
                            $itemTotal = $item['price'] * $item['quantity'];
                            $displayItemTotal = $order->currency === 'MZN' ? $itemTotal * $order->exchange_rate : $itemTotal;
                        @endphp
                        {{ $currencyService->format($displayItemTotal, $order->currency) }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="result-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> {{ __('messages.home') }}
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline">
                    <i class="fas fa-shopping-bag"></i> {{ __('messages.continue_shopping') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-result {
    padding: 60px 0;
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
}

.result-card {
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.result-icon {
    font-size: 70px;
    margin-bottom: 20px;
}

.success .result-icon {
    color: #28a745;
}

.result-card h1 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #333;
}

.result-message {
    color: #666;
    margin-bottom: 30px;
}

.order-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    text-align: left;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #777;
}

.info-value {
    font-weight: 600;
    color: #333;
}

.status-paid {
    color: #28a745;
}

.status-pending {
    color: #ffc107;
}

.status-failed {
    color: #dc3545;
}

.order-items-summary {
    text-align: left;
    margin-bottom: 25px;
}

.order-items-summary h3 {
    font-size: 16px;
    margin-bottom: 15px;
    color: #333;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #555;
    font-size: 14px;
}

.result-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.result-actions .btn {
    padding: 12px 25px;
    display: flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 576px) {
    .result-actions {
        flex-direction: column;
    }

    .result-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
