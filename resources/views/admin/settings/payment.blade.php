@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="settings-nav" style="display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap;">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
            <i class="fas fa-cog"></i> General
        </a>
        <a href="{{ route('admin.settings.seo') }}" class="btn btn-outline">
            <i class="fas fa-search"></i> SEO
        </a>
        <a href="{{ route('admin.settings.appearance') }}" class="btn btn-outline">
            <i class="fas fa-palette"></i> Appearance
        </a>
        <a href="{{ route('admin.settings.content') }}" class="btn btn-outline">
            <i class="fas fa-edit"></i> Content
        </a>
        <a href="{{ route('admin.settings.about-us') }}" class="btn btn-outline">
            <i class="fas fa-info-circle"></i> About Us
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-primary">
            <i class="fas fa-credit-card"></i> Payment
        </a>
    </div>

    <form action="{{ route('admin.settings.payment.update') }}" method="POST" id="payment-settings-form">
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
                               {{ !empty($settings['paystack_enabled']) && $settings['paystack_enabled'] != '0' ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Enable Paystack Payments</span>
                    </label>
                    <p class="form-help">When enabled, customers can pay online using Paystack.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="paystack_public_key">Paystack Public Key</label>
                        <input type="text" id="paystack_public_key" name="paystack_public_key" class="form-control"
                               value="{{ $settings['paystack_public_key'] ?? '' }}"
                               placeholder="pk_live_xxxxxxxxxxxxxxxx">
                        <p class="form-help">Your Paystack public/publishable key.</p>
                    </div>

                    <div class="form-group">
                        <label for="paystack_secret_key">Paystack Secret Key</label>
                        <input type="password" id="paystack_secret_key" name="paystack_secret_key" class="form-control"
                               value="{{ $settings['paystack_secret_key'] ?? '' }}"
                               placeholder="sk_live_xxxxxxxxxxxxxxxx">
                        <p class="form-help">Your Paystack secret key. Keep this secure!</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="paystack_test_mode" value="1"
                               {{ !isset($settings['paystack_test_mode']) || (!empty($settings['paystack_test_mode']) && $settings['paystack_test_mode'] != '0') ? 'checked' : '' }}>
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
                    <label class="toggle-label">
                        <input type="checkbox" name="currency_auto_update" value="1" id="currency_auto_toggle"
                               {{ !isset($settings['currency_auto_update']) || (!empty($settings['currency_auto_update']) && $settings['currency_auto_update'] != '0') ? 'checked' : '' }}>
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Auto-Update Exchange Rate</span>
                    </label>
                    <p class="form-help">
                        When enabled, exchange rates are automatically fetched from free currency APIs every 6 hours.
                        Disable to use a manual rate.
                    </p>
                </div>

                @php
                    $currencyAutoUpdate = !isset($settings['currency_auto_update']) || (!empty($settings['currency_auto_update']) && $settings['currency_auto_update'] != '0');
                @endphp
                <div class="auto-rate-info" id="autoRateInfo" style="{{ $currencyAutoUpdate ? '' : 'display: none;' }}">
                    <div class="info-box success">
                        <i class="fas fa-sync-alt"></i>
                        <div>
                            <strong>Auto Currency Conversion Active</strong>
                            <p>
                                Current live rate: <strong>1 ZAR = {{ number_format($settings['mzn_exchange_rate'] ?? 3.5, 4) }} MZN</strong><br>
                                <small>Rates are cached for 6 hours and fetched from open.er-api.com</small>
                            </p>
                            <button type="button" class="btn btn-sm btn-outline" onclick="refreshRate()" id="refreshBtn">
                                <i class="fas fa-sync"></i> Refresh Rate Now
                            </button>
                        </div>
                    </div>
                </div>

                <div class="manual-rate-section" id="manualRateSection" style="{{ $currencyAutoUpdate ? 'display: none;' : '' }}">
                    <div class="form-group">
                        <label for="mzn_exchange_rate">Manual MZN Exchange Rate (1 ZAR = X MZN)</label>
                        <input type="number" id="mzn_exchange_rate" name="mzn_exchange_rate" class="form-control"
                               value="{{ $settings['mzn_exchange_rate'] ?? '3.50' }}"
                               step="0.0001" min="0.01">
                        <p class="form-help">
                            Enter the exchange rate manually. This rate will be used for all conversions.
                        </p>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label for="exchange_rate_markup">Exchange Rate Markup (%)</label>
                    <input type="number" id="exchange_rate_markup" name="exchange_rate_markup" class="form-control"
                           value="{{ $settings['exchange_rate_markup'] ?? '0' }}"
                           step="0.01" min="0" max="100" style="max-width: 200px;">
                    <p class="form-help">
                        Add a percentage markup on top of the exchange rate. This markup will be applied to all product prices when converting to MZN.
                    </p>
                </div>

                <div class="currency-preview">
                    <h4>Preview:</h4>
                    @php
                        $baseRate = $settings['mzn_exchange_rate'] ?? 3.5;
                        $markup = $settings['exchange_rate_markup'] ?? 0;
                        $effectiveRate = $baseRate * (1 + $markup / 100);
                    @endphp
                    <p>
                        <strong>Base Rate:</strong> 1 ZAR = <span id="base-rate-display">{{ number_format($baseRate, 4) }}</span> MZN<br>
                        <strong>Markup:</strong> <span id="markup-display">{{ number_format($markup, 2) }}</span>%<br>
                        <strong>Effective Rate:</strong> 1 ZAR = <span id="effective-rate-display">{{ number_format($effectiveRate, 4) }}</span> MZN
                    </p>
                    <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;">
                        <strong>R 100.00</strong> (ZAR) =
                        <strong>MT <span id="mzn-preview">{{ number_format(100 * $effectiveRate, 2) }}</span></strong> (MZN)
                    </p>
                </div>

                <div class="info-box" style="margin-top: 20px;">
                    <i class="fas fa-globe"></i>
                    <div>
                        <strong>Geo-Location Based Currency</strong>
                        <p>
                            Customers from South Africa automatically see prices in ZAR.<br>
                            Customers from Mozambique automatically see prices in MZN (converted using the exchange rate above).
                        </p>
                    </div>
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

        .info-box.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .info-box.success i {
            color: #28a745;
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
        // Function to update all preview values
        function updatePreview() {
            const rate = parseFloat(document.getElementById('mzn_exchange_rate')?.value) || {{ $settings['mzn_exchange_rate'] ?? 3.5 }};
            const markup = parseFloat(document.getElementById('exchange_rate_markup')?.value) || 0;
            const effectiveRate = rate * (1 + markup / 100);

            document.getElementById('base-rate-display').textContent = rate.toFixed(4);
            document.getElementById('markup-display').textContent = markup.toFixed(2);
            document.getElementById('effective-rate-display').textContent = effectiveRate.toFixed(4);
            document.getElementById('mzn-preview').textContent = (100 * effectiveRate).toFixed(2);
        }

        // Update preview when exchange rate changes
        document.getElementById('mzn_exchange_rate')?.addEventListener('input', updatePreview);

        // Update preview when markup changes
        document.getElementById('exchange_rate_markup')?.addEventListener('input', updatePreview);

        // Toggle auto/manual rate sections
        document.getElementById('currency_auto_toggle')?.addEventListener('change', function() {
            const autoInfo = document.getElementById('autoRateInfo');
            const manualSection = document.getElementById('manualRateSection');

            if (this.checked) {
                autoInfo.style.display = '';
                manualSection.style.display = 'none';
            } else {
                autoInfo.style.display = 'none';
                manualSection.style.display = '';
            }
        });

        // Refresh exchange rate from API
        function refreshRate() {
            const btn = document.getElementById('refreshBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
            btn.disabled = true;

            fetch('{{ route("admin.settings.currency.refresh") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rate = data.rate;
                    const markup = parseFloat(document.getElementById('exchange_rate_markup')?.value) || 0;
                    const effectiveRate = rate * (1 + markup / 100);

                    // Update preview displays
                    document.getElementById('base-rate-display').textContent = rate.toFixed(4);
                    document.getElementById('effective-rate-display').textContent = effectiveRate.toFixed(4);
                    document.getElementById('mzn-preview').textContent = (100 * effectiveRate).toFixed(2);

                    // Update the info text
                    const infoBox = document.querySelector('.auto-rate-info .info-box p');
                    if (infoBox) {
                        infoBox.innerHTML = `Current live rate: <strong>1 ZAR = ${rate.toFixed(4)} MZN</strong><br><small>Rates are cached for 6 hours and fetched from open.er-api.com</small>`;
                    }

                    alert('Exchange rate refreshed! New rate: 1 ZAR = ' + rate.toFixed(4) + ' MZN (Effective with markup: ' + effectiveRate.toFixed(4) + ' MZN)');
                } else {
                    alert('Failed to refresh rate: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to refresh exchange rate. Please try again.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

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
