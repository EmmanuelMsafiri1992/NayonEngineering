@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="toolbar">
        <div style="flex: 1;"></div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($categories->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-folder"></i>
                    <p>No categories found</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="margin-top: 15px;">Add Your First Category</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Subcategories</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->sort_order }}</td>
                                <td><i class="fas {{ $category->icon ?? 'fa-folder' }}"></i></td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if(!empty($category->subcategories))
                                        {{ count($category->subcategories) }} subcategories
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Are you sure you want to delete this category?" style="display: inline;">
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
            @endif
        </div>
    </div>
@endsection
