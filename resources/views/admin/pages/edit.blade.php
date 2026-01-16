@extends('admin.layouts.app')

@section('title', 'Edit Page: ' . $page->title)

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
        <a href="{{ route('admin.pages.preview', $page) }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-eye"></i> Preview
        </a>
        <a href="{{ url('/page/' . $page->slug) }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Live
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-8" style="width: 66.666%;">
                <div class="card">
                    <div class="card-header">
                        <h3>Page Content</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Page Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug (URL)</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #666;">/page/</span>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Excerpt (Short Description)</label>
                            <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $page->excerpt) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Page Content</label>
                            <textarea name="content" id="content-editor" class="form-control" rows="15">{{ old('content', $page->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Page Sections -->
                <div class="card">
                    <div class="card-header">
                        <h3>Page Sections</h3>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openAddSectionModal()">
                            <i class="fas fa-plus"></i> Add Section
                        </button>
                    </div>
                    <div class="card-body">
                        @if($sections->isEmpty())
                            <div class="empty-state" style="padding: 30px;">
                                <i class="fas fa-puzzle-piece"></i>
                                <p>No sections added yet</p>
                                <button type="button" class="btn btn-primary btn-sm" style="margin-top: 10px;" onclick="openAddSectionModal()">
                                    Add Your First Section
                                </button>
                            </div>
                        @else
                            <div id="sections-list">
                                @foreach($sections as $section)
                                <div class="section-item" data-id="{{ $section->id }}" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px; margin-bottom: 10px; background: {{ $section->is_active ? '#fff' : '#f8f8f8' }};">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-grip-vertical" style="color: #ccc; cursor: move;"></i>
                                            <i class="fas {{ $sectionTypes[$section->type]['icon'] ?? 'fa-puzzle-piece' }}" style="color: #0079c1;"></i>
                                            <div>
                                                <strong>{{ $section->title ?: $sectionTypes[$section->type]['name'] ?? ucfirst($section->type) }}</strong>
                                                <span class="badge {{ $section->is_active ? 'badge-success' : 'badge-secondary' }}" style="margin-left: 5px;">
                                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <button type="button" class="action-btn edit" title="Edit" onclick="openEditSectionModal({{ $section->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="action-btn" style="background: rgba(255,193,7,0.1); color: #856404;" title="Toggle" onclick="toggleSection({{ $section->id }})">
                                                <i class="fas fa-{{ $section->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                            <button type="button" class="action-btn delete" title="Delete" onclick="deleteSection({{ $section->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if($section->content)
                                        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; font-size: 13px; color: #666;">
                                            {{ Str::limit(strip_tags($section->content), 100) }}
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>SEO Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Leave empty to use page title">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3">
                        </div>

                        <div class="form-group">
                            <label class="form-label">OG Image</label>
                            @if($page->og_image)
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ Storage::url($page->og_image) }}" alt="OG Image" style="max-width: 200px; border-radius: 4px;">
                                </div>
                            @endif
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
                            <textarea name="custom_css" class="form-control" rows="4" style="font-family: monospace;">{{ old('custom_css', $page->custom_css) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Custom JavaScript</label>
                            <textarea name="custom_js" class="form-control" rows="4" style="font-family: monospace;">{{ old('custom_js', $page->custom_js) }}</textarea>
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
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                                <span>Published</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}>
                                <span>Set as Homepage</span>
                            </label>
                        </div>

                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e0e0e0;">

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header', $page->show_in_header) ? 'checked' : '' }}>
                                <span>Show in Header Menu</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer', $page->show_in_footer) ? 'checked' : '' }}>
                                <span>Show in Footer Menu</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $page->sort_order) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Update Page
                        </button>

                        <p style="margin-top: 15px; font-size: 12px; color: #666;">
                            Created: {{ $page->created_at->format('M d, Y H:i') }}<br>
                            Updated: {{ $page->updated_at->format('M d, Y H:i') }}
                        </p>
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
                                    <option value="{{ $key }}" {{ old('template', $page->template) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Layout Width</label>
                            <select name="layout_width" class="form-control">
                                <option value="container" {{ old('layout_width', $page->layout_width) == 'container' ? 'selected' : '' }}>Container (Centered)</option>
                                <option value="full-width" {{ old('layout_width', $page->layout_width) == 'full-width' ? 'selected' : '' }}>Full Width</option>
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
                                <input type="checkbox" name="show_header" value="1" {{ old('show_header', $page->show_header) ? 'checked' : '' }}>
                                <span>Show Site Header</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_footer" value="1" {{ old('show_footer', $page->show_footer) ? 'checked' : '' }}>
                                <span>Show Site Footer</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="show_breadcrumbs" value="1" {{ old('show_breadcrumbs', $page->show_breadcrumbs) ? 'checked' : '' }}>
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
                        @if($page->featured_image)
                            <div style="margin-bottom: 10px;">
                                <img src="{{ Storage::url($page->featured_image) }}" alt="Featured Image" style="max-width: 100%; border-radius: 4px;">
                            </div>
                        @endif
                        <div class="form-group">
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small style="color: #666;">Recommended: 1200x630 pixels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Add Section Modal -->
    <div id="addSectionModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0;">Add Section</h3>
                <button type="button" onclick="closeAddSectionModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            <form action="{{ route('admin.pages.sections.store', $page) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label class="form-label">Section Type *</label>
                        <select name="type" class="form-control" required>
                            @foreach($sectionTypes as $key => $type)
                                <option value="{{ $key }}">{{ $type['name'] }} - {{ $type['description'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Section Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Background Color</label>
                                <input type="color" name="background_color" class="form-control" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <input type="color" name="text_color" class="form-control" value="#333333" style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Image</label>
                        <input type="file" name="background_image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span>Active</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 20px; border-top: 1px solid #e0e0e0; text-align: right;">
                    <button type="button" class="btn btn-outline" onclick="closeAddSectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div id="editSectionModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0;">Edit Section</h3>
                <button type="button" onclick="closeEditSectionModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            <form id="editSectionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label class="form-label">Section Type *</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            @foreach($sectionTypes as $key => $type)
                                <option value="{{ $key }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Section Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Content</label>
                        <textarea name="content" id="edit_content" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Background Color</label>
                                <input type="color" name="background_color" id="edit_bg_color" class="form-control" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <input type="color" name="text_color" id="edit_text_color" class="form-control" style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Image</label>
                        <input type="file" name="background_image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 20px; border-top: 1px solid #e0e0e0; text-align: right;">
                    <button type="button" class="btn btn-outline" onclick="closeEditSectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Section Form -->
    <form id="deleteSectionForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // TinyMCE Editor
    tinymce.init({
        selector: '#content-editor',
        height: 400,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }'
    });

    // Section data
    const sectionsData = @json($sections->keyBy('id'));

    // Sortable sections
    const sectionsList = document.getElementById('sections-list');
    if (sectionsList) {
        new Sortable(sectionsList, {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function(evt) {
                const order = Array.from(sectionsList.children).map(item => item.dataset.id);
                fetch('{{ route("admin.pages.sections.reorder", $page) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                });
            }
        });
    }

    // Modal functions
    function openAddSectionModal() {
        document.getElementById('addSectionModal').style.display = 'flex';
    }

    function closeAddSectionModal() {
        document.getElementById('addSectionModal').style.display = 'none';
    }

    function openEditSectionModal(sectionId) {
        const section = sectionsData[sectionId];
        if (section) {
            document.getElementById('editSectionForm').action = '{{ url("admin/pages/" . $page->id . "/sections") }}/' + sectionId;
            document.getElementById('edit_type').value = section.type;
            document.getElementById('edit_title').value = section.title || '';
            document.getElementById('edit_content').value = section.content || '';
            document.getElementById('edit_bg_color').value = section.background_color || '#ffffff';
            document.getElementById('edit_text_color').value = section.text_color || '#333333';
            document.getElementById('edit_is_active').checked = section.is_active;
            document.getElementById('editSectionModal').style.display = 'flex';
        }
    }

    function closeEditSectionModal() {
        document.getElementById('editSectionModal').style.display = 'none';
    }

    function toggleSection(sectionId) {
        fetch('{{ url("admin/pages/" . $page->id . "/sections") }}/' + sectionId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }

    function deleteSection(sectionId) {
        if (confirm('Are you sure you want to delete this section?')) {
            const form = document.getElementById('deleteSectionForm');
            form.action = '{{ url("admin/pages/" . $page->id . "/sections") }}/' + sectionId;
            form.submit();
        }
    }

    // Close modals on outside click
    document.getElementById('addSectionModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddSectionModal();
    });
    document.getElementById('editSectionModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditSectionModal();
    });
</script>
@endpush
