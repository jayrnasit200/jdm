@extends('layouts.owner')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'See all products, add a new product, or edit an existing one.')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 owner-toolbar">
        <h6 class="mb-0">All Products</h6>
        <a href="{{ route('owner.products.create') }}" class="btn btn-sm btn-dark">
            <i class="fa fa-plus me-1"></i> Add New Product
        </a>
    </div>

    @if ($products->isEmpty())
        <div class="card shadow-soft border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa fa-cube fa-2x mb-2"></i>
                <h6 class="mt-2">No products found</h6>
                <p class="small mb-3">Start by adding your first product.</p>
                <a href="{{ route('owner.products.create') }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-plus me-1"></i> Add New Product
                </a>
            </div>
        </div>
    @else
        <form id="bulkStatusForm" method="POST" action="{{ route('owner.products.bulk-status') }}" class="d-none">
            @csrf
            <input type="hidden" name="status" id="bulkStatusValue">
        </form>

        <div class="card shadow-soft border-0 mb-3">
            <div class="card-body py-2">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAllProducts">
                        <label class="form-check-label small" for="selectAllProducts">
                            Select all
                            <span class="text-muted">(<span id="bulkSelectedCount">0</span> selected)</span>
                        </label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success flex-grow-1 flex-md-grow-0 bulk-status-btn" data-status="enable" disabled>
                            <i class="fa fa-check me-1"></i> Enable
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary flex-grow-1 flex-md-grow-0 bulk-status-btn" data-status="disable" disabled>
                            <i class="fa fa-ban me-1"></i> Disable
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-none mb-3">
            <input type="search" id="mobileProductSearch" class="form-control" placeholder="Search products…">
        </div>

        <div class="d-md-none d-flex flex-column gap-2 mb-3" id="mobile-product-list">
            @foreach ($products as $product)
                <div class="card shadow-soft border-0 mobile-product-card"
                     data-search="{{ strtolower($product->name.' '.$product->model_number.' '.($product->category->name ?? '')) }}">
                    <div class="card-body py-3">
                        <div class="d-flex gap-3">
                            <div class="pt-1">
                                <input class="form-check-input product-check" type="checkbox"
                                       data-id="{{ $product->id }}"
                                       aria-label="Select {{ $product->name }}">
                            </div>
                            @if ($product->image)
                                <img src="{{ media_url($product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted"
                                     style="width:56px;height:56px;border-radius:8px;">
                                    <i class="fa fa-cube"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                                <div class="small text-muted">{{ $product->model_number }}</div>
                                <div class="small text-muted text-truncate">
                                    {{ $product->category->name ?? '—' }}
                                    @if($product->subcategory)
                                        · {{ $product->subcategory->name }}
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fw-semibold">£{{ number_format($product->price, 2) }}</span>
                                    @if ($product->status === 'enable')
                                        <span class="badge bg-success bg-opacity-75">Enable</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-50">Disable</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('owner.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-secondary flex-grow-1">
                                <i class="fa fa-pencil me-1"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteProduct{{ $product->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-soft border-0 d-none d-md-block">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle mb-0" id="owner-products-table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 36px;">
                                    <input class="form-check-input select-all-table" type="checkbox" aria-label="Select all">
                                </th>
                                <th>#</th>
                                <th>Image</th>
                                <th>Model Number</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Price</th>
                                <th>VAT</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td class="text-center">
                                        <input class="form-check-input product-check" type="checkbox"
                                               data-id="{{ $product->id }}"
                                               aria-label="Select {{ $product->name }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ media_url($product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->model_number }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '—' }}</td>
                                    <td>{{ $product->subcategory->name ?? '—' }}</td>
                                    <td>£{{ number_format($product->price, 2) }}</td>
                                    <td>{{ strtoupper($product->vat ?? 'no') }}</td>
                                    <td>
                                        @if ($product->status === 'enable')
                                            <span class="badge bg-success bg-opacity-75">Enable</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-50">Disable</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('owner.products.edit', $product) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-pencil me-1"></i> Edit
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteProduct{{ $product->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @foreach ($products as $product)
        <div class="modal fade"
             id="deleteProduct{{ $product->id }}"
             tabindex="-1"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-soft">
                    <div class="modal-header border-0">
                        <h6 class="modal-title">Delete product</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        Are you sure you want to delete
                        <strong>{{ $product->name }}</strong>?
                        This cannot be undone.
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        function selectedIds() {
            var ids = {};
            $('.product-check:checked').each(function () {
                ids[$(this).data('id')] = true;
            });
            return Object.keys(ids);
        }

        function syncChecks(id, checked) {
            $('.product-check[data-id="' + id + '"]').prop('checked', checked);
        }

        function uniqueProductCount() {
            var ids = {};
            $('.product-check').each(function () {
                ids[$(this).data('id')] = true;
            });
            return Object.keys(ids).length;
        }

        function updateBulkBar() {
            var ids = selectedIds();
            $('#bulkSelectedCount').text(ids.length);
            $('.bulk-status-btn').prop('disabled', ids.length === 0);
            var allSelected = uniqueProductCount() > 0 && ids.length === uniqueProductCount();
            $('#selectAllProducts, .select-all-table').prop('checked', allSelected);
        }

        $(document).on('change', '.product-check', function () {
            syncChecks($(this).data('id'), $(this).is(':checked'));
            updateBulkBar();
        });

        $(document).on('change', '#selectAllProducts, .select-all-table', function () {
            var checked = $(this).is(':checked');
            $('.product-check').prop('checked', checked);
            $('#selectAllProducts, .select-all-table').prop('checked', checked);
            updateBulkBar();
        });

        $('.bulk-status-btn').on('click', function () {
            var ids = selectedIds();
            var status = $(this).data('status');
            if (!ids.length) {
                return;
            }

            var label = status === 'enable' ? 'enable' : 'disable';
            if (!confirm('Are you sure you want to ' + label + ' ' + ids.length + ' product(s)?')) {
                return;
            }

            var $form = $('#bulkStatusForm');
            $form.find('input[name="ids[]"]').remove();
            ids.forEach(function (id) {
                $form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
            });
            $('#bulkStatusValue').val(status);
            $form.trigger('submit');
        });

        if ($('#owner-products-table').length && $('#owner-products-table').is(':visible')) {
            $('#owner-products-table').DataTable({
                pageLength: 25,
                order: [],
                scrollX: true,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }
                ],
                language: { searchPlaceholder: 'Search products…' }
            });
        }

        $('#mobileProductSearch').on('input', function () {
            var q = $(this).val().toLowerCase();
            $('#mobile-product-list .mobile-product-card').each(function () {
                $(this).toggle(String($(this).data('search')).indexOf(q) !== -1);
            });
        });

        updateBulkBar();
    });
</script>
@endpush
