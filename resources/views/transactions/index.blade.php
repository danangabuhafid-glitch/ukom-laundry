@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Transactions</h5>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">New Transaction</a>
                </div>

                <!-- Filter Tabs -->
                <div class="mb-4 btn-group" role="group" aria-label="Transaction Filters">
                    <button type="button" class="btn btn-outline-primary active filter-btn" data-status="all">Semua</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-status="0">Baru</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-status="1">Sudah Diambil</button>
                </div>

                <div id="transaction-table-container">
                    @include('transactions.table', ['orders' => $orders])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const tableContainer = document.getElementById('transaction-table-container');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const status = this.getAttribute('data-status');
                const url = new URL(window.location.href);
                if (status !== 'all') {
                    url.searchParams.set('status', status);
                } else {
                    url.searchParams.delete('status');
                }
                
                // Fetch new table content
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                });
            });
        });
    });
</script>
@endpush
