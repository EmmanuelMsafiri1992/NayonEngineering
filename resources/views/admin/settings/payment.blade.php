@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="page-header">
        <h1>Payment Settings</h1>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
    </div>

    <form action="{{ route('admin.settings.payment.update') }}" method="POST">
        @csrf

        <!-- Paystack Settings -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-credit-card"></i> Paystack Configuration</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="paystack_enabled" value="1"
                               {{ $settings['paystack_enabled'] ?? false ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Enable Paystack Payments</span>
                    </label>
                    <p class="form-help">When enabled, customers can pay online using Paystack.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="paystack_public_key">Paystack Public Key</label>
                        <input type="text" id="paystack_public_key" name="paystack_public_key"
                               value="{{ $settings['paystack_public_key'] ?? '' }}"
                               placeholder="pk_live_xxxxxxxxxxxxxxxx">
                        <p class="form-help">Your Paystack public/publishable key.</p>
                    </div>

                    <div class="form-group">
                        <label for="paystack_secret_key">Paystack Secret Key</label>
                        <input type="password" id="paystack_secret_key" name="paystack_secret_key"
                               value="{{ $settings['paystack_secret_key'] ?? '' }}"
                               placeholder="sk_live_xxxxxxxxxxxxxxxx">
                        <p class="form-help">Your Paystack secret key. Keep this secure!</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="paystack_test_mode" value="1"
                               {{ $settings['paystack_test_mode'] ?? true ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Test Mode</span>
                    </label>
                    <p class="form-help">Enable test mode to use Paystack test keys for development.</p>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Don't have a Paystack account?</strong>
                        <p>Sign up at <a href="https://paystack.com" target="_blank">paystack.com</a> to get your API keys. Paystack supports payments in ZAR, NGN, GHS, and more.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Settings -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-money-bill-wave"></i> Currency Settings</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="mzn_exchange_rate">MZN Exchange Rate (1 ZAR = X MZN)</label>
                    <input type="number" id="mzn_exchange_rate" name="mzn_exchange_rate"
                           value="{{ $settings['mzn_exchange_rate'] ?? '3.50' }}"
                           step="0.01" min="0.01">
                    <p class="form-help">
                        The exchange rate for converting ZAR to Mozambican Metical (MZN).
                        Customers in Mozambique will see prices in MZN based on this rate.
                    </p>
                </div>

                <div class="currency-preview">
                    <h4>Preview:</h4>
                    <p>
                        <strong>R 100.00</strong> (ZAR) =
                        <strong>MT <span id="mzn-preview">{{ number_format(100 * ($settings['mzn_exchange_rate'] ?? 3.5), 2) }}</span></strong> (MZN)
                    </p>
                </div>
            </div>
        </div>

        <!-- Webhook Configuration -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-link"></i> Webhook Configuration</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Webhook URL</label>
                    <div class="copy-field">
                        <input type="text" readonly value="{{ route('payment.webhook') }}" id="webhook-url">
                        <button type="button" class="btn btn-sm btn-outline" onclick="copyWebhookUrl()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <p class="form-help">
                        Add this URL to your Paystack dashboard under Settings → API Keys & Webhooks → Webhook URL.
                    </p>
                </div>

                <div class="form-group">
                    <label>Callback URL</label>
                    <div class="copy-field">
                        <input type="text" readonly value="{{ route('payment.callback') }}" id="callback-url">
                        <button type="button" class="btn btn-sm btn-outline" onclick="copyCallbackUrl()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <p class="form-help">
                        This is where customers will be redirected after payment.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Payment Settings
            </button>
        </div>
    </form>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            margin: 0;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h2 i {
            color: var(--primary);
        }

        .card-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text);
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-help {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .toggle-label input {
            display: none;
        }

        .toggle-switch {
            width: 50px;
            height: 26px;
            background: #ddd;
            border-radius: 13px;
            position: relative;
            transition: background 0.3s;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: #fff;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
        }

        .toggle-label input:checked + .toggle-switch {
            background: var(--primary);
        }

        .toggle-label input:checked + .toggle-switch::after {
            transform: translateX(24px);
        }

        .toggle-text {
            font-weight: 500;
        }

        .info-box {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #e8f4fc;
            border-radius: 8px;
            margin-top: 20px;
        }

        .info-box i {
            color: var(--primary);
            font-size: 20px;
            margin-top: 2px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        .info-box a {
            color: var(--primary);
        }

        .currency-preview {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .currency-preview h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #777;
        }

        .currency-preview p {
            margin: 0;
            font-size: 16px;
        }

        .copy-field {
            display: flex;
            gap: 10px;
        }

        .copy-field input {
            flex: 1;
            background: #f8f9fa;
        }

        .form-actions {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Update MZN preview
        document.getElementById('mzn_exchange_rate').addEventListener('input', function() {
            const rate = parseFloat(this.value) || 0;
            const mznValue = (100 * rate).toFixed(2);
            document.getElementById('mzn-preview').textContent = mznValue;
        });

        function copyWebhookUrl() {
            const input = document.getElementById('webhook-url');
            input.select();
            document.execCommand('copy');
            alert('Webhook URL copied!');
        }

        function copyCallbackUrl() {
            const input = document.getElementById('callback-url');
            input.select();
            document.execCommand('copy');
            alert('Callback URL copied!');
        }
    </script>
@endsection
