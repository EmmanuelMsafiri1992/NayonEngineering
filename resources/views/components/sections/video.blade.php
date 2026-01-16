@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $videoUrl = $settings['video_url'] ?? '';

    // Extract video ID from YouTube or Vimeo URLs
    $embedUrl = '';
    if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
        if (!empty($matches[1])) {
            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
        }
    } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
        preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
        if (!empty($matches[1])) {
            $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
        }
    }
@endphp

<section class="section video-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        @if($embedUrl)
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
                <iframe src="{{ $embedUrl }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
            </div>
        </div>
        @endif
    </div>
</section>
