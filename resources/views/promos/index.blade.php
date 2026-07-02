@extends('layouts.app')
@section('title', 'Promo Settings')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Promo Settings</h5>
                    @can('create-promos')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPromoModal">
                        <i class="ti ti-plus"></i> Add New Promo
                    </button>
                    @endcan
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Discount</th>
                                <th>Min. Qty</th>
                                <th>Validity Period</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                            <tr>
                                <td class="fw-bold">{{ $promo->name }}</td>
                                <td>
                                    @if($promo->services->isEmpty())
                                        <span class="badge bg-secondary">All Services</span>
                                    @else
                                        @foreach($promo->services as $svc)
                                            <span class="badge bg-info mb-1">{{ $svc->service_name }}</span><br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($promo->discount_type == 'percent')
                                        <span class="badge bg-primary">{{ floatval($promo->discount_value) }}%</span>
                                    @else
                                        <span class="badge bg-primary">Rp {{ number_format($promo->discount_value, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td>{{ floatval($promo->min_qty) }} Kg</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                                </td>
                                <td>
                                    @if($promo->is_active && $promo->end_date >= today()->format('Y-m-d'))
                                        <span class="badge bg-success">Active</span>
                                    @elseif(!$promo->is_active)
                                        <span class="badge bg-danger">Inactive</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Expired</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('update-promos')
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPromoModal{{ $promo->id }}">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    @endcan
                                    @can('delete-promos')
                                    <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this promo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editPromoModal{{ $promo->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Promo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('promos.update', $promo->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Promo Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $promo->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold d-block mb-2">Applicable Services <small class="text-muted fw-normal">(Select multiple or leave empty for All Services)</small></label>
                                                    <div class="card p-3 bg-light border-0 mb-0" style="max-height: 200px; overflow-y: auto;">
                                                        <div class="row">
                                                            @php
                                                                $selectedServices = $promo->services->pluck('id')->toArray();
                                                            @endphp
                                                            @foreach($services as $service)
                                                            <div class="col-12 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->id }}" id="service_edit_{{ $promo->id }}_{{ $service->id }}" {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                                                                    <label class="form-check-label text-dark fw-normal mb-0" for="service_edit_{{ $promo->id }}_{{ $service->id }}" style="cursor: pointer;">
                                                                        <strong>{{ $service->service_name }}</strong> 
                                                                        <span class="text-muted ms-1">(Rp {{ number_format($service->price, 0, ',', '.') }}/kg)</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Discount Type</label>
                                                        <select name="discount_type" class="form-select" required>
                                                            <option value="percent" {{ $promo->discount_type == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                                                            <option value="nominal" {{ $promo->discount_type == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Discount Value</label>
                                                        <input type="text" name="discount_value" class="form-control nominal-input" value="{{ floatval($promo->discount_value) }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Min. Qty (Kg)</label>
                                                    <input type="number" step="0.1" name="min_qty" class="form-control" value="{{ floatval($promo->min_qty) }}" required>
                                                    <small class="text-muted">Set to 0 if there's no minimum qty.</small>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Start Date</label>
                                                        <input type="date" name="start_date" class="form-control flatpickr-date-edit bg-white" value="{{ $promo->start_date }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">End Date</label>
                                                        <input type="date" name="end_date" class="form-control flatpickr-date-edit bg-white" value="{{ $promo->end_date }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" id="isActive{{ $promo->id }}" name="is_active" value="1" {{ $promo->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="isActive{{ $promo->id }}">Promo is Active</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No promos found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('promos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Promo Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Promo Akhir Tahun" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block mb-2">Applicable Services <small class="text-muted fw-normal">(Select multiple or leave empty for All Services)</small></label>
                        <div class="card p-3 bg-light border-0 mb-0" style="max-height: 200px; overflow-y: auto;">
                            <div class="row">
                                @foreach($services as $service)
                                <div class="col-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->id }}" id="service_create_{{ $service->id }}">
                                        <label class="form-check-label text-dark fw-normal mb-0" for="service_create_{{ $service->id }}" style="cursor: pointer;">
                                            <strong>{{ $service->service_name }}</strong> 
                                            <span class="text-muted ms-1">(Rp {{ number_format($service->price, 0, ',', '.') }}/kg)</span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select" required>
                                <option value="percent">Percent (%)</option>
                                <option value="nominal">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value</label>
                            <input type="text" name="discount_value" class="form-control nominal-input" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Min. Qty (Kg)</label>
                        <input type="number" step="0.1" name="min_qty" class="form-control" value="0" required>
                        <small class="text-muted">Set to 0 if there's no minimum qty.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control flatpickr-date bg-white" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control flatpickr-date bg-white" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1" checked>
                        <label class="form-check-label" for="isActive">Promo is Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for Create Modal
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Initialize Flatpickr for Edit Modals when they open
        const editModals = document.querySelectorAll('[id^="editPromoModal"]');
        editModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                // Init Flatpickr
                flatpickr(this.querySelectorAll('.flatpickr-date-edit'), {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            });
        });
    });
</script>
@endpush
