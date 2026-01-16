@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $address = $settings['address'] ?? '';
    $embedCode = $settings['embed_code'] ?? '';
    $height = $settings['height'] ?? '400';
@endphp

<section class="section map-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="border-radius: 8px; overflow: hidden;">
            @if($embedCode)
                {!! $embedCode !!}
            @elseif($address)
                <iframe
                    src="https://maps.google.com/maps?q={{ urlencode($address) }}&output=embed"
                    width="100%"
                    height="{{ $height }}"
                    style="border: 0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @endif
        </div>
    </div>
</section>
