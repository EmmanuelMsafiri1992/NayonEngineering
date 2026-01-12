@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Product: {{ $product->name }}</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">SKU *</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Subcategory</label>
                            <input type="text" name="subcategory" class="form-control" value="{{ old('subcategory', $product->subcategory) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Brand *</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Warranty</label>
                            <input type="text" name="warranty" class="form-control" value="{{ old('warranty', $product->warranty) }}" placeholder="e.g., 1 Year">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">List Price (R) *</label>
                            <input type="number" name="list_price" class="form-control" value="{{ old('list_price', $product->list_price) }}" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Net Price (R) *</label>
                            <input type="number" name="net_price" class="form-control" value="{{ old('net_price', $product->net_price) }}" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" name="discount" class="form-control" value="{{ old('discount', $product->discount) }}" min="0" max="100">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="image" class="form-control" value="{{ old('image', $product->image) }}" placeholder="https://example.com/image.jpg">
                    @if($product->image)
                        <div style="margin-top: 10px;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 150px; border-radius: 4px;">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label for="is_active">Active (visible on website)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured">Featured Product</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
