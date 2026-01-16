@php
    $settings = $section->settings ?? [];
    $height = $settings['height'] ?? '50';
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle = "background-color: {$section->background_color};";
    }
@endphp

<div class="spacer-section" style="{{ $bgStyle }} height: {{ $height }}px;"></div>
