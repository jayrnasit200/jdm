@extends('layouts.owner')

@section('title', 'Product Sales Report')

@push('styles')
    {{-- DataTables core + Buttons (Bootstrap 5 skin) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Product Sales Report</h4>
            <small class="text-muted">
                See total quantity and earnings per product in the selected period.
            </small>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.reports.products') }}" class="row g-2 align-items-end">

                <div class="col-md-3 col-6">
                    <label class="form-label small mb-1">From date</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label small mb-1">To date</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3 col-6 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-dark btn-sm w-100">
                        <i class="fa fa-search me-1"></i> Filter
                    </button>
                </div>

                <div class="col-md-3 col-6 mt-2 mt-md-0">
                    <a href="{{ route('owner.reports.products') }}"
                       class="btn btn-outline-secondary btn-sm w-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    @php
        $grandTotalEarning = $productsReport->sum('total_earning');
        $grandTotalQty     = $productsReport->sum('total_qty');
    @endphp

    <div class="row mb-3 g-2">
        <div class="col-md-4 col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Products Sold</div>
                            <div class="fw-bold fs-5">{{ $grandTotalQty }}</div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                             style="width:38px;height:38px;">
                            <i class="fa fa-shopping-basket text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Earnings</div>
                            <div class="fw-bold fs-5">£{{ number_format($grandTotalEarning, 2) }}</div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                             style="width:38px;height:38px;">
                            <i class="fa fa-gbp text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Products</h6>
                <small class="text-muted">Search + Export (Excel / PDF / Print)</small>
            </div>

            {{-- ✅ Extra Search Input (Nice UI) --}}
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                        <input type="text" id="tableSearch"
                               class="form-control"
                               placeholder="Search product, qty, earning...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle" id="products-report-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Ref</th>
                            <th class="text-end">Total Qty</th>
                            <th class="text-end">Average Price</th>
                            <th class="text-end">Total Earning</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productsReport as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->model_number }}</td>
                                <td class="text-end">{{ $row->total_qty }}</td>
                                <td class="text-end">£{{ number_format($row->avg_price, 2) }}</td>
                                <td class="text-end fw-semibold">£{{ number_format($row->total_earning, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted small py-3">
                                    No data found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- ✅ REQUIRED: DataTables core --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    {{-- Buttons --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    {{-- Excel --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    {{-- PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- HTML5 export --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {

            if (!$('#products-report-table').length) return;

            var exportTitle = @json(
                'Product Sales Report'
                .($dateFrom ? ' From '.$dateFrom : '')
                .($dateTo ? ' To '.$dateTo : '')
            );

            // ✅ Initialize DataTable (includes built-in search too)
            var table = $('#products-report-table').DataTable({
                pageLength: 25,
                lengthMenu: [25, 50, 100, -1],
                order: [[4, 'desc']],
                dom:
                    "<'row mb-2'<'col-sm-6 d-flex align-items-center gap-2'l><'col-sm-6 text-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    { extend: 'excelHtml5', title: exportTitle, className: 'btn btn-success btn-sm' },
                    {
                        extend: 'pdfHtml5',
                        title: exportTitle,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: { columns: [0,1,2,3,4] }
                    },
                    {
                        extend: 'print',
                        title: exportTitle,
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: { columns: [0,1,2,3,4] }
                    }
                ]
            });

            // ✅ Your custom search box controls DataTables search
            $('#tableSearch').on('keyup', function () {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
