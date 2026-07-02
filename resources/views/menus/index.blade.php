@extends('layouts.app')
@section('title', 'Menu Configuration')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title fw-semibold mb-0">Menu Configuration</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#menuModal">
                    <i class="ti ti-plus"></i> Add New Menu
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Icon</th>
                                <th>Route</th>
                                <th class="text-center">Order</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $parent)
                            <!-- Parent Menu -->
                            <tr class="table-primary">
                                <td class="fw-bold">
                                    <i class="ti {{ $parent->icon }} me-2"></i> {{ $parent->name }}
                                </td>
                                <td><code>{{ $parent->icon }}</code></td>
                                <td>{{ $parent->route_name ?? '-' }}</td>
                                <td class="text-center">{{ $parent->order }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $parent->id }}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <form action="{{ route('menus.destroy', $parent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this menu and all its submenus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Sub Menus -->
                            @foreach($parent->children as $child)
                            <tr>
                                <td class="ps-5">
                                    <i class="ti {{ $child->icon }} me-2"></i> {{ $child->name }}
                                </td>
                                <td><code>{{ $child->icon }}</code></td>
                                <td>{{ $child->route_name ?? '-' }}</td>
                                <td class="text-center">{{ $child->order }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $child->id }}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <form action="{{ route('menus.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Menu Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('menus.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parent Menu</label>
                    <select name="parent_id" class="form-select">
                        <option value="">-- No Parent (Main Menu) --</option>
                        @foreach($menus as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon Class (e.g., ti-circle)</label>
                    <input type="text" name="icon" class="form-control" value="ti-circle">
                </div>
                <div class="mb-3">
                    <label class="form-label">Route Name (e.g., master.customers.index)</label>
                    <input type="text" name="route_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Order</label>
                    <input type="number" name="order" class="form-control" value="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Menu</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modals -->
@foreach($menus as $parent)
    @php
        $allItems = collect([$parent])->merge($parent->children);
    @endphp
    @foreach($allItems as $item)
    <div class="modal fade" id="editMenuModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('menus.update', $item->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu: {{ $item->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Menu</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- No Parent (Main Menu) --</option>
                            @foreach($menus as $p)
                                @if($p->id != $item->id)
                                    <option value="{{ $p->id }}" {{ $item->parent_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="{{ $item->icon }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Route Name</label>
                        <input type="text" name="route_name" class="form-control" value="{{ $item->route_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ $item->order }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endforeach

@endsection
