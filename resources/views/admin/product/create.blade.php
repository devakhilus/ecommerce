@extends('admin.master')

@section('title', 'Edit Product')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Edit Product</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Edit Product Form -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Update Product</h3>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name) }}" placeholder="Enter product name">
                        @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $product->price) }}" placeholder="Enter price">
                        @error('price')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="form-group">
                        <label for="stock">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock', $product->stock) }}" placeholder="Enter stock quantity">
                        @error('stock')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Picture Upload -->
                    <div class="form-group">
                        <label for="picture">Product Picture</label>
                        <input type="file" name="picture" id="picture" class="form-control">
                        @error('picture')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if($product->picture)
                        <div class="mt-2">
                            <p>Current Picture:</p>
                            <img src="{{ asset('storage/products/' . $product->picture) }}" alt="Product Image" height="100">
                        </div>
                        @endif
                    </div>

                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                            class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
