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

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('All Products') }}</h5>
                    </div>

                    <div class="card-body">
                        @if($products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
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
                                        @foreach($products as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                
                                                {{-- Display main image --}}
                                                <td>
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
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
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1"></i>
                                <h5 class="mt-3">No products found.</h5>
                                <p>Add your first product below.</p>
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

    {{-- Auto-hide success alert --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alert = document.querySelector('.alert-success');
            if(alert){
                setTimeout(() => {
                    new bootstrap.Alert(alert).close();
                }, 4000);
            }
        });
    </script>
</x-app-layout>
