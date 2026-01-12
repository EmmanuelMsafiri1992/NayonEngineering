@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Add New Category</h3>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Category Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Icon (Font Awesome class)</label>
                            <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="e.g., fa-bolt">
                            <small style="color: var(--text-muted);">Visit fontawesome.com for icons</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategories</label>
                    <input type="text" name="subcategories" class="form-control" value="{{ old('subcategories') }}" placeholder="Separate with commas: Sub1, Sub2, Sub3">
                    <small style="color: var(--text-muted);">Enter subcategories separated by commas</small>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check" style="margin-top: 10px;">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
