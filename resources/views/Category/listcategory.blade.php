{{-- <x-app-layout>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('All Categories') }}</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($categories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">{{ __('Category Name') }}</th>
                                            <th scope="col">{{ __('Description') }}</th>
                                            <th scope="col">{{ __('Created At') }}</th>
                                            <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->description ?? '-' }}</td>
                                                <td>{{ $category->created_at->format('d M Y') }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
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
                                <i class="bi bi-folder-x fs-2"></i>
                                <p class="mt-2">{{ __('No categories found.') }}</p>
                                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                    {{ __('Add First Category') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Categories') }}</h2>
    </x-slot>

    <div class="container my-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('All Categories') }}</h5>
                    <a href="{{ url('categories.create') }}" class="btn btn-sm btn-primary">
                        + Add New
                    </a>
                </div>

               
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 35%">Name</th>
                                <th style="width: 40%">Description</th>
                                <th style="width: 20%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                    <div class="text-center py-4 text-muted">
                        <p class="mb-1">No categories found.</p>
                        <a href="{{ url('categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
