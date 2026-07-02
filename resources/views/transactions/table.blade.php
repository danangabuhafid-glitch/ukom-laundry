<div class="table-responsive">
    <table class="table text-nowrap mb-0 align-middle">
        <thead class="text-dark fs-4">
            <tr>
                <th class="border-bottom-0">Order Code</th>
                <th class="border-bottom-0">Date</th>
                <th class="border-bottom-0">Customer</th>
                <th class="border-bottom-0">Total</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="border-bottom-0">{{ $order->order_code }}</td>
                <td class="border-bottom-0">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td>
                <td class="border-bottom-0">{{ $order->customer->customer_name ?? '-' }}</td>
                <td class="border-bottom-0">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="border-bottom-0">
                    @if($order->order_status == 0)
                        <span class="badge bg-warning rounded-3 fw-semibold">Baru</span>
                    @else
                        <span class="badge bg-success rounded-3 fw-semibold">Sudah Diambil</span>
                    @endif
                </td>
                <td class="border-bottom-0">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}" title="View Detail">
                        <i class="ti ti-eye"></i> Detail
                    </button>
                    
                    @if($order->order_status == 0)
                    <form action="{{ route('pickups.store', $order->id) }}" method="POST" class="d-inline form-confirm-pickup">
                        @csrf
                        <input type="hidden" name="notes" class="pickup-notes">
                        <button type="submit" class="btn btn-sm btn-success" data-code="{{ $order->order_code }}" title="Confirm Pickup">
                            <i class="ti ti-check"></i> Pickup
                        </button>
                    </form>
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6" class="text-center">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modals -->
@foreach($orders as $order)
<div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details: {{ $order->order_code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Customer:</strong> {{ $order->customer->customer_name ?? 'Walk-in' }}<br>
                        <strong>Phone:</strong> {{ $order->customer->phone ?? '-' }}<br>
                        <strong>Date:</strong> {{ $order->order_date }}<br>
                        <strong>Status:</strong> 
                        @if($order->order_status == 0)
                            <span class="badge bg-warning">Baru</span>
                        @else
                            <span class="badge bg-success">Sudah Diambil</span>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <strong>Payment Method:</strong> {{ $order->payment_method ?? 'Cash' }}<br>
                    </div>
                </div>
                
                <h6 class="fw-semibold mt-4 mb-2">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->transOrderDetails as $detail)
                            <tr>
                                <td>{{ $detail->typeOfService->service_name ?? 'Unknown Service' }}</td>
                                <td>{{ floatval($detail->qty) }} Kg</td>
                                <td>Rp {{ number_format($detail->typeOfService->price ?? 0, 0, ',', '.') }}</td>
                                <td class="text-danger">{{ $detail->discount > 0 ? '-Rp ' . number_format($detail->discount, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if($order->tax_amount > 0)
                            <tr>
                                <th colspan="4" class="text-end">Subtotal</th>
                                <th class="text-end">Rp {{ number_format($order->total - $order->tax_amount, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Tax ({{ $order->tax_rate }}%)</th>
                                <th class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="4" class="text-end">Grand Total</th>
                                <th class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Paid Amount</th>
                                <th class="text-end">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Change</th>
                                <th class="text-end">Rp {{ number_format($order->order_change, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="btn btn-secondary">
                    <i class="ti ti-printer"></i> Print Receipt
                </a>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="mt-4">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
