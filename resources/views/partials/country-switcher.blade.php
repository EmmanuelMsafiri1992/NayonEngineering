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
    margin-right: 15px;
}

.country-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    color: #fff;
    font-size: 14px;
    transition: all 0.3s;
}

.country-toggle:hover {
    border-color: #0079C1;
    background: rgba(255,255,255,0.1);
}

.country-toggle .country-flag {
    font-size: 18px;
}

.country-toggle .country-currency {
    font-weight: 600;
}

.country-toggle .fa-chevron-down {
    font-size: 10px;
    transition: transform 0.3s;
}

.country-switcher.open .country-toggle .fa-chevron-down {
    transform: rotate(180deg);
}

.country-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s;
    z-index: 1000;
}

.country-switcher.open .country-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.country-header {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    color: #777;
}

.country-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    text-decoration: none;
    color: #333;
    transition: background 0.2s;
}

.country-option:hover {
    background: #f8f9fa;
}

.country-option.active {
    background: #e8f4fc;
}

.country-option .country-flag {
    font-size: 24px;
}

.country-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.country-name {
    font-weight: 600;
    font-size: 14px;
    color: #333;
}

.country-currency-label {
    font-size: 12px;
    color: #777;
}

.country-option.active .country-name,
.country-option.active .country-currency-label {
    color: #0079C1;
}

.country-option .fa-check {
    color: #0079C1;
    font-size: 12px;
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
