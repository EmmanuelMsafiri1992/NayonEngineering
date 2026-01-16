@extends('admin.layouts.app')

@section('title', 'Create Page')

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-8" style="width: 66.666%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Page Content</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Page Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug (URL)</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #666;">/page/</span>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated-from-title">
                            </div>
                            <small style="color: #666;">Leave empty to auto-generate from title</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Excerpt (Short Description)</label>
                            <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Page Content</label>
                            <textarea name="content" id="content-editor" class="form-control" rows="15">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>SEO Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Leave empty to use page title">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                        </div>

                        <div class="form-group">
                            <label class="form-label">OG Image</label>
                            <input type="file" name="og_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Custom Code</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Custom CSS</label>
                            <textarea name="custom_css" class="form-control" rows="4" style="font-family: monospace;">{{ old('custom_css') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Custom JavaScript</label>
                            <textarea name="custom_js" class="form-control" rows="4" style="font-family: monospace;">{{ old('custom_js') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4" style="width: 33.333%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Publish</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <span>Published</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage') ? 'checked' : '' }}>
                                <span>Set as Homepage</span>
                            </label>
                        </div>

                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e0e0e0;">

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header') ? 'checked' : '' }}>
                                <span>Show in Header Menu</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer') ? 'checked' : '' }}>
                                <span>Show in Footer Menu</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Create Page
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Page Template</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Template</label>
                            <select name="template" class="form-control">
                                @foreach($templates as $key => $name)
                                    <option value="{{ $key }}" {{ old('template', 'default') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Layout Width</label>
                            <select name="layout_width" class="form-control">
                                <option value="container" {{ old('layout_width', 'container') == 'container' ? 'selected' : '' }}>Container (Centered)</option>
                                <option value="full-width" {{ old('layout_width') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Layout Options</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_header" value="1" {{ old('show_header', true) ? 'checked' : '' }}>
                                <span>Show Site Header</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_footer" value="1" {{ old('show_footer', true) ? 'checked' : '' }}>
                                <span>Show Site Footer</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_breadcrumbs" value="1" {{ old('show_breadcrumbs', true) ? 'checked' : '' }}>
                                <span>Show Breadcrumbs</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Featured Image</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small style="color: #666;">Recommended: 1200x630 pixels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content-editor',
        height: 400,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }'
    });
</script>
@endpush
