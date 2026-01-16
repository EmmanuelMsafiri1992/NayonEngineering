@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $images = $settings['images'] ?? [];
    $columns = $settings['columns'] ?? 4;
@endphp

<section class="section gallery-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        @if(!empty($images))
        <div style="display: grid; grid-template-columns: repeat({{ $columns }}, 1fr); gap: 15px;">
            @foreach($images as $image)
            <a href="{{ $image['url'] ?? $image }}" data-lightbox="gallery" style="display: block; border-radius: 8px; overflow: hidden;">
                <img src="{{ $image['url'] ?? $image }}" alt="{{ $image['caption'] ?? '' }}" style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s;">
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
