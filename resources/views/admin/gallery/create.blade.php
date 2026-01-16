@extends('admin.layouts.app')

@section('title', 'Add Project to Gallery')

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-8" style="width: 66.666%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Project Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Project Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug (URL)</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #666;">/gallery/</span>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated-from-title">
                            </div>
                            <small style="color: #666;">Leave empty to auto-generate from title</small>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" name="client" class="form-control" value="{{ old('client') }}" placeholder="e.g., ABC Company">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g., Johannesburg, SA">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Completion Date</label>
                            <input type="date" name="completion_date" class="form-control" value="{{ old('completion_date') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Project Description</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Describe the project, what was done, challenges overcome, etc.">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Gallery Images</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Upload Multiple Images</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple id="gallery-images-input">
                            <small style="color: #666;">Select multiple images to upload. Maximum 5MB per image.</small>
                        </div>
                        <div id="gallery-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;"></div>
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
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span>Active (visible on website)</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <span>Featured Project</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Add Project
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Main Image</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="file" name="image" class="form-control" accept="image/*" id="main-image-input">
                            <small style="color: #666;">This will be the cover image. Recommended: 800x600 pixels</small>
                        </div>
                        <div id="main-image-preview" style="margin-top: 15px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Main image preview
    document.getElementById('main-image-input').addEventListener('change', function(e) {
        const preview = document.getElementById('main-image-preview');
        preview.innerHTML = '';

        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'max-width: 100%; border-radius: 4px;';
                preview.appendChild(img);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Gallery images preview
    document.getElementById('gallery-images-input').addEventListener('change', function(e) {
        const preview = document.getElementById('gallery-preview');
        preview.innerHTML = '';

        if (this.files) {
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.style.cssText = 'position: relative;';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 4px;';

                    div.appendChild(img);
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endpush
