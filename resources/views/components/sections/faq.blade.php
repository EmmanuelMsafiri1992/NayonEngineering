@php
    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $faqs = $settings['faqs'] ?? [];
@endphp

<section class="section faq-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="max-width: 800px; margin: 0 auto;">
            @foreach($faqs as $index => $faq)
            <div class="faq-item" style="border: 1px solid var(--border-color, #e0e0e0); border-radius: 8px; margin-bottom: 15px; overflow: hidden;">
                <button class="faq-question" onclick="toggleFaq({{ $index }})" style="width: 100%; padding: 20px; text-align: left; background: var(--card-bg, #fff); border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; {{ $textStyle }}">
                    <span style="font-weight: 600;">{{ $faq['question'] ?? '' }}</span>
                    <i class="fas fa-chevron-down" id="faq-icon-{{ $index }}"></i>
                </button>
                <div class="faq-answer" id="faq-answer-{{ $index }}" style="display: none; padding: 0 20px 20px; {{ $textStyle }}">
                    <p style="color: var(--light-gray, #666); line-height: 1.7;">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-answer-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    if (answer.style.display === 'none') {
        answer.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        answer.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>
