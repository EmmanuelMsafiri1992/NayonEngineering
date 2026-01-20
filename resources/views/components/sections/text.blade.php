@php
    // Sanitize color values to prevent CSS injection
    $sanitizeColor = function($color) {
        if (empty($color)) return '';
        // Only allow valid hex colors or basic color names
        if (preg_match('/^#[0-9A-Fa-f]{3,6}$/', $color)) return $color;
        $validColors = ['white','black','red','green','blue','yellow','orange','purple','pink','gray','grey','transparent'];
        return in_array(strtolower($color), $validColors) ? $color : '';
    };

    $bgStyle = '';
    $bgColor = $sanitizeColor($section->background_color);
    if ($bgColor) {
        $bgStyle .= "background-color: {$bgColor};";
    }
    if ($section->background_image) {
        $bgStyle .= "background-image: url('" . e(Storage::url($section->background_image)) . "'); background-size: cover; background-position: center;";
    }
    $textColor = $sanitizeColor($section->text_color);
    $textStyle = $textColor ? "color: {$textColor};" : '';
@endphp

<section class="section text-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 30px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div class="text-content" style="max-width: 900px; margin: 0 auto; line-height: 1.8; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
    </div>
</section>
