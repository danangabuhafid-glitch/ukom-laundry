                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Order Code</th>
                                <th class="border-bottom-0">Customer</th>
                                <th class="border-bottom-0">Status</th>
                                <th class="border-bottom-0">Pickup Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="border-bottom-0">
                                    <a href="{{ route('transactions.show', $order->id) }}">{{ $order->order_code }}</a>
                                </td>
                                <td class="border-bottom-0">{{ $order->customer->customer_name ?? '-' }}</td>
                                <td class="border-bottom-0">
                                    @if($order->order_status == 0)
                                        <span class="badge bg-warning rounded-3 fw-semibold">Baru</span>
                                    @else
                                        <span class="badge bg-success rounded-3 fw-semibold">Sudah Diambil</span>
                                        <br><small>{{ \Carbon\Carbon::parse($order->transLaundryPickup->pickup_date)->format('d M Y H:i') }}</small>
                                        @if($order->transLaundryPickup->notes)
                                            <br><small class="text-muted fst-italic">{{ $order->transLaundryPickup->notes }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    @if($order->order_status == 0)
                                    <form action="{{ route('pickups.store', $order->id) }}" method="POST" class="d-inline form-confirm-pickup">
                                        @csrf
                                        <input type="hidden" name="notes" class="pickup-notes">
                                        <button type="submit" class="btn btn-sm btn-primary" data-code="{{ $order->order_code }}">Confirm Pickup</button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-secondary" disabled>Picked Up</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
