@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
@endphp

<section class="section contact-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="max-width: 600px; margin: 0 auto;">
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <input type="text" name="name" placeholder="Your Name *" required style="width: 100%; padding: 15px; border: 1px solid var(--border-color, #e0e0e0); border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="email" name="email" placeholder="Your Email *" required style="width: 100%; padding: 15px; border: 1px solid var(--border-color, #e0e0e0); border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="text" name="phone" placeholder="Phone Number" style="width: 100%; padding: 15px; border: 1px solid var(--border-color, #e0e0e0); border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="text" name="subject" placeholder="Subject *" required style="width: 100%; padding: 15px; border: 1px solid var(--border-color, #e0e0e0); border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <textarea name="message" placeholder="Your Message *" required rows="5" style="width: 100%; padding: 15px; border: 1px solid var(--border-color, #e0e0e0); border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
                </div>
                <button type="submit" style="width: 100%; padding: 15px; background: var(--primary-blue, #0079c1); color: #fff; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
