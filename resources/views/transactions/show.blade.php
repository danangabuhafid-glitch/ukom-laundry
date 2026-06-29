@extends('layouts.app')
@section('title', 'Transaction Detail')
@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Transaction Detail: {{ $order->order_code }}</h5>
                <div class="mb-4">
                    <strong>Customer:</strong> {{ $order->customer->customer_name }} <br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }} <br>
                    <strong>Estimasi Selesai:</strong> <span class="text-primary">{{ \Carbon\Carbon::parse($order->order_date)->addDays(2)->format('d M Y') }}</span> <br>
                    <strong>Status:</strong> 
                    @if($order->order_status == 0)
                        <span class="badge bg-warning rounded-3 fw-semibold">Baru</span>
                    @else
                        <span class="badge bg-success rounded-3 fw-semibold">Sudah Diambil</span>
                    @endif
                </div>

                <div class="mt-2 mb-5 pt-3 border-top">
                    <h6 class="fw-semibold mb-4 text-center">Status Pelacakan</h6>
                    <div class="d-flex align-items-center justify-content-between position-relative px-4">
                        <div class="progress position-absolute top-50 start-50 translate-middle w-100" style="height: 4px; z-index: 0; width: 80% !important;">
                            <div class="progress-bar {{ $order->order_status == 1 ? 'bg-success' : 'bg-primary progress-bar-striped progress-bar-animated' }}" role="progressbar" style="width: {{ $order->order_status == 1 ? '100%' : '50%' }}"></div>
                        </div>
                        
                        <div class="position-relative text-center bg-white px-2" style="z-index: 1;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white {{ $order->order_status >= 0 ? 'bg-primary' : 'bg-secondary' }}" style="width: 45px; height: 45px; margin: 0 auto;">
                                <i class="ti ti-receipt fs-6"></i>
                            </div>
                            <small class="d-block mt-2 fw-bold text-primary">Pesanan Diterima</small>
                            <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M H:i') }}</small>
                        </div>
                        
                        <div class="position-relative text-center bg-white px-2" style="z-index: 1;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white {{ $order->order_status == 1 ? 'bg-success' : 'bg-primary' }}" style="width: 45px; height: 45px; margin: 0 auto;">
                                <i class="ti ti-wash-machine fs-6"></i>
                            </div>
                            <small class="d-block mt-2 fw-bold {{ $order->order_status == 1 ? 'text-success' : 'text-primary' }}">Proses Laundry</small>
                            <small class="text-muted" style="font-size: 11px;">Sedang Dikerjakan</small>
                        </div>
                        
                        <div class="position-relative text-center bg-white px-2" style="z-index: 1;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white {{ $order->order_status == 1 ? 'bg-success' : 'bg-secondary' }}" style="width: 45px; height: 45px; margin: 0 auto;">
                                <i class="ti ti-check fs-6"></i>
                            </div>
                            <small class="d-block mt-2 fw-bold {{ $order->order_status == 1 ? 'text-success' : 'text-muted' }}">Sudah Diambil</small>
                            @if($order->order_status == 1)
                                <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($order->transLaundryPickup->pickup_date)->format('d M H:i') }}</small>
                            @else
                                <small class="text-muted" style="font-size: 11px;">Menunggu</small>
                            @endif
                        </div>
                    </div>
                    @if($order->order_status == 1 && $order->transLaundryPickup && $order->transLaundryPickup->notes)
                        <div class="mt-3 text-center"><small class="text-muted fst-italic"><i class="ti ti-info-circle"></i> {{ $order->transLaundryPickup->notes }}</small></div>
                    @endif
                </div>

                <h6 class="fw-semibold">Details</h6>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Service</th>
                                <th class="border-bottom-0 text-end">Price</th>
                                <th class="border-bottom-0 text-end">Qty</th>
                                <th class="border-bottom-0 text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->transOrderDetails as $detail)
                            <tr>
                                <td class="border-bottom-0">{{ $detail->typeOfService->service_name }}</td>
                                <td class="border-bottom-0 text-end">Rp {{ number_format($detail->typeOfService->price, 0, ',', '.') }}</td>
                                <td class="border-bottom-0 text-end">{{ $detail->qty }}</td>
                                <td class="border-bottom-0 text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end border-top">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                            </tr>
                            @if($order->order_pay > 0)
                            <tr>
                                <th colspan="3" class="text-end">Bayar</th>
                                <th class="text-end border-top text-primary">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Kembali</th>
                                <th class="text-end border-top text-success">Rp {{ number_format($order->order_change, 0, ',', '.') }}</th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-4 text-end">
                    <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="btn btn-primary me-2"><i class="ti ti-printer"></i> Print Receipt</a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
