<div class="country-switcher">
    <button class="country-toggle" onclick="toggleCountryMenu()">
        <span class="country-flag">{{ $currentCountry === 'MZ' ? '🇲🇿' : '🇿🇦' }}</span>
        <span class="country-currency">{{ $currentCurrency ?? 'ZAR' }}</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    <div class="country-dropdown" id="countryDropdown">
        <div class="country-header">
            <span>{{ __('messages.select_country') }}</span>
        </div>

        <a href="{{ route('country.switch', 'ZA') }}"
           class="country-option {{ ($currentCountry ?? 'ZA') === 'ZA' ? 'active' : '' }}">
            <span class="country-flag">🇿🇦</span>
            <div class="country-info">
                <span class="country-name">South Africa</span>
                <span class="country-currency-label">ZAR (R)</span>
            </div>
            @if(($currentCountry ?? 'ZA') === 'ZA')
                <i class="fas fa-check"></i>
            @endif
        </a>

        <a href="{{ route('country.switch', 'MZ') }}"
           class="country-option {{ ($currentCountry ?? 'ZA') === 'MZ' ? 'active' : '' }}">
            <span class="country-flag">🇲🇿</span>
            <div class="country-info">
                <span class="country-name">Mozambique</span>
                <span class="country-currency-label">MZN (MT)</span>
            </div>
            @if(($currentCountry ?? 'ZA') === 'MZ')
                <i class="fas fa-check"></i>
            @endif
        </a>
    </div>
</div>

<style>
.country-switcher {
    position: relative;
    margin-right: 10px;
    z-index: 9999;
}

.country-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    color: #fff;
    font-size: 13px;
    transition: all 0.3s;
}

.country-toggle:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.4);
}

.country-toggle .country-flag {
    font-size: 16px;
    line-height: 1;
}

.country-toggle .country-currency {
    font-weight: 600;
    font-size: 12px;
}

.country-toggle .fa-chevron-down {
    font-size: 8px;
    transition: transform 0.3s;
    opacity: 0.7;
}

.country-switcher.open .country-toggle .fa-chevron-down {
    transform: rotate(180deg);
}

.country-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    right: 0;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 99999;
    overflow: hidden;
}

.country-switcher.open .country-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.country-header {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    font-size: 12px;
    color: #666;
    background: #f8f9fa;
    font-weight: 500;
}

.country-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    text-decoration: none !important;
    color: #333 !important;
    transition: background 0.2s;
    border-bottom: 1px solid #f0f0f0;
}

.country-option:last-child {
    border-bottom: none;
}

.country-option:hover {
    background: #f0f7fc;
    color: #333 !important;
}

.country-option.active {
    background: #e3f2fd;
}

.country-option .country-flag {
    font-size: 20px;
    line-height: 1;
}

.country-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.country-name {
    font-weight: 600;
    font-size: 13px;
    color: #333;
}

.country-currency-label {
    font-size: 11px;
    color: #888;
}

.country-option.active .country-name {
    color: #0079C1;
}

.country-option.active .country-currency-label {
    color: #0079C1;
}

.country-option .fa-check {
    color: #0079C1;
    font-size: 11px;
}
</style>

<script>
function toggleCountryMenu() {
    const switcher = document.querySelector('.country-switcher');
    switcher.classList.toggle('open');

    // Close language menu if open
    const langSwitcher = document.querySelector('.language-switcher');
    if (langSwitcher) {
        langSwitcher.classList.remove('open');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const switcher = document.querySelector('.country-switcher');
    if (switcher && !switcher.contains(e.target)) {
        switcher.classList.remove('open');
    }
});
</script>
