@extends('admin.layouts.app')

@section('title', 'Edit Widget: ' . $widget->name)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.widgets.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Widgets
        </a>
    </div>

    <form action="{{ route('admin.widgets.update', $widget) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-8" style="width: 66.666%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Widget Content</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Widget Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $widget->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Display Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $widget->title) }}" placeholder="Optional title shown to visitors">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Content</label>
                            <textarea name="content" id="content-editor" class="form-control" rows="10">{{ old('content', $widget->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Appearance</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" name="background_color" class="form-control" value="{{ old('background_color', $widget->background_color ?: '#ffffff') }}" style="height: 40px;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Background Image</label>
                                    @if($widget->background_image)
                                        <div style="margin-bottom: 10px;">
                                            <img src="{{ Storage::url($widget->background_image) }}" alt="Background" style="max-width: 150px; border-radius: 4px;">
                                        </div>
                                    @endif
                                    <input type="file" name="background_image" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4" style="width: 33.333%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Widget Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Widget Type *</label>
                            <select name="type" class="form-control" required>
                                @foreach($types as $key => $type)
                                    <option value="{{ $key }}" {{ old('type', $widget->type) == $key ? 'selected' : '' }}>
                                        {{ $type['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Location *</label>
                            <select name="location" class="form-control" required>
                                @foreach($locations as $key => $name)
                                    <option value="{{ $key }}" {{ old('location', $widget->location) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $widget->sort_order) }}" min="0">
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $widget->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Update Widget
                        </button>

                        <p style="margin-top: 15px; font-size: 12px; color: #666;">
                            Created: {{ $widget->created_at->format('M d, Y H:i') }}<br>
                            Updated: {{ $widget->updated_at->format('M d, Y H:i') }}
                        </p>
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
        height: 300,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }'
    });
</script>
@endpush
