@extends('admin.master')

@section('title', 'Add Product')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Add New Product</h1>
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

        <!-- Product Create Form -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create Product</h3>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Enter product name">
                        @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}" placeholder="Enter price">
                        @error('price') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Stock -->
                    <div class="form-group">
                        <label for="stock">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock') }}" placeholder="Enter stock quantity">
                        @error('stock') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Picture Upload and Preview -->
                    <div class="form-group">
                        <label for="picture">Product Picture</label>
                        <div class="custom-file">
                            <input type="file" name="picture" id="picture" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="picture">Choose image</label>
                        </div>
                        @error('picture') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Selected Picture Preview</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <img id="preview-picture" src="#" alt="Preview"
                                            class="img-fluid img-thumbnail"
                                            style="max-height: 200px; display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                            class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Show selected filename
    document.getElementById('picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const label = document.querySelector('label[for="picture"]');
        if (file) {
            label.innerText = file.name;

            // Preview logic
            const preview = document.getElementById('preview-picture');
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
