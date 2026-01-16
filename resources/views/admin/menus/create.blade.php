@extends('admin.layouts.app')

@section('title', 'Create Menu')

@section('content')
    <div class="toolbar">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Menus
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Menu Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Menu Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g., Main Navigation">
                </div>

                <div class="form-group">
                    <label class="form-label">Location</label>
                    <select name="location" class="form-control">
                        <option value="">-- Select Location --</option>
                        @foreach($locations as $key => $name)
                            <option value="{{ $key }}" {{ old('location') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <small style="color: #666;">Assign this menu to a specific location on the website</small>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Menu
                </button>
            </form>
        </div>
    </div>
@endsection
