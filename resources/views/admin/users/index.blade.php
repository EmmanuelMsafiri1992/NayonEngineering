@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}">
            </form>
        </div>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.users.index') }}?role='+this.value+'&search={{ request('search') }}'">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Users</option>
        </select>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>No users found</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge badge-primary">Admin</span>
                                    @else
                                        <span class="badge badge-secondary">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-confirm="Are you sure you want to delete this user?" style="display: inline;">
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
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
