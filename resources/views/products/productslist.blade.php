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

                {{-- ✅ Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('All Products') }}</h5>
                    </div>

                    <div class="card-body">
                        @if ($products->count() > 0)
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
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
                                                <td>{{ $index + 1 }}</td>

                                                {{-- ✅ Product Image --}}
                                                <td>
                                                    @if ($product->image)
                                                        <a href="{{ asset('storage/' . $product->image) }}"
                                                           data-lightbox="product-gallery"
                                                           data-title="{{ $product->name }}">
                                                            <img src="{{ asset('storage/' . $product->image) }}"
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
                                                <td>{{ ucfirst($product->status) }}</td>

                                                <td class="text-end">
                                                    {{-- ✅ Edit Button --}}
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>

                                                    {{-- ✅ Delete Button --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $product->id }}">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>

                                                    {{-- ✅ Delete Confirmation Modal --}}
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- Empty State --}}
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

    {{-- ✅ Auto-hide Success Message --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    const fade = new bootstrap.Alert(alert);
                    fade.close();
                }, 4000);
            }
        });
    </script>
</x-app-layout>
