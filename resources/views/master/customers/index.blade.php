@extends('layouts.app')
@section('title', 'Customers')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Customers</h5>
                    <a href="{{ route('master.customers.create') }}" class="btn btn-primary">Add Customer</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Id</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Phone</th>
                                <th class="border-bottom-0">Address</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td class="border-bottom-0">{{ $customer->id }}</td>
                                <td class="border-bottom-0">{{ $customer->customer_name }}</td>
                                <td class="border-bottom-0">{{ $customer->phone }}</td>
                                <td class="border-bottom-0">{{ $customer->address }}</td>
                                <td class="border-bottom-0">
                                    <a href="{{ route('master.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('master.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this customer?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No customers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
