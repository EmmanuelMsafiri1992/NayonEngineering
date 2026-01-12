@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search orders..." value="{{ request('search') }}">
            </form>
        </div>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.orders.index') }}?status='+this.value+'&search={{ request('search') }}'">
            <option value="">All Statuses</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="card">
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No orders found</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                <td>
                                    {{ $order->customer_name }}<br>
                                    <small style="color: var(--text-muted);">{{ $order->customer_email }}</small>
                                </td>
                                <td>{{ count($order->items) }} items</td>
                                <td><strong>R {{ number_format($order->total, 2) }}</strong></td>
                                <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
