@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-md-6 col-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4 text-center">Edit Profile</h5>
                
                <div class="text-center mb-4">
                    <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/profile/user-1.jpg') }}" alt="User Profile" class="rounded-circle img-fluid border border-2 border-primary" style="width: 100px; height: 100px; object-fit: cover;">
                    <p class="text-muted mt-2 mb-0 fs-2">Profile picture cannot be changed</p>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control text-muted" id="email" value="{{ $user->email }}" readonly>
                        <small class="form-text text-muted">Email address cannot be changed.</small>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3">Change Password</h6>
                    <p class="text-muted fs-2 mb-3">Leave password fields blank if you don't want to change it.</p>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
