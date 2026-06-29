@extends('layouts.app')
@section('title', 'Services')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Laundry Services</h5>
                    <a href="{{ route('master.services.create') }}" class="btn btn-primary">Add Service</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Id</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Price/Kg</th>
                                <th class="border-bottom-0">Description</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td class="border-bottom-0">{{ $service->id }}</td>
                                <td class="border-bottom-0">{{ $service->service_name }}</td>
                                <td class="border-bottom-0">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td class="border-bottom-0">{{ $service->description }}</td>
                                <td class="border-bottom-0">
                                    <a href="{{ route('master.services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('master.services.destroy', $service->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No services found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $services->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
