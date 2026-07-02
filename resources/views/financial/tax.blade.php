@extends('layouts.app')
@section('title', 'Tax Settings')
@section('content')
<div class="row">
    <div class="col-12 col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tax Settings</h5>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form action="{{ route('financial.tax.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tax Rate</label>
                        <div class="input-group">
                            <input type="number" name="tax_rate" class="form-control" value="{{ old('tax_rate', $taxRate->value ?? 0) }}" min="0" max="100" step="0.01" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Set 0 for no tax.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Tax Rate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection