@extends('layouts.app')
@section('title', 'Web Settings')
@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Web Settings</h5>
                
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <label class="form-label d-block fw-semibold">Current Logo</label>
                        @if($setting->logo_path)
                            <img src="{{ Storage::url($setting->logo_path) }}" alt="Web Logo" class="img-fluid rounded mb-3" style="max-height: 100px; object-fit: contain;">
                        @else
                            <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" alt="Default Logo" class="img-fluid rounded mb-3" style="max-height: 100px; object-fit: contain;">
                        @endif
                        <input type="file" class="form-control" name="logo" accept="image/*">
                        <small class="text-muted">Upload a new logo to change it globally. (PNG/JPG, Max: 2MB)</small>
                    </div>

                    <div class="mb-3">
                        <label for="app_name" class="form-label fw-semibold">Application Name</label>
                        <input type="text" class="form-control" id="app_name" name="app_name" value="{{ old('app_name', $setting->app_name) }}" required>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
