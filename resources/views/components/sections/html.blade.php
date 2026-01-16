@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
@endphp

<section class="section html-section" style="{{ $bgStyle }}">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 30px; text-align: center;">{{ $section->title }}</h2>
        @endif
        {!! $section->content !!}
    </div>
</section>
