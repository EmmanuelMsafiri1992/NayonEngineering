@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="" method="GET" style="display: contents;">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>
        </div>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.products.index') }}?category='+this.value+'&search={{ request('search') }}'">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="form-control filter-select" onchange="window.location.href='{{ route('admin.products.index') }}?status='+this.value+'&search={{ request('search') }}&category={{ request('category') }}'">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    <div class="card" style="margin-bottom: 20px; background: #e8f4fc;">
        <div class="card-body" style="padding: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <strong><i class="fas fa-exchange-alt"></i> Exchange Rate Info</strong>
                    <span style="margin-left: 15px;">
                        Base Rate: <strong>1 ZAR = {{ number_format($exchangeRate, 4) }} MZN</strong>
                    </span>
                    <span style="margin-left: 15px;">
                        Markup: <strong>{{ number_format($markupPercentage, 2) }}%</strong>
                    </span>
                    <span style="margin-left: 15px;">
                        Effective Rate: <strong>1 ZAR = {{ number_format($effectiveRate, 4) }} MZN</strong>
                    </span>
                </div>
                <a href="{{ route('admin.settings.payment') }}" class="btn btn-sm btn-outline">
                    <i class="fas fa-cog"></i> Adjust Markup
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($products->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-box"></i>
                    <p>No products found</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="margin-top: 15px;">Add Your First Product</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price (ZAR)</th>
                                <th>Price (MZN)</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ Str::limit($product->name, 40) }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <span style="text-decoration: line-through; color: var(--text-muted); font-size: 12px;">R {{ number_format($product->list_price, 2) }}</span><br>
                                    <strong>R {{ number_format($product->net_price, 2) }}</strong>
                                </td>
                                <td>
                                    <span style="text-decoration: line-through; color: var(--text-muted); font-size: 12px;">MT {{ number_format($product->list_price * $effectiveRate, 2) }}</span><br>
                                    <strong>MT {{ number_format($product->net_price * $effectiveRate, 2) }}</strong>
                                </td>
                                <td>
                                    @if($product->stock == 0)
                                        <span class="badge badge-danger">Out</span>
                                    @elseif($product->stock <= 5)
                                        <span class="badge badge-warning">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-info">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" data-confirm="Are you sure you want to delete this product?" style="display: inline;">
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

                {{ $products->links('admin.partials.pagination') }}
            @endif
        </div>
    </div>
@endsection
