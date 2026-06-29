@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Transaction Report Filter</h5>
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Select start date">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="Select end date">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                        <a href="{{ route('reports.print', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" target="_blank" class="btn btn-success d-print-none ms-2"><i class="ti ti-printer"></i> Cetak Data Laporan</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Transactions Report</h5>
                    <h5 class="text-primary fw-bold mb-0">Total Revenue: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Order Code</th>
                                <th class="border-bottom-0">Date</th>
                                <th class="border-bottom-0">Customer</th>
                                <th class="border-bottom-0">Services</th>
                                <th class="border-bottom-0 text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="border-bottom-0">{{ $order->order_code }}</td>
                                <td class="border-bottom-0">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td class="border-bottom-0">{{ $order->customer->customer_name ?? '-' }}</td>
                                <td class="border-bottom-0">
                                    <ul class="mb-0 ps-3">
                                    @foreach($order->transOrderDetails as $detail)
                                        <li>{{ $detail->typeOfService->service_name }} ({{ $detail->qty }} kg)</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td class="border-bottom-0 text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No transactions found for the selected period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Since it's a report, we get all data -->
            </div>
        </div>
    </div>
</div>
<style>
    @media print {
        .left-sidebar, .topbar, .d-print-none, form {
            display: none !important;
        }
        .page-wrapper {
            margin-left: 0 !important;
        }
        body {
            background-color: white !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });
</script>
@endpush
