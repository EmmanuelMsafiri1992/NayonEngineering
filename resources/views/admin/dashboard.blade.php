@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-box"></i></div>
            <div class="stat-value">{{ number_format($stats['products']) }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-folder"></i></div>
            <div class="stat-value">{{ number_format($stats['categories']) }}</div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-value">{{ number_format($stats['orders']) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ number_format($stats['pending_orders']) }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ number_format($stats['users']) }}</div>
            <div class="stat-label">Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
            <div class="stat-value">{{ number_format($stats['unread_messages']) }}</div>
            <div class="stat-label">Unread Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value">{{ number_format($stats['low_stock']) }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-value">{{ number_format($stats['out_of_stock']) }}</div>
            <div class="stat-label">Out of Stock</div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Recent Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">View All</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <p>No orders yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>R {{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Recent Messages</h3>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-outline">View All</a>
                </div>
                <div class="card-body">
                    @if($recentMessages->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <p>No messages yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMessages as $message)
                                    <tr>
                                        <td>
                                            @if(!$message->is_read)
                                                <strong>{{ $message->name }}</strong>
                                            @else
                                                {{ $message->name }}
                                            @endif
                                        </td>
                                        <td><a href="{{ route('admin.messages.show', $message) }}">{{ Str::limit($message->subject, 30) }}</a></td>
                                        <td>{{ $message->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="card">
        <div class="card-header">
            <h3>Low Stock Products</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline">View All Products</a>
        </div>
        <div class="card-body">
            @if($topProducts->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-box"></i>
                    <p>All products are well stocked</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ $product->sku }}</td>
                                <td>{{ Str::limit($product->name, 40) }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($product->stock == 0)
                                        <span class="badge badge-danger">Out of Stock</span>
                                    @elseif($product->stock <= 5)
                                        <span class="badge badge-warning">{{ $product->stock }} left</span>
                                    @else
                                        <span class="badge badge-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">Edit</a>
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
