@php
    $bgStyle = 'background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);';
    if ($section->background_color) {
        $bgStyle = "background-color: {$section->background_color};";
    }
    if ($section->background_image) {
        $bgStyle = "background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('" . Storage::url($section->background_image) . "'); background-size: cover; background-position: center;";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : 'color: #fff;';
    $settings = $section->settings ?? [];
@endphp

<section class="hero-section" style="{{ $bgStyle }} padding: 80px 0; min-height: 400px; display: flex; align-items: center;">
    <div class="container" style="text-align: center;">
        @if($section->title)
            <h1 style="font-size: 48px; margin-bottom: 20px; {{ $textStyle }}">{{ $section->title }}</h1>
        @endif
        @if($section->content)
            <div style="font-size: 18px; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        @if(isset($settings['button_text']) && isset($settings['button_url']))
            <a href="{{ $settings['button_url'] }}" class="btn btn-primary" style="font-size: 16px; padding: 15px 40px;">
                {{ $settings['button_text'] }}
            </a>
        @endif
    </div>
</section>
