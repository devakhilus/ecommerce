@extends('admin.master')

@section('title', 'Edit Category')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Edit Category</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Edit Category Form -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Update Category</h3>
            </div>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label for="name">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" value="{{ old('name', $category->name) }}" placeholder="Enter category name">
                        @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection