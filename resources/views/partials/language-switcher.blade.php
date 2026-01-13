<div class="language-switcher">
    <button class="lang-toggle" onclick="toggleLanguageMenu()">
        <i class="fas fa-globe"></i>
        <span class="current-lang">{{ strtoupper($currentLocale ?? 'EN') }}</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    <div class="lang-dropdown" id="langDropdown">
        <div class="lang-header">
            <span>{{ __('messages.select_language') }}</span>
            @if(isset($currentCurrency))
                <span class="currency-badge">{{ $currentCurrency }}</span>
            @endif
        </div>

        @if(isset($availableLanguages))
            @foreach($availableLanguages as $code => $name)
                <a href="{{ route('language.switch', $code) }}"
                   class="lang-option {{ ($currentLocale ?? 'en') === $code ? 'active' : '' }}">
                    <span class="lang-code">{{ strtoupper($code) }}</span>
                    <span class="lang-name">{{ $name }}</span>
                    @if(($currentLocale ?? 'en') === $code)
                        <i class="fas fa-check"></i>
                    @endif
                </a>
            @endforeach
        @endif
    </div>
</div>

<style>
.language-switcher {
    position: relative;
    z-index: 9999;
}

.lang-toggle {
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

.lang-toggle:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.4);
}

.lang-toggle .fa-globe {
    font-size: 14px;
}

.lang-toggle .current-lang {
    font-weight: 600;
    font-size: 12px;
}

.lang-toggle .fa-chevron-down {
    font-size: 8px;
    transition: transform 0.3s;
    opacity: 0.7;
}

.language-switcher.open .lang-toggle .fa-chevron-down {
    transform: rotate(180deg);
}

.lang-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    right: 0;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    min-width: 200px;
    max-height: 350px;
    overflow-y: auto;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 99999;
}

.language-switcher.open .lang-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.lang-header {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #666;
    background: #f8f9fa;
    font-weight: 500;
    position: sticky;
    top: 0;
}

.currency-badge {
    background: #0079C1;
    color: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: 600;
}

.lang-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    text-decoration: none !important;
    color: #333 !important;
    transition: background 0.2s;
    border-bottom: 1px solid #f5f5f5;
}

.lang-option:last-child {
    border-bottom: none;
}

.lang-option:hover {
    background: #f0f7fc;
    color: #333 !important;
}

.lang-option.active {
    background: #e3f2fd;
}

.lang-code {
    font-weight: 600;
    width: 32px;
    font-size: 12px;
    color: #555;
}

.lang-option.active .lang-code {
    color: #0079C1;
}

.lang-name {
    flex: 1;
    font-size: 13px;
    color: #333;
}

.lang-option.active .lang-name {
    color: #0079C1;
    font-weight: 500;
}

.lang-option .fa-check {
    color: #0079C1;
    font-size: 11px;
}
</style>

<script>
function toggleLanguageMenu() {
    const switcher = document.querySelector('.language-switcher');
    switcher.classList.toggle('open');

    // Close country menu if open
    const countrySwitcher = document.querySelector('.country-switcher');
    if (countrySwitcher) {
        countrySwitcher.classList.remove('open');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const switcher = document.querySelector('.language-switcher');
    if (switcher && !switcher.contains(e.target)) {
        switcher.classList.remove('open');
    }
});
</script>
