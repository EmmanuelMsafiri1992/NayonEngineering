@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $imagePosition = $settings['image_position'] ?? 'right';
    $imageUrl = $settings['image'] ?? ($section->background_image ? Storage::url($section->background_image) : null);
@endphp

<section class="section text-image-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;">
            @if($imagePosition == 'left' && $imageUrl)
            <div>
                <img src="{{ $imageUrl }}" alt="{{ $section->title }}" style="width: 100%; border-radius: 8px;">
            </div>
            @endif
            <div>
                @if($section->title)
                    <h2 style="font-size: 32px; margin-bottom: 20px; {{ $textStyle }}">{{ $section->title }}</h2>
                @endif
                @if($section->content)
                    <div style="line-height: 1.8; {{ $textStyle }}">
                        {!! $section->content !!}
                    </div>
                @endif
            </div>
            @if($imagePosition != 'left' && $imageUrl)
            <div>
                <img src="{{ $imageUrl }}" alt="{{ $section->title }}" style="width: 100%; border-radius: 8px;">
            </div>
            @endif
        </div>
    </div>
</section>
