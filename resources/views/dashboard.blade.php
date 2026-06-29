@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary-subtle text-primary border-0 shadow-sm">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="ms-3">
                    <h4 class="fw-semibold mb-1 text-primary">Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="mb-1 fs-4">You are logged in as <strong>{{ auth()->user()->level->level_name }}</strong>.</p>
                    <p class="mb-0 fs-3 fw-medium text-muted" id="dashboard-clock"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Total Revenue</h6>
                        <h4 class="fw-semibold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-primary-subtle text-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-currency-dollar fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Transactions</h6>
                        <h4 class="fw-semibold mb-0">{{ $totalTransactions }}</h4>
                    </div>
                    <div class="bg-success-subtle text-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-shopping-cart fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Pending Pickups</h6>
                        <h4 class="fw-semibold mb-0">{{ $pendingPickups }}</h4>
                    </div>
                    <div class="bg-warning-subtle text-warning p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-truck fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Customers</h6>
                        <h4 class="fw-semibold mb-0">{{ $totalCustomers }}</h4>
                    </div>
                    <div class="bg-info-subtle text-info p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-users fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart -->
    <div class="col-lg-8 d-flex align-items-stretch">
        <div class="card w-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">Revenue Overview</h5>
                        <p class="card-subtitle mb-0">Last 7 Days</p>
                    </div>
                </div>
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card w-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="card-title fw-semibold">Recent Transactions</h5>
                </div>
                <ul class="timeline-widget mb-0 position-relative mb-n5">
                    @forelse($recentTransactions as $tx)
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end">{{ \Carbon\Carbon::parse($tx->created_at)->format('H:i') }}</div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1">
                            <span class="fw-semibold text-primary d-block">{{ $tx->order_code }}</span>
                            {{ $tx->customer->customer_name ?? '-' }} (Rp {{ number_format($tx->total, 0, ',', '.') }})
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted">No recent transactions.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartData = @json($chartData);
        
        var options = {
            series: [{
                name: 'Revenue',
                data: chartData.series
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: "'Plus Jakarta Sans', sans-serif",
                foreColor: '#adb0bb',
                zoom: { enabled: false }
            },
            colors: ['#5d87ff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.0,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: chartData.labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString('id-ID');
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString('id-ID');
                    }
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const day = String(now.getDate()).padStart(2, '0');
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            
            const timeString = `${dayName}, ${day} ${month} ${year} - ${h}:${m}:${s}`;
            
            const clockElement = document.getElementById('dashboard-clock');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    });
</script>
@endpush
