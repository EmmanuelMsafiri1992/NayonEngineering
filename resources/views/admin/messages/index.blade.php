@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search messages..." value="{{ request('search') }}">
            </form>
        </div>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.messages.index') }}?status='+this.value+'&search={{ request('search') }}'">
            <option value="">All Messages</option>
            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread ({{ $unreadCount }})</option>
            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
        </select>
        @if($unreadCount > 0)
        <form action="{{ route('admin.messages.markAllRead') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-outline">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
        </form>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @if($messages->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-envelope"></i>
                    <p>No messages found</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                            <tr style="{{ !$message->is_read ? 'background: rgba(0,121,193,0.05);' : '' }}">
                                <td>
                                    @if(!$message->is_read)
                                        <span class="badge badge-primary">New</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$message->is_read)
                                        <strong>{{ $message->name }}</strong>
                                    @else
                                        {{ $message->name }}
                                    @endif
                                    <br>
                                    <small style="color: var(--text-muted);">{{ $message->email }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}">
                                        @if(!$message->is_read)
                                            <strong>{{ $message->subject }}</strong>
                                        @else
                                            {{ $message->subject }}
                                        @endif
                                    </a>
                                </td>
                                <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="action-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Are you sure you want to delete this message?" style="display: inline;">
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

                {{ $messages->links('admin.partials.pagination') }}
            @endif
        </div>
    </div>
@endsection
