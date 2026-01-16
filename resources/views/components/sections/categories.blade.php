@php
    use App\Models\Category;

    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $categoryIds = $settings['category_ids'] ?? [];
    $limit = $settings['limit'] ?? 8;

    // Get categories
    if (!empty($categoryIds)) {
        $categories = Category::whereIn('id', $categoryIds)->active()->get();
    } else {
        $categories = Category::active()->ordered()->limit($limit)->get();
    }
@endphp

<section class="section categories-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" style="display: block; background: var(--card-bg, #fff); border-radius: 8px; padding: 30px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; transition: transform 0.3s;">
                <i class="fas {{ $category->icon ?? 'fa-folder' }}" style="font-size: 40px; color: var(--primary-blue, #0079c1); margin-bottom: 15px;"></i>
                <h4 style="margin: 0; {{ $textStyle }}">{{ $category->name }}</h4>
                <p style="margin: 10px 0 0; font-size: 14px; color: var(--light-gray, #666);">{{ $category->activeProducts()->count() }} Products</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
