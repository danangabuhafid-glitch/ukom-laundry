@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Users</h5>
                    <a href="{{ route('master.users.create') }}" class="btn btn-primary">Add User</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Id</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Email</th>
                                <th class="border-bottom-0">Role</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="border-bottom-0">{{ $user->id }}</td>
                                <td class="border-bottom-0">{{ $user->name }}</td>
                                <td class="border-bottom-0">{{ $user->email }}</td>
                                <td class="border-bottom-0">{{ $user->level->level_name ?? '-' }}</td>
                                <td class="border-bottom-0">
                                    <a href="{{ route('master.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('master.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
