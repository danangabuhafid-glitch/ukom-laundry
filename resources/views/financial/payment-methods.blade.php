@extends('layouts.app')
@section('title', 'Payment Methods')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Payment Methods</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaymentMethodModal">Add Method</button>
                </div>
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
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Code</th>
                                <th class="border-bottom-0">Active</th>
                                <th class="border-bottom-0">System</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods as $method)
                            <tr>
                                <td class="border-bottom-0">{{ $method->id }}</td>
                                <td class="border-bottom-0">{{ $method->name }}</td>
                                <td class="border-bottom-0"><code>{{ $method->code }}</code></td>
                                <td class="border-bottom-0">{!! $method->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                                <td class="border-bottom-0">{!! $method->is_system ? '<span class="badge bg-info">System</span>' : '<span class="badge bg-light text-dark">Custom</span>' !!}</td>
                                <td class="border-bottom-0">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentMethodModal{{ $method->id }}">Edit</button>
                                    @if(!$method->is_system)
                                    <form action="{{ route('financial.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this method?')">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="editPaymentMethodModal{{ $method->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Payment Method</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('financial.payment-methods.update', $method->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $method->name }}" {{ $method->is_system ? 'readonly' : '' }} required>
                                                    @if($method->is_system)<small class="text-muted">System methods cannot be renamed.</small>@endif
                                                </div>
                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" id="isActive{{ $method->id }}" name="is_active" value="1" {{ $method->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="isActive{{ $method->id }}">Active</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No payment methods found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createPaymentMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('financial.payment-methods.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Bank Transfer" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection