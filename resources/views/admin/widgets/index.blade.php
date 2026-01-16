@extends('admin.layouts.app')

@section('title', 'Widgets')

@section('content')
    <div class="toolbar">
        <select name="location" class="form-control filter-select" onchange="location = this.value">
            <option value="{{ route('admin.widgets.index') }}">All Locations</option>
            @foreach($locations as $key => $name)
                <option value="{{ route('admin.widgets.index', ['location' => $key]) }}" {{ request('location') == $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="type" class="form-control filter-select" onchange="location = this.value">
            <option value="{{ route('admin.widgets.index', array_merge(request()->except('type'), [])) }}">All Types</option>
            @foreach($types as $key => $type)
                <option value="{{ route('admin.widgets.index', array_merge(request()->except('type'), ['type' => $key])) }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type['name'] }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.widgets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Widget
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($widgets->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-puzzle-piece"></i>
                    <p>No widgets created yet</p>
                    <a href="{{ route('admin.widgets.create') }}" class="btn btn-primary" style="margin-top: 15px;">Create Your First Widget</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="widgetsList">
                            @foreach($widgets as $widget)
                            <tr data-id="{{ $widget->id }}">
                                <td>
                                    <i class="fas fa-grip-vertical" style="color: #ccc; cursor: move;"></i>
                                    {{ $widget->sort_order }}
                                </td>
                                <td>
                                    <strong>{{ $widget->name }}</strong>
                                    @if($widget->title)
                                        <div style="font-size: 12px; color: #666;">{{ $widget->title }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas {{ $types[$widget->type]['icon'] ?? 'fa-puzzle-piece' }}" style="margin-right: 5px;"></i>
                                        {{ $types[$widget->type]['name'] ?? $widget->type }}
                                    </span>
                                </td>
                                <td>{{ $locations[$widget->location] ?? $widget->location }}</td>
                                <td>
                                    @if($widget->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.widgets.edit', $widget) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.widgets.duplicate', $widget) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn" style="background: rgba(111,66,193,0.1); color: #6f42c1;" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.widgets.toggle', $widget) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn" style="background: rgba(255,193,7,0.1); color: #856404;" title="{{ $widget->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $widget->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST" data-confirm="Are you sure you want to delete this widget?" style="display: inline;">
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
            <h3>Widget Types</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                @foreach($types as $key => $type)
                <div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas {{ $type['icon'] }}" style="font-size: 20px; color: #0079c1;"></i>
                        <strong>{{ $type['name'] }}</strong>
                    </div>
                    <p style="font-size: 13px; color: #666; margin: 0;">{{ $type['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const widgetsList = document.getElementById('widgetsList');
    if (widgetsList) {
        new Sortable(widgetsList, {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function(evt) {
                const order = Array.from(widgetsList.querySelectorAll('tr')).map(row => row.dataset.id);
                fetch('{{ route("admin.widgets.reorder") }}', {
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
</script>
@endpush
