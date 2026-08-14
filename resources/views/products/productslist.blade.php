<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Product List') }}</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> {{ __('Add Product') }}
            </a>
        </div>
    </x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <h5 class="mb-0">{{ __('All Products') }}</h5>
                        @if ($products->count() > 0)
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="form-check mb-0 me-1">
                                    <input class="form-check-input" type="checkbox" id="selectAllProducts">
                                    <label class="form-check-label small" for="selectAllProducts">
                                        Select all
                                        <span class="text-muted">(<span id="bulkSelectedCount">0</span> selected)</span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-success bulk-status-btn" data-status="enable" disabled>
                                    <i class="fa fa-check me-1"></i> Enable
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary bulk-status-btn" data-status="disable" disabled>
                                    <i class="fa fa-ban me-1"></i> Disable
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($products->count() > 0)
                            <form id="bulkStatusForm" method="POST" action="{{ route('products.bulk-status') }}" class="d-none">
                                @csrf
                                <input type="hidden" name="status" id="bulkStatusValue">
                            </form>

                            <div class="table-responsive">
                                <table id="seller-products-table" class="table table-striped align-middle">
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
                                                        <a href="{{ media_url($product->image) }}"
                                                           data-lightbox="product-gallery"
                                                           data-title="{{ $product->name }}">
                                                            <img src="{{ media_url($product->image) }}"
                                                                 alt="{{ $product->name }}"
                                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $product->model_number }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? '-' }}</td>
                                                <td>{{ $product->subcategory->name ?? '-' }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>
                                                    @if ($product->status === 'enable')
                                                        <span class="badge bg-success">Enable</span>
                                                    @else
                                                        <span class="badge bg-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $product->id }}">
                                                            <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 text-secondary"></i>
                                <h5 class="mt-3">No products found.</h5>
                                <p class="text-muted">Start by adding your first product below.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Add Product
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($products as $product)
        <div class="modal fade"
             id="deleteModal{{ $product->id }}"
             tabindex="-1"
             aria-labelledby="deleteModalLabel{{ $product->id }}"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">
                            <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-trash3 text-danger fs-1 mb-3"></i>
                        <p class="fw-semibold">
                            Are you sure you want to delete
                            <strong>{{ $product->name }}</strong>?
                        </p>
                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

            if ($('#seller-products-table').length && !$.fn.DataTable.isDataTable('#seller-products-table')) {
                $('#seller-products-table').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    order: [],
                    columnDefs: [
                        { orderable: false, searchable: false, targets: 0 }
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search products..."
                    }
                });
            }

            updateBulkBar();
        });
    </script>
    @endpush
</x-app-layout>
