@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $testimonials = $settings['testimonials'] ?? [];
@endphp

<section class="section testimonials-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            @foreach($testimonials as $testimonial)
            <div style="background: var(--card-bg, #fff); border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-quote-left" style="font-size: 24px; color: var(--primary-blue, #0079c1);"></i>
                </div>
                <p style="line-height: 1.8; margin-bottom: 20px; {{ $textStyle }}">{{ $testimonial['content'] ?? '' }}</p>
                <div style="display: flex; align-items: center; gap: 15px;">
                    @if(isset($testimonial['avatar']))
                    <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] ?? '' }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    @else
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-blue, #0079c1); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600;">
                        {{ substr($testimonial['name'] ?? 'A', 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <h4 style="margin: 0; {{ $textStyle }}">{{ $testimonial['name'] ?? '' }}</h4>
                        <p style="margin: 0; font-size: 14px; color: var(--light-gray, #666);">{{ $testimonial['position'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
