@extends('layouts.owner')

@section('title', 'Shop Details')

@section('content')
<div class="container-fluid py-3">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ $shop->company_name }}</h4>
            <small class="text-muted">
                {{ $shop->shopname }} • Ref: {{ $shop->ref }}
            </small>
        </div>
        <a href="{{ route('reports.shop-balance') }}" class="btn btn-outline-secondary btn-sm">
            ← Back
        </a>
    </div>

    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Sales</div>
                    <div class="fw-bold fs-5">£{{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Paid</div>
                    <div class="fw-bold fs-5">£{{ number_format($totalPaid, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="text-muted small">Total Due</div>
                    <div class="fw-bold fs-5">£{{ number_format($totalDue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SHOP INFO ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="mb-2">Shop Info</h6>
            <div class="row small">
                <div class="col-md-6">
                    <div><span class="text-muted">Company:</span> {{ $shop->company_name }}</div>
                    <div><span class="text-muted">Shop Name:</span> {{ $shop->shopname }}</div>
                    <div><span class="text-muted">Ref:</span> {{ $shop->ref }}</div>
                </div>
                <div class="col-md-6">
                    <div><span class="text-muted">Address:</span> {{ $shop->address }}</div>
                    <div><span class="text-muted">City:</span> {{ $shop->city }}</div>
                    <div><span class="text-muted">Postcode:</span> {{ $shop->postcode }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ORDERS TABLE ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Orders</h6>
                <small class="text-muted">Click “Items” to view order items</small>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle" id="shop-orders-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Payment</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $o)
                            <tr>
                                <td>{{ $o->id }}</td>
                                <td class="fw-semibold">{{ $o->invoice_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($o->payment_status === 'success')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">£{{ number_format($o->total, 2) }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-dark js-view-items"
                                        data-order-id="{{ $o->id }}"
                                        data-invoice="{{ $o->invoice_number }}">
                                        Items
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== PRODUCTS SUMMARY ===== --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Products Summary</h6>
                <small class="text-muted">Total quantity & amount sold</small>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle" id="shop-products-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th class="text-end">Qty Sold</th>
                            <th class="text-end">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productsSummary as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $p->name }}</td>
                                <td>{{ $p->model_number }}</td>
                                <td class="text-end">{{ $p->total_qty }}</td>
                                <td class="text-end fw-semibold">
                                    £{{ number_format($p->total_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ===== ITEMS MODAL ===== --}}
<div class="modal fade" id="itemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Items — <span id="modalInvoice"></span></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="itemsLoading" class="text-center text-muted py-3 d-none">Loading…</div>
                <div id="itemsError" class="alert alert-danger d-none"></div>

                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    $('#shop-orders-table').DataTable({
        pageLength: 25,
        order: [[2, 'desc']],
        language: { searchPlaceholder: 'Search invoice / payment…' }
    });

    $('#shop-products-table').DataTable({
        pageLength: 10,
        order: [[3, 'desc']],
        language: { searchPlaceholder: 'Search product…' }
    });

    const modal = new bootstrap.Modal(document.getElementById('itemsModal'));

    document.querySelectorAll('.js-view-items').forEach(btn => {
        btn.addEventListener('click', async () => {
            document.getElementById('modalInvoice').innerText = btn.dataset.invoice;
            document.getElementById('itemsTbody').innerHTML = '';
            document.getElementById('itemsLoading').classList.remove('d-none');
            document.getElementById('itemsError').classList.add('d-none');
            modal.show();

            try {
                const res = await fetch(`/owner/orders/${btn.dataset.orderId}/items`);
                const json = await res.json();

                document.getElementById('itemsTbody').innerHTML = json.items.map(i => `
                    <tr>
                        <td>${i.name}</td>
                        <td>${i.model_number ?? ''}</td>
                        <td class="text-end">£${(+i.selling_price).toFixed(2)}</td>
                        <td class="text-end">£${(+i.discount).toFixed(2)}</td>
                        <td class="text-end">${i.quantity}</td>
                        <td class="text-end fw-semibold">£${(+i.line_total).toFixed(2)}</td>
                    </tr>
                `).join('');
            } catch {
                document.getElementById('itemsError').textContent = 'Failed to load items.';
                document.getElementById('itemsError').classList.remove('d-none');
            } finally {
                document.getElementById('itemsLoading').classList.add('d-none');
            }
        });
    });
});
</script>
@endpush
