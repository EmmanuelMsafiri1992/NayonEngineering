@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $features = $settings['features'] ?? [];
@endphp

<section class="section features-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        @if(!empty($features))
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            @foreach($features as $feature)
            <div style="background: var(--card-bg, #f8f9fa); border-radius: 8px; padding: 30px; text-align: center;">
                @if(isset($feature['icon']))
                <div style="width: 70px; height: 70px; background: var(--primary-blue, #0079c1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas {{ $feature['icon'] }}" style="font-size: 28px; color: #fff;"></i>
                </div>
                @endif
                <h3 style="margin-bottom: 15px; {{ $textStyle }}">{{ $feature['title'] ?? '' }}</h3>
                <p style="color: var(--light-gray, #666); line-height: 1.7;">{{ $feature['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
