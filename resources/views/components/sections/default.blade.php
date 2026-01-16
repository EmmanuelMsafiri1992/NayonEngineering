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

<section class="section" style="{{ $bgStyle }}">
    <div class="container">
        @if($section->title)
            <h2 style="margin-bottom: 20px; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div class="section-content" style="{{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
    </div>
</section>
