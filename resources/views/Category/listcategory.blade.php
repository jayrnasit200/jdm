<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Category List') }}</h2>
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> {{ __('Add Category') }}
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
                        <h5 class="mb-0">{{ __('All Categories') }}</h5>
                    </div>

                    <div class="card-body">
                        {{-- ✅ Category Table --}}
                        @if ($categories->count() > 0)
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Category Name') }}</th>
                                            <th class="text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td class="text-end">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('categories.edit', $category->id) }}"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>

                                                    {{-- Delete Button Trigger --}}
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $category->id }}">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                    <a href="{{ route('subcatogory', $category->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                         <i class="bi bi-pencil"></i> Sub
                                                     </a>
                                                    {{-- Delete Confirmation Modal --}}
                                                    <div class="modal fade"
                                                         id="deleteModal{{ $category->id }}"
                                                         tabindex="-1"
                                                         aria-labelledby="deleteModalLabel{{ $category->id }}"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">
                                                                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <i class="bi bi-trash3 text-danger fs-1 mb-3"></i>
                                                                    <p class="fw-semibold">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $category->name }}</strong>?
                                                                    </p>
                                                                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">
                                                                        Cancel
                                                                    </button>
                                                                    <form action="{{ route('categories.destroy') }}"
                                                                          method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        {{-- $category->id --}}
                                                                        <input type="hidden" name="id" value="{{ $category->id }}">
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
                                <i class="bi bi-folder-x fs-1 text-secondary"></i>
                                <h5 class="mt-3">{{ __('No categories found.') }}</h5>
                                <p class="text-muted">Start by adding your first category below.</p>
                                <a href="{{ route('categories.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> {{ __('Add Category') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Optional Auto-hide Success Message --}}
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
