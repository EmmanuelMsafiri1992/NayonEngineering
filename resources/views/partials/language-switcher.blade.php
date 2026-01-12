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
}

.lang-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 1px solid #ddd;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    color: #333;
    font-size: 14px;
    transition: all 0.3s;
}

.lang-toggle:hover {
    border-color: #0079C1;
    color: #0079C1;
}

.lang-toggle .fa-globe {
    font-size: 16px;
}

.lang-toggle .fa-chevron-down {
    font-size: 10px;
    transition: transform 0.3s;
}

.language-switcher.open .lang-toggle .fa-chevron-down {
    transform: rotate(180deg);
}

.lang-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    min-width: 200px;
    max-height: 400px;
    overflow-y: auto;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s;
    z-index: 1000;
}

.language-switcher.open .lang-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.lang-header {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #777;
}

.currency-badge {
    background: #0079C1;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.lang-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    transition: background 0.2s;
}

.lang-option:hover {
    background: #f8f9fa;
}

.lang-option.active {
    background: #e8f4fc;
    color: #0079C1;
}

.lang-code {
    font-weight: 600;
    width: 35px;
}

.lang-name {
    flex: 1;
    font-size: 14px;
}

.lang-option .fa-check {
    color: #0079C1;
    font-size: 12px;
}
</style>

<script>
function toggleLanguageMenu() {
    const switcher = document.querySelector('.language-switcher');
    switcher.classList.toggle('open');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const switcher = document.querySelector('.language-switcher');
    if (switcher && !switcher.contains(e.target)) {
        switcher.classList.remove('open');
    }
});
</script>
