@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@if($page->meta_description)
@section('meta_description', $page->meta_description)
@endif

@if($page->meta_keywords)
@section('meta_keywords', $page->meta_keywords)
@endif

@section('content')
    @if($page->show_breadcrumbs)
    <!-- Page Header -->
    <div class="page-header">
        <div class="{{ $page->layout_width == 'full-width' ? 'container-fluid px-4' : 'container' }}">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <h1 class="page-title">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p style="color: var(--light-gray); margin-top: 10px;">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Page Content -->
    @if($page->content)
    <section class="section">
        <div class="{{ $page->layout_width == 'full-width' ? 'container-fluid px-4' : 'container' }}">
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </section>
    @endif

    <!-- Page Sections -->
    @foreach($sections as $section)
        @include('components.sections.' . $section->type, ['section' => $section])
    @endforeach

    @if($page->custom_css)
    <style>
        {!! $page->custom_css !!}
    </style>
    @endif

    @if($page->custom_js)
    <script>
        {!! $page->custom_js !!}
    </script>
    @endif
@endsection
