<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Shop Management') }}</h2>
            <a href="{{ route('shops.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add New Shop
            </a>
        </div>
    </x-slot>

    <div class="container my-5">
        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="shopsTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Shop Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>VAT</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shops as $shop)
                                <tr>
                                    <td>{{ $shop->id }}</td>
                                    <td>{{ $shop->shopname }}</td>
                                    <td>{{ $shop->email }}</td>
                                    <td>{{ $shop->phone }}</td>
                                    <td>{{ $shop->Vat }}</td>
                                    <td class="text-end">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('shops.edit', $shop->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        {{-- Delete Button Trigger --}}
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $shop->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>

                                        {{-- Delete Confirmation Modal --}}
                                        <div class="modal fade"
                                             id="deleteModal{{ $shop->id }}"
                                             tabindex="-1"
                                             aria-labelledby="deleteModalLabel{{ $shop->id }}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $shop->id }}">
                                                            <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <i class="bi bi-trash3 text-danger fs-1 mb-3"></i>
                                                        <p class="fw-semibold">
                                                            Are you sure you want to delete
                                                            <strong>{{ $shop->shopname }}</strong>?
                                                        </p>
                                                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                        <form action="{{ route('shops.destroy', $shop->id) }}" method="POST" class="d-inline">
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
                                        {{-- End Modal --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Include DataTables CDN --}}
    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#shopsTable').DataTable({
                    pageLength: 10,
                    order: [[0, 'desc']],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search shops..."
                    }
                });

                // Auto-hide success alert
                const alert = document.querySelector('.alert-success');
                if(alert){
                    setTimeout(() => new bootstrap.Alert(alert).close(), 4000);
                }
            });
        </script>
    @endpush
</x-app-layout>
