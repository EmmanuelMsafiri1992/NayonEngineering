<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $page->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0079c1;
            --dark-bg: #1a1a2e;
            --card-bg: #16213e;
            --border-color: #2a2a4a;
            --light-gray: #a0a0a0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; color: #333; }
        .preview-banner {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 9999;
        }
        .preview-banner a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            background: var(--primary-blue);
            border-radius: 4px;
        }
        .section { padding: 60px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .page-header {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--card-bg) 100%);
            color: #fff;
            padding: 40px 0;
        }
        .breadcrumb { font-size: 14px; margin-bottom: 10px; }
        .breadcrumb a { color: var(--light-gray); text-decoration: none; }
        .page-title { font-size: 36px; }
        .page-content { line-height: 1.8; }
        .btn { display: inline-block; padding: 12px 25px; background: var(--primary-blue); color: #fff; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="preview-banner">
        <span><i class="fas fa-eye"></i> Preview Mode - This page is {{ $page->is_published ? 'published' : 'not published yet' }}</span>
        <div>
            <a href="{{ route('admin.pages.edit', $page) }}"><i class="fas fa-edit"></i> Edit Page</a>
        </div>
    </div>

    @if($page->show_breadcrumbs)
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="#">Home</a> / <span>{{ $page->title }}</span>
            </div>
            <h1 class="page-title">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p style="margin-top: 10px; color: var(--light-gray);">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
    @endif

    @if($page->content)
    <section class="section">
        <div class="container">
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </section>
    @endif

    @foreach($page->activeSections as $section)
        @include('components.sections.' . $section->type, ['section' => $section])
    @endforeach

    @if($page->custom_css)
    <style>{!! $page->custom_css !!}</style>
    @endif

    @if($page->custom_js)
    <script>{!! $page->custom_js !!}</script>
    @endif
</body>
</html>
