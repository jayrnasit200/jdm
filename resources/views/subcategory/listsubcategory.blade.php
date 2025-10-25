<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Subcategory List') }}</h2>
            <a href="{{ route('subcategories.create', $catid) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> {{ __('Add Subcategory') }}
            </a>
        </div>
    </x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- ✅ Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('All Subcategories') }}</h5>
                    </div>

                    <div class="card-body">
                        @if ($subcategories->count() > 0)
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Subcategory Name') }}</th>
                                            <th class="text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subcategories as $index => $subcategory)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subcategory->name }}</td>
                                                <td class="text-end">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>

                                                    {{-- Delete Button Trigger --}}
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $subcategory->id }}">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>

                                                    {{-- Delete Confirmation Modal --}}
                                                    <div class="modal fade" id="deleteModal{{ $subcategory->id }}" tabindex="-1"
                                                         aria-labelledby="deleteModalLabel{{ $subcategory->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="deleteModalLabel{{ $subcategory->id }}">
                                                                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <i class="bi bi-trash3 text-danger fs-1 mb-3"></i>
                                                                    <p class="fw-semibold">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $subcategory->name }}</strong>?
                                                                    </p>
                                                                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="{{ route('subcategories.destroy', $subcategory->id) }}"
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
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 text-secondary"></i>
                                <h5 class="mt-3">{{ __('No subcategories found.') }}</h5>
                                <p class="text-muted">Start by adding your first subcategory below.</p>
                                <a href="{{ route('subcategories.create', $catid) }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Add Subcategory
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Auto-hide Success Alert --}}
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
