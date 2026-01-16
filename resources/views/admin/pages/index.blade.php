@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="{{ route('admin.pages.index') }}" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search pages..." value="{{ request('search') }}" class="form-control">
            </form>
        </div>
        <select name="status" class="form-control filter-select" onchange="location = this.value">
            <option value="{{ route('admin.pages.index', array_merge(request()->except('status'), [])) }}">All Status</option>
            <option value="{{ route('admin.pages.index', array_merge(request()->except('status'), ['status' => 'published'])) }}" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
            <option value="{{ route('admin.pages.index', array_merge(request()->except('status'), ['status' => 'draft'])) }}" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
        <select name="template" class="form-control filter-select" onchange="location = this.value">
            <option value="{{ route('admin.pages.index', array_merge(request()->except('template'), [])) }}">All Templates</option>
            @foreach($templates as $key => $name)
                <option value="{{ route('admin.pages.index', array_merge(request()->except('template'), ['template' => $key])) }}" {{ request('template') == $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Page
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($pages->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p>No pages found</p>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary" style="margin-top: 15px;">Create Your First Page</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Template</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Menu</th>
                                <th>Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr>
                                <td>
                                    <strong>{{ $page->title }}</strong>
                                    @if($page->is_homepage)
                                        <span class="badge badge-primary" style="margin-left: 5px;">Homepage</span>
                                    @endif
                                </td>
                                <td>
                                    <code>/page/{{ $page->slug }}</code>
                                </td>
                                <td>{{ $templates[$page->template] ?? $page->template }}</td>
                                <td>{{ $page->sections_count }} sections</td>
                                <td>
                                    @if($page->is_published)
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->show_in_header)
                                        <span class="badge badge-info" title="Shows in header">H</span>
                                    @endif
                                    @if($page->show_in_footer)
                                        <span class="badge badge-secondary" title="Shows in footer">F</span>
                                    @endif
                                </td>
                                <td>{{ $page->updated_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.pages.preview', $page) }}" class="action-btn view" title="Preview" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.duplicate', $page) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn" style="background: rgba(111,66,193,0.1); color: #6f42c1;" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pages.togglePublish', $page) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn" style="background: rgba(255,193,7,0.1); color: #856404;" title="{{ $page->is_published ? 'Unpublish' : 'Publish' }}">
                                                <i class="fas fa-{{ $page->is_published ? 'eye-slash' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if(!$page->is_homepage)
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" data-confirm="Are you sure you want to delete this page?" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
