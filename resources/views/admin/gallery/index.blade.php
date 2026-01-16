@extends('admin.layouts.app')

@section('title', 'Gallery - Completed Projects')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search projects..." value="{{ request('search') }}">
            </form>
        </div>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.gallery.index') }}?status='+this.value+'&search={{ request('search') }}'">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.gallery.index') }}?featured='+this.value+'&search={{ request('search') }}&status={{ request('status') }}'">
            <option value="">All Projects</option>
            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured Only</option>
        </select>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Project
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($projects->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>No projects in gallery yet</p>
                    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary" style="margin-top: 15px;">Add Your First Project</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Date</th>
                                <th>Images</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>
                                    @if($project->image)
                                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($project->title, 35) }}</strong>
                                </td>
                                <td>{{ $project->client ?? '-' }}</td>
                                <td>{{ $project->location ?? '-' }}</td>
                                <td>{{ $project->completion_date ? $project->completion_date->format('M Y') : '-' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $project->gallery_images ? count($project->gallery_images) : 0 }} images
                                    </span>
                                </td>
                                <td>
                                    @if($project->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                    @if($project->is_featured)
                                        <span class="badge badge-warning">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <form action="{{ route('admin.gallery.toggle', [$project, 'is_featured']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn {{ $project->is_featured ? 'warning' : '' }}" title="{{ $project->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.gallery.edit', $project) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.gallery.destroy', $project) }}" method="POST" data-confirm="Are you sure you want to delete this project?" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $projects->links('admin.partials.pagination') }}
            @endif
        </div>
    </div>
@endsection
