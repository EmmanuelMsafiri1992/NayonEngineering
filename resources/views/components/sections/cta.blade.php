@php
    $bgStyle = 'background: linear-gradient(135deg, #0079c1 0%, #005a8e 100%);';
    if ($section->background_color) {
        $bgStyle = "background-color: {$section->background_color};";
    }
    if ($section->background_image) {
        $bgStyle = "background-image: linear-gradient(rgba(0,121,193,0.9), rgba(0,90,142,0.9)), url('" . Storage::url($section->background_image) . "'); background-size: cover; background-position: center;";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : 'color: #fff;';
    $settings = $section->settings ?? [];
@endphp

<section class="cta-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container" style="text-align: center;">
        @if($section->title)
            <h2 style="font-size: 36px; margin-bottom: 20px; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="font-size: 18px; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        @if(isset($settings['button_text']) && isset($settings['button_url']))
            <a href="{{ $settings['button_url'] }}" class="btn" style="background: #fff; color: #0079c1; font-size: 16px; padding: 15px 40px; border-radius: 6px; text-decoration: none; display: inline-block;">
                {{ $settings['button_text'] }}
            </a>
        @endif
    </div>
</section>
