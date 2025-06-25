@extends('admin.master')

@section('title', 'Edit User')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Edit User</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Edit User Form -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Update User</h3>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" placeholder="Enter full name">
                        @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" placeholder="Enter email">
                        @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Is Admin Checkbox (with fallback) -->
                    <div class="form-group">
                        <label for="is_admin">Role</label><br>
                        <input type="hidden" name="is_admin" value="0">
                        <input type="checkbox" name="is_admin" id="is_admin" value="1"
                            {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                        <label for="is_admin">Admin</label>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">New Password <small>(leave blank to keep current)</small></label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter new password">
                        @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirm new password">
                    </div>
                </div>

                <!-- Submit -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection