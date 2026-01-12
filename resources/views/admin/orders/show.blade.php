@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Order Details</h3>
                    <span class="badge badge-{{ $order->status_badge }}" style="font-size: 14px;">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Order Number</label>
                            <p style="margin: 5px 0 15px; font-weight: 600;">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Order Date</label>
                            <p style="margin: 5px 0 15px;">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <h4 style="margin: 20px 0 15px; padding-top: 20px; border-top: 1px solid var(--border);">Customer Information</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Name</label>
                            <p style="margin: 5px 0 15px;">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Email</label>
                            <p style="margin: 5px 0 15px;"><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>
                        </div>
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Phone</label>
                            <p style="margin: 5px 0 15px;">{{ $order->customer_phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label style="color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Address</label>
                            <p style="margin: 5px 0 15px;">{{ $order->customer_address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>Update Status</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Order Items</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item['name'] ?? 'N/A' }}</td>
                            <td>{{ $item['sku'] ?? 'N/A' }}</td>
                            <td>R {{ number_format($item['price'] ?? 0, 2) }}</td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                            <td><strong>R {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;">Subtotal:</td>
                            <td><strong>R {{ number_format($order->subtotal, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;">VAT (15%):</td>
                            <td>R {{ number_format($order->vat, 2) }}</td>
                        </tr>
                        <tr style="font-size: 18px;">
                            <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong>R {{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
@endsection
