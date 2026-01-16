@php
    use App\Models\Product;

    $bgStyle = '';
    if ($section->background_color) {
        $bgStyle .= "background-color: {$section->background_color};";
    }
    $textStyle = $section->text_color ? "color: {$section->text_color};" : '';
    $settings = $section->settings ?? [];
    $productIds = $settings['product_ids'] ?? [];
    $showFeatured = $settings['show_featured'] ?? true;
    $limit = $settings['limit'] ?? 8;

    // Get products
    if (!empty($productIds)) {
        $products = Product::whereIn('id', $productIds)->active()->get();
    } elseif ($showFeatured) {
        $products = Product::featured()->active()->limit($limit)->get();
    } else {
        $products = Product::active()->latest()->limit($limit)->get();
    }
@endphp

<section class="section products-section" style="{{ $bgStyle }} padding: 60px 0;">
    <div class="container">
        @if($section->title)
            <h2 style="font-size: 32px; margin-bottom: 40px; text-align: center; {{ $textStyle }}">{{ $section->title }}</h2>
        @endif
        @if($section->content)
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px; {{ $textStyle }}">
                {!! $section->content !!}
            </div>
        @endif
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @foreach($products as $product)
            <div style="background: var(--card-bg, #fff); border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <a href="{{ route('products.show', $product) }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: contain; background: #f8f8f8; padding: 10px;">
                </a>
                <div style="padding: 20px;">
                    <h4 style="margin-bottom: 10px; font-size: 16px;">
                        <a href="{{ route('products.show', $product) }}" style="color: inherit; text-decoration: none;">{{ Str::limit($product->name, 50) }}</a>
                    </h4>
                    <div style="font-size: 18px; font-weight: 600; color: var(--primary-blue, #0079c1);">
                        {{ $product->formatted_price }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
