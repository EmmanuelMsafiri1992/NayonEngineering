@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    if ($section->background_image) {
        $bgStyle .= "background-image: url('" . Storage::url($section->background_image) . "'); background-size: cover; background-position: center;";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
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
