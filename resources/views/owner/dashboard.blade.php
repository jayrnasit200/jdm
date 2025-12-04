@extends('layouts.owner')

@section('title', 'Dashboard')
@section('page_title', 'Owner Dashboard')
@section('page_subtitle', 'High-level overview of shops, sellers and sales performance.')

@section('content')
    <div class="container-fluid px-0">

        {{-- Top KPI Row --}}
        <div class="row g-3">
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Sellers</div>
                        <div class="h4 mb-1">{{ $totalSellers ?? 0 }}</div>
                        <div class="small text-success">
                            <i class="fa fa-arrow-up me-1"></i>Active network
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Customers</div>
                        <div class="h4 mb-1">{{ $totalCustomers ?? 0 }}</div>
                        <div class="small text-muted">
                            Repeat + new buyers
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Shops</div>
                        <div class="h4 mb-1">{{ $totalShops ?? 0 }}</div>
                        <div class="small text-muted">
                            Linked retail locations
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Orders</div>
                        <div class="h4 mb-1">{{ $totalOrders ?? 0 }}</div>
                        <div class="small text-muted">
                            Across all sellers
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Sales This Month</div>
                        <div class="h4 mb-1">
                            £{{ number_format($salesThisMonth ?? 0, 2) }}
                        </div>
                        <div class="small text-success">
                            {{ $currentMonthName ?? now()->format('M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body py-3">
                        <div class="small text-muted">Sales This Year</div>
                        <div class="h4 mb-1">
                            £{{ number_format($salesThisYear ?? ($totalSales ?? 0), 2) }}
                        </div>
                        <div class="small text-muted">
                            {{ $currentYear ?? now()->format('Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Middle Row: Chart + Side Stats --}}
        <div class="row g-3 mt-3">
            {{-- Sales Chart --}}
            <div class="col-lg-8">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">Sales Overview</h6>
                                <small class="text-muted">
                                    Monthly sales trend (last 12 months)
                                </small>
                            </div>
                            <span class="badge bg-dark-subtle text-dark small">
                                Total: £{{ number_format($totalSales ?? 0, 2) }}
                            </span>
                        </div>

                        <div style="height: 260px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Side quick stats --}}
            <div class="col-lg-4">
                <div class="card shadow-soft border-0 mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Quick Performance</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="border rounded-3 p-2 h-100">
                                    <div class="small text-muted">Today’s Sales</div>
                                    <div class="fw-semibold">
                                        £{{ number_format($salesToday ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded-3 p-2 h-100">
                                    <div class="small text-muted">This Week</div>
                                    <div class="fw-semibold">
                                        £{{ number_format($salesThisWeek ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded-3 p-2 h-100">
                                    <div class="small text-muted">Avg. Order Value</div>
                                    <div class="fw-semibold">
                                        £{{ number_format($avgOrderValue ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded-3 p-2 h-100">
                                    <div class="small text-muted">Best Seller (Month)</div>
                                    <div class="fw-semibold small">
                                        {{ $topProductThisMonth->name ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Management Shortcuts --}}
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-2">Owner Shortcuts</h6>
                        <p class="text-muted small mb-3">
                            Quickly jump to the most important owner tools.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('owner.sellers.index') }}" class="btn btn-dark btn-sm">
                                <i class="fa fa-users me-1"></i> Manage Sellers
                            </a>
                            <a href="{{ route('owner.reports.products') }}" class="btn btn-outline-dark btn-sm">
                                <i class="fa fa-bar-chart me-1"></i> Product Reports
                            </a>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-shopping-basket me-1"></i> Front Store
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Row: Notes & Summary --}}
        <div class="row g-3 mt-3">
            <div class="col-lg-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-2">Network Summary</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span>Active Sellers</span>
                                <span class="fw-semibold">{{ $activeSellers ?? $totalSellers ?? 0 }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span>Active Shops</span>
                                <span class="fw-semibold">{{ $activeShops ?? $totalShops ?? 0 }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span>Orders This Month</span>
                                <span class="fw-semibold">{{ $ordersThisMonth ?? 0 }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1">
                                <span>Top Seller (By Revenue)</span>
                                <span class="fw-semibold small">
                                    {{ $topSellerName ?? '—' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-soft border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-2">Owner Notes</h6>
                        <p class="small text-muted mb-2">
                            Use this area as a quick reminder for your weekly routine:
                        </p>
                        <ul class="small mb-0">
                            <li>Review seller performance and follow up on low-activity accounts.</li>
                            <li>Check top-selling products and make sure stock levels are healthy.</li>
                            <li>Compare this month’s sales against last month for growth tracking.</li>
                            <li>Identify key shops that can benefit from promotions or new lines.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{-- Chart.js (only once in your layout or here) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart');

            if (!ctx) return;

            const labels = @json($salesByMonthLabels ?? []);
            const values = @json($salesByMonthTotals ?? []);

            if (!labels.length || !values.length) {
                // No data: optional – you can show a message instead
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (£)',
                        data: values,
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return '£' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            ticks: {
                                callback: value => '£' + value
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
