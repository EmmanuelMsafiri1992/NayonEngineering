@extends('layouts.app')

@section('title', __('messages.payment_failed'))

@section('content')
<div class="checkout-result">
    <div class="container">
        <div class="result-card failed">
            <div class="result-icon">
                <i class="fas fa-times-circle"></i>
            </div>

            <h1>{{ __('messages.payment_failed') }}</h1>

            <p class="result-message">
                {{ session('error') ?? 'Your payment could not be processed. Please try again or contact support.' }}
            </p>

            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.order_number') }}:</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.total') }}:</span>
                    <span class="info-value">
                        @php
                            $displayTotal = $order->currency === 'MZN' ? $order->total * $order->exchange_rate : $order->total;
                        @endphp
                        {{ $order->currency === 'MZN' ? 'MT' : 'R' }} {{ number_format($displayTotal, 2) }}
                    </span>
                </div>
            </div>

            <div class="result-actions">
                <a href="{{ route('payment.initiate', $order) }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i> {{ __('messages.try_again') }}
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline">
                    <i class="fas fa-envelope"></i> {{ __('messages.contact') }}
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

.failed .result-icon {
    color: #dc3545;
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
