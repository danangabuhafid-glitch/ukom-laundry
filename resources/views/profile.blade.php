@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-12 col-md-6 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/profile/user-1.jpg') }}" alt="user" class="rounded-circle mb-3" width="100">
                <h5 class="fw-semibold">{{ auth()->user()->name }}</h5>
                <p class="text-muted">{{ auth()->user()->email }}</p>
                <span class="badge bg-primary rounded-3">{{ auth()->user()->level->level_name }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
