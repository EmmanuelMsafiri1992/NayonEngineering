@php
    $settings = $section->settings ?? [];
    $style = $settings['style'] ?? 'solid';
    $color = $section->background_color ?? '#e0e0e0';
    $width = $settings['width'] ?? '100';
    $thickness = $settings['thickness'] ?? '1';
@endphp

<div class="divider-section" style="padding: 30px 0;">
    <div class="container">
        <hr style="border: none; border-top: {{ $thickness }}px {{ $style }} {{ $color }}; width: {{ $width }}%; margin: 0 auto;">
    </div>
</div>
