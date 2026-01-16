@extends('admin.layouts.app')

@section('title', 'Menus')

@section('content')
    <div class="toolbar">
        <div style="flex: 1;"></div>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Menu
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($menus->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-bars"></i>
                    <p>No menus created yet</p>
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary" style="margin-top: 15px;">Create Your First Menu</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td><strong>{{ $menu->name }}</strong></td>
                                <td>
                                    @if($menu->location)
                                        <span class="badge badge-info">{{ $locations[$menu->location] ?? $menu->location }}</span>
                                    @else
                                        <span style="color: #999;">Not assigned</span>
                                    @endif
                                </td>
                                <td>{{ $menu->all_items_count }} items</td>
                                <td>
                                    @if($menu->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" data-confirm="Are you sure you want to delete this menu?" style="display: inline;">
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

    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3>Menu Locations</h3>
        </div>
        <div class="card-body">
            <p style="color: #666; margin-bottom: 15px;">Assign menus to display in specific locations on your website:</p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Assigned Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $locationKey => $locationName)
                        <tr>
                            <td>{{ $locationName }}</td>
                            <td>
                                @php $assignedMenu = $menus->where('location', $locationKey)->first(); @endphp
                                @if($assignedMenu)
                                    <a href="{{ route('admin.menus.edit', $assignedMenu) }}">{{ $assignedMenu->name }}</a>
                                @else
                                    <span style="color: #999;">No menu assigned</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
