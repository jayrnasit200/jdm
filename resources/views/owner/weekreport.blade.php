@extends('layouts.owner')

@section('title', 'This Week Orders')

@section('content')
<div class="container-fluid py-3">

    {{-- ===== HEADER ===== --}}
   {{-- ===== HEADER ===== --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Orders Report</h4>
        <small class="text-muted">
            {{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}
        </small>
    </div>

    <form method="GET" class="d-flex gap-2 align-items-end">

        <div>
            <label class="small text-muted">From</label>
            <input type="date"
                   name="from_date"
                   class="form-control form-control-sm"
                   value="{{ request('from_date', $from->format('Y-m-d')) }}"
                   onchange="this.form.submit()">
        </div>

        <div>
            <label class="small text-muted">To</label>
            <input type="date"
                   name="to_date"
                   class="form-control form-control-sm"
                   value="{{ request('to_date', $to->format('Y-m-d')) }}"
                   onchange="this.form.submit()">
        </div>

        <noscript>
            <button type="submit" class="btn btn-sm btn-primary">
                Filter
            </button>
        </noscript>

    </form>
</div>
    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Orders</div>
                    <div class="fw-bold fs-4">{{ $weeklyOrders->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Sales</div>
                    <div class="fw-bold fs-4">
                        £{{ number_format($weeklyOrders->sum('total'), 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Paid Orders</div>
                    <div class="fw-bold fs-4">
                        {{ $weeklyOrders->where('payment_status', 'success')->count() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- ===== SELLER SUMMARY BOXES ===== --}}
<div class="row g-3 mb-3">
    @foreach($sellerSummary as $seller => $data)
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="fw-bold mb-1">
                        {{ $seller ?? 'Unknown Seller' }}
                    </div>

                    <div class="small text-muted">
                        Orders This Week
                    </div>
                    <div class="fs-5 fw-semibold mb-2">
                        {{ $data['total_orders'] }}
                    </div>

                    <div class="small text-muted">
                        Total Sales
                    </div>
                    <div class="fs-6 fw-bold text-success">
                        £{{ number_format($data['total_sales'], 2) }}
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
    </div>

    {{-- ===== WEEKLY ORDERS TABLE ===== --}}
    <div class="card shadow-sm">

        <div class="card-body">
            <div class="row mb-2">
                <div class="row mb-2">
                    <div class="col-md-3 ms-auto">
                        <select id="sellerFilter" class="form-select form-select-sm">
                            <option value="">All Sellers</option>
                            @foreach($weeklyOrders->pluck('seller.name')->unique() as $seller)
                                @if($seller)
                                    <option value="{{ $seller }}">{{ $seller }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle" id="weekly-orders-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Shop Name</th>
                            <th>Ref</th>
                            <th>Address</th>
                            <th>Postcode</th>
                            <th>Seller</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="text-end">Amount (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklyOrders as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>

                                <td class="fw-semibold">
                                    {{ $order->shop->shopname ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->shop->ref ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->shop->address ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->shop->postcode ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->seller->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                </td>

                                <td class="text-end fw-semibold">
                                    £{{ number_format($order->total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    $(document).ready(function () {

        var sellerColors = {};
        var colorIndex = 0;

        var colorList = [
            '#ffcccc',
            '#ccffcc',
            '#cce5ff',
            '#fff0cc',
            '#e6ccff',
            '#ccffff'
        ];

        var table = $('#weekly-orders-table').DataTable({
            pageLength: 25,
            order: [[6, 'desc']],
            dom: 'Bfrtip',
            buttons: ['print', 'pdf', 'excel']
        });

        function applySellerColors() {

            table.rows({ page: 'current' }).every(function () {

                var data = this.data();
                var seller = data[5]; // seller column
                var rowNode = this.node();

                if (!sellerColors[seller]) {
                    sellerColors[seller] = colorList[colorIndex % colorList.length];
                    colorIndex++;
                }

                // Color ONLY seller cell
                $('td:eq(5)', rowNode).css({
                    'background-color': sellerColors[seller],
                    'font-weight': 'bold'
                });

            });
        }

        applySellerColors();

        table.on('draw', function () {
            applySellerColors();
        });

        $('#sellerFilter').on('change', function () {
            table.column(5).search(this.value).draw();
        });

    });
    </script>
@endpush