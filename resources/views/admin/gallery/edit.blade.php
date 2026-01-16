@extends('admin.layouts.app')

@section('title', 'Edit Project - ' . $gallery->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
        <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" data-confirm="Are you sure you want to delete this project?" style="display: inline; margin-left: auto;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete Project
            </button>
        </form>
    </div>

    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-8" style="width: 66.666%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Project Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Project Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $gallery->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug (URL)</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #666;">/gallery/</span>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $gallery->slug) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" name="client" class="form-control" value="{{ old('client', $gallery->client) }}" placeholder="e.g., ABC Company">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $gallery->location) }}" placeholder="e.g., Johannesburg, SA">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Completion Date</label>
                            <input type="date" name="completion_date" class="form-control" value="{{ old('completion_date', $gallery->completion_date?->format('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Project Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description', $gallery->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Current Gallery Images</h3>
                    </div>
                    <div class="card-body">
                        @if($gallery->gallery_images && count($gallery->gallery_images) > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                                @foreach($gallery->gallery_images as $index => $image)
                                    <div style="position: relative; border: 1px solid #e0e0e0; border-radius: 4px; padding: 5px;">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 4px;">
                                        <label style="display: flex; align-items: center; gap: 5px; margin-top: 8px; font-size: 12px; cursor: pointer;">
                                            <input type="checkbox" name="remove_gallery_images[]" value="{{ $image }}">
                                            <span style="color: #dc3545;">Remove</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small style="color: #666;">Check the images you want to remove, then save to apply changes.</small>
                        @else
                            <p style="color: #666;">No gallery images uploaded yet.</p>
                        @endif

                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e0e0e0;">

                        <div class="form-group">
                            <label class="form-label">Add More Images</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple id="gallery-images-input">
                            <small style="color: #666;">Select multiple images to add to the gallery.</small>
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
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                                <span>Active (visible on website)</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                                <span>Featured Project</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $gallery->sort_order) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Update Project
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Main Image</h3>
                    </div>
                    <div class="card-body">
                        @if($gallery->image)
                            <div style="margin-bottom: 15px;">
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" style="max-width: 100%; border-radius: 4px;">
                                <small style="display: block; margin-top: 5px; color: #666;">Current main image</small>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">{{ $gallery->image ? 'Replace Main Image' : 'Upload Main Image' }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*" id="main-image-input">
                            <small style="color: #666;">Recommended: 800x600 pixels</small>
                        </div>
                        <div id="main-image-preview" style="margin-top: 15px;"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Info</h3>
                    </div>
                    <div class="card-body">
                        <p style="margin: 0; font-size: 13px; color: #666;">
                            <strong>Created:</strong> {{ $gallery->created_at->format('M d, Y H:i') }}<br>
                            <strong>Updated:</strong> {{ $gallery->updated_at->format('M d, Y H:i') }}
                        </p>
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

                const label = document.createElement('small');
                label.textContent = 'New image preview';
                label.style.cssText = 'display: block; margin-top: 5px; color: #28a745;';
                preview.appendChild(label);
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
                    img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 2px solid #28a745;';

                    div.appendChild(img);
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endpush
