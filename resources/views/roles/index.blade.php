@extends('layouts.app')
@section('title', 'Role Management')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title fw-semibold mb-0">Role Management</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="ti ti-plus"></i> Add New Role
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">ID</th>
                                <th class="border-bottom-0">Role Name</th>
                                <th class="border-bottom-0">Permissions Count</th>
                                <th class="border-bottom-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $role->id }}</h6></td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-primary rounded-3 fw-semibold">{{ $role->name }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    {{ $role->permissions->count() }} permissions
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal-{{ $role->id }}">
                                        <i class="ti ti-settings"></i> Edit Permissions
                                    </button>
                                    @if(!in_array($role->name, ['Administrator', 'Operator', 'Pimpinan']))
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Create Role Modal -->
                <div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('roles.store') }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Role Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Kasir">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Role</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modals rendered outside the table to prevent HTML structure issues -->
                @foreach($roles as $role)
                <!-- Edit Role Modal -->
                <div class="modal fade" id="editRoleModal-{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel-{{ $role->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleModalLabel-{{ $role->id }}">Edit Permissions: <span class="text-primary">{{ $role->name }}</span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    @php $rolePermissions = $role->permissions->pluck('name')->toArray(); @endphp
                                    <div class="table-responsive border rounded">
                                        <table class="table mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Module</th>
                                                    <th class="text-center">Read</th>
                                                    <th class="text-center">Create</th>
                                                    <th class="text-center">Update</th>
                                                    <th class="text-center">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($permissions as $module => $perms)
                                                <tr>
                                                    <td class="fw-semibold text-capitalize">{{ $module }}</td>
                                                    
                                                    <td class="text-center">
                                                        @if($perms->contains('name', "read-{$module}"))
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="read-{{ $module }}" {{ in_array("read-{$module}", $rolePermissions) ? 'checked' : '' }}>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        @if($perms->contains('name', "create-{$module}"))
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create-{{ $module }}" {{ in_array("create-{$module}", $rolePermissions) ? 'checked' : '' }}>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        @if($perms->contains('name', "update-{$module}"))
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="update-{{ $module }}" {{ in_array("update-{$module}", $rolePermissions) ? 'checked' : '' }}>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        @if($perms->contains('name', "delete-{$module}"))
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete-{{ $module }}" {{ in_array("delete-{$module}", $rolePermissions) ? 'checked' : '' }}>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
