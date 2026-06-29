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
                    <a href="{{ route('transactions.show', $order->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
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
<div class="mt-4">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
