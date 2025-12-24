@extends('layouts.owner')

@section('title', 'Shop Balances')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Shop Balances</h4>
            <small class="text-muted">Paid = orders with payment_status = success</small>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Sales</div>
                    <div class="fw-bold fs-5">£{{ number_format($grandTotalSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Paid</div>
                    <div class="fw-bold fs-5">£{{ number_format($grandTotalPaid, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Due</div>
                    <div class="fw-bold fs-5">£{{ number_format($grandTotalDue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle" id="shops-balance-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Shop</th>
                            <th>Ref</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Due</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shopsReport as $i => $s)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td class="fw-semibold">{{ $s->company_name }}</td>
                                <td>{{ $s->shopname }}</td>
                                <td>{{ $s->ref }}</td>
                                <td class="text-end">{{ $s->total_orders }}</td>
                                <td class="text-end">£{{ number_format($s->total_sales, 2) }}</td>
                                <td class="text-end">£{{ number_format($s->total_paid, 2) }}</td>
                                <td class="text-end fw-bold">£{{ number_format($s->total_due, 2) }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-dark"
                                       href="{{ route('shops.details', $s->shop_id) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.$ && $.fn.DataTable) {
                        $('#shops-balance-table').DataTable({
                            pageLength: 25,
                            order: [[7, 'desc']],
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Search shop / ref..."
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>

</div>
@endsection
