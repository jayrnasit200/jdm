<x-app-layout>
    {{-- Page header slot --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <h2 class="mb-1" style="font-weight: 700; color:#ffffff;">
                    Seller Dashboard
                </h2>
                <div style="font-size: 0.9rem; color:#d3d6dd;">
                    Welcome, {{ auth()->user()->name ?? 'Seller' }} – track your weekly, monthly and yearly performance.
                </div>
            </div>

            <div class="d-none d-md-flex align-items-center gap-2">
                <span class="badge bg-light text-dark">
                    <i class="fa fa-user-circle me-1"></i>
                    {{ auth()->user()->email ?? 'Seller Account' }}
                </span>
                     </div>
        </div>
    </x-slot>

    {{-- Custom styling --}}
    <style>
        .dashboard-section {
            padding-top: 24px;
            padding-bottom: 24px;
        }

        .filter-toggle-group .btn {
            border-radius: 999px !important;
        }

        .filter-toggle-group .btn.active {
            background: linear-gradient(135deg, #0b3c5d, #f35252);
            border-color: transparent;
            color: #ffffff !important;
            box-shadow: 0 6px 14px rgba(15,23,42,0.25);
        }

        .dash-metric-card {
            border-radius: 1rem;
            border: 0;
            background: #ffffff;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.10);
        }

        .dash-metric-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
        }

        .dash-metric-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dash-metric-sub {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .dash-main-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        }

        .dash-main-card-header {
            background:
                linear-gradient(135deg, rgba(11,60,93,0.92), rgba(243,82,82,0.92)),
                url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg');
            background-size: cover;
            background-position: center right;
            color: #ffffff;
            padding: 16px 20px;
            border-radius: 1rem 1rem 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .dash-main-card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .dash-main-card-header small {
            opacity: 0.9;
            font-size: 0.8rem;
        }

        .table-dashboard thead {
            background: #e9efff;
            color: #1e293b;
            font-weight: 600;
        }

        .table-dashboard tbody tr:hover {
            background: #eef2ff !important;
        }
    </style>

    <div class="container dashboard-section">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Top: filter buttons + label --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">Sales Overview</h4>
                <small class="text-muted" id="currentRangeLabel">
                    This Week ({{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M') }})
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('seller.products.report.pdf', ['range' => 'year']) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-file-pdf-o me-1"></i> Products PDF (Year)
                </a>

                <div class="btn-group filter-toggle-group" role="group" aria-label="Period filter">
                    <button type="button" class="btn btn-outline-primary active" data-range="week">
                        This Week
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-range="month">
                        This Month
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-range="year">
                        This Year
                    </button>
                </div>
            </div>
        </div>


        @php
            $weekStats  = $weekStats  ?? ['orders' => 0, 'sales' => 0, 'avg' => 0];
            $monthStats = $monthStats ?? ['orders' => 0, 'sales' => 0, 'avg' => 0];
            $yearStats  = $yearStats  ?? ['orders' => 0, 'sales' => 0, 'avg' => 0];
            $totalShops = $totalShops ?? 0;
        @endphp

        {{-- Stats in data attributes for JS --}}
        <div id="rangeStats"
             data-week-orders="{{ $weekStats['orders'] }}"
             data-week-sales="{{ $weekStats['sales'] }}"
             data-week-avg="{{ $weekStats['avg'] }}"
             data-month-orders="{{ $monthStats['orders'] }}"
             data-month-sales="{{ $monthStats['sales'] }}"
             data-month-avg="{{ $monthStats['avg'] }}"
             data-year-orders="{{ $yearStats['orders'] }}"
             data-year-sales="{{ $yearStats['sales'] }}"
             data-year-avg="{{ $yearStats['avg'] }}">
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <div class="dash-metric-card p-3 h-100">
                    <div class="dash-metric-label">Orders</div>
                    <div class="dash-metric-value" id="ordersCount">
                        {{ $weekStats['orders'] }}
                    </div>
                    <div class="dash-metric-sub">
                        Number of orders in selected period.
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="dash-metric-card p-3 h-100">
                    <div class="dash-metric-label">Sales (ex VAT)</div>
                    <div class="dash-metric-value" id="salesAmount">
                        £{{ number_format($weekStats['sales'], 2) }}
                    </div>
                    <div class="dash-metric-sub">
                        Based on order total field.
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="dash-metric-card p-3 h-100">
                    <div class="dash-metric-label">Avg Order Value</div>
                    <div class="dash-metric-value" id="avgAmount">
                        £{{ number_format($weekStats['avg'], 2) }}
                    </div>
                    <div class="dash-metric-sub">
                        Average per order in period.
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="dash-metric-card p-3 h-100">
                    <div class="dash-metric-label">Total Shops</div>
                    <div class="dash-metric-value">
                        {{ $totalShops }}
                    </div>
                    <div class="dash-metric-sub">
                        Shops registered on the system.
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts row: Sales trend + Product report --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-8">
                <div class="dash-metric-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="dash-metric-label">Sales Trend</div>
                            <div style="font-size:0.85rem; color:#6b7280;" id="salesChartSubtitle">
                                Sales for this week.
                            </div>
                        </div>
                        <i class="fa fa-line-chart text-muted"></i>
                    </div>
                    <canvas id="salesChart" style="max-height: 260px;"></canvas>
                </div>
            </div>

            {{-- Product selling report --}}
            <div class="col-12 col-lg-4">
                <div class="dash-metric-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="dash-metric-label">Product Selling Report</div>
                            <div style="font-size:0.85rem; color:#6b7280;">
                                Which products are selling the most.
                            </div>
                        </div>
                        <i class="fa fa-bar-chart text-muted"></i>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size:0.75rem;">Report Range</label>
                        <select id="productReportRange" class="form-select form-select-sm">
                            <option value="week" selected>This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-2" id="productReportCustomDates" style="display:none;">
                        <div class="col-6">
                            <label class="form-label mb-1" style="font-size:0.75rem;">Start Date</label>
                            <input type="date" id="productReportStart" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1" style="font-size:0.75rem;">End Date</label>
                            <input type="date" id="productReportEnd" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-2">
                        <button id="btnGenerateProductReport" class="btn btn-sm btn-primary rounded-pill">
                            Generate Report
                        </button>
                    </div>

                    <div class="table-responsive" style="max-height: 230px; overflow-y:auto;">
                        <table class="table table-sm table-striped mb-0" style="font-size:0.75rem;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Sales (£)</th>
                                </tr>
                            </thead>
                            <tbody id="productReportBody">
                                @forelse($productReportWeek as $row)
                                    <tr>
                                        <td>
                                            {{ $row->product->name ?? 'Unknown' }}
                                            @if(!empty($row->product->model_number))
                                                <br><small class="text-muted">Code: {{ $row->product->model_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ (int) $row->total_qty }}</td>
                                        <td>£{{ number_format($row->total_sales, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No product data for this week.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Orders table --}}
        <div class="card dash-main-card">
            <div class="dash-main-card-header">
                <div>
                    <h5 class="mb-1">Orders in Selected Period</h5>
                    <small id="ordersSubtitle">
                        Showing orders for this week.
                    </small>
                </div>
                <span class="badge bg-light text-dark">
                    <i class="fa fa-calendar me-1"></i> Dynamic View
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dashboard table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Shop</th>
                                <th>Date</th>
                                <th>Total (£)</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                        </thead>

                        {{-- Week --}}
                        <tbody id="tbody-week" class="orders-tbody">
                            @forelse($ordersWeek as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->shop->shopname ?? 'N/A' }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>£{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->payment_status === 'success')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No orders found for this week.</td></tr>
                            @endforelse
                        </tbody>

                        {{-- Month --}}
                        <tbody id="tbody-month" class="orders-tbody d-none">
                            @forelse($ordersMonth as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->shop->shopname ?? 'N/A' }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>£{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->payment_status === 'success')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No orders found for this month.</td></tr>
                            @endforelse
                        </tbody>

                        {{-- Year --}}
                        <tbody id="tbody-year" class="orders-tbody d-none">
                            @forelse($ordersYear as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->shop->shopname ?? 'N/A' }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>£{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->payment_status === 'success')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No orders found for this year.</td></tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        {{-- Chart.js CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buttons = document.querySelectorAll('.filter-toggle-group .btn');
                const statsEl = document.getElementById('rangeStats');
                const ordersCountEl = document.getElementById('ordersCount');
                const salesAmountEl = document.getElementById('salesAmount');
                const avgAmountEl   = document.getElementById('avgAmount');
                const currentRangeLabel = document.getElementById('currentRangeLabel');
                const ordersSubtitle = document.getElementById('ordersSubtitle');
                const salesChartSubtitle = document.getElementById('salesChartSubtitle');

                const tbodyMap = {
                    week: document.getElementById('tbody-week'),
                    month: document.getElementById('tbody-month'),
                    year: document.getElementById('tbody-year'),
                };

                const rangeLabels = {
                    week: 'This Week',
                    month: 'This Month',
                    year: 'This Year',
                };

                const ordersSubtitleMap = {
                    week: 'Showing orders for this week.',
                    month: 'Showing orders for this month.',
                    year: 'Showing orders for this year.',
                };

                const salesSubtitleMap = {
                    week: 'Sales for this week.',
                    month: 'Daily sales for this month.',
                    year: 'Monthly sales for this year.',
                };

                function formatMoney(num) {
                    const n = parseFloat(num) || 0;
                    return '£' + n.toFixed(2);
                }

                // 🔹 Sales chart datasets from PHP
                const salesDatasets = {
                    week: {
                        labels: @json($weekChart['labels']),
                        data:   @json($weekChart['data']),
                    },
                    month: {
                        labels: @json($monthChart['labels']),
                        data:   @json($monthChart['data']),
                    },
                    year: {
                        labels: @json($yearChart['labels']),
                        data:   @json($yearChart['data']),
                    },
                };

                // Sales Chart
                const salesCtx = document.getElementById('salesChart').getContext('2d');
                const salesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: salesDatasets.week.labels,
                        datasets: [{
                            label: 'Sales',
                            data: salesDatasets.week.data,
                            fill: false,
                            tension: 0.3,
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: (value) => '£' + value
                                }
                            }
                        }
                    }
                });

                // Switch range handler for top stats + orders table + chart
                function switchRange(range) {
                    // Buttons
                    buttons.forEach(btn => {
                        btn.classList.toggle('active', btn.dataset.range === range);
                    });

                    // Table bodies
                    Object.keys(tbodyMap).forEach(key => {
                        if (key === range) {
                            tbodyMap[key].classList.remove('d-none');
                        } else {
                            tbodyMap[key].classList.add('d-none');
                        }
                    });

                    // Top numeric stats
                    const orders = statsEl.dataset[`${range}Orders`] || 0;
                    const sales  = statsEl.dataset[`${range}Sales`]  || 0;
                    const avg    = statsEl.dataset[`${range}Avg`]    || 0;

                    ordersCountEl.textContent = orders;
                    salesAmountEl.textContent = formatMoney(sales);
                    avgAmountEl.textContent   = formatMoney(avg);

                    currentRangeLabel.textContent = rangeLabels[range];
                    ordersSubtitle.textContent    = ordersSubtitleMap[range];
                    salesChartSubtitle.textContent = salesSubtitleMap[range];

                    // Update sales chart dataset
                    if (salesDatasets[range]) {
                        salesChart.data.labels = salesDatasets[range].labels;
                        salesChart.data.datasets[0].data = salesDatasets[range].data;
                        salesChart.update();
                    }
                }

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        switchRange(btn.dataset.range);
                    });
                });

                // Default range on load
                switchRange('week');

                // 🔹 Product report logic
                const rangeSelect = document.getElementById('productReportRange');
                const customDatesBox = document.getElementById('productReportCustomDates');
                const startInput = document.getElementById('productReportStart');
                const endInput   = document.getElementById('productReportEnd');
                const btnGenerate = document.getElementById('btnGenerateProductReport');
                const productReportBody = document.getElementById('productReportBody');

                rangeSelect.addEventListener('change', () => {
                    if (rangeSelect.value === 'custom') {
                        customDatesBox.style.display = 'flex';
                    } else {
                        customDatesBox.style.display = 'none';
                    }
                });

                btnGenerate.addEventListener('click', () => {
                    const range = rangeSelect.value;
                    const params = new URLSearchParams();
                    params.append('range', range);

                    if (range === 'custom') {
                        if (startInput.value) params.append('start_date', startInput.value);
                        if (endInput.value)   params.append('end_date', endInput.value);
                    }

                    fetch(`{{ route('seller.product-report') }}?` + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(json => {
                            if (!json.success) {
                                alert('Failed to load product report.');
                                return;
                            }

                            const rows = json.data || [];
                            productReportBody.innerHTML = '';

                            if (rows.length === 0) {
                                productReportBody.innerHTML = `
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No product data for this range.
                                        </td>
                                    </tr>`;
                                return;
                            }

                            rows.forEach(row => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>
                                        ${row.product_name}
                                        ${row.model_number ? `<br><small class="text-muted">Code: ${row.model_number}</small>` : ''}
                                    </td>
                                    <td>${row.total_qty}</td>
                                    <td>£${(row.total_sales || 0).toFixed(2)}</td>
                                `;
                                productReportBody.appendChild(tr);
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error loading product report.');
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
