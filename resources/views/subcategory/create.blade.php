<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Add New Subcategory') }}</h2>
    </x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('subcategories.store') }}" method="POST">
                            @csrf

                            {{-- Hidden field for parent category ID --}}
                            <input type="hidden" name="categories_id" value="{{ $catid }}">

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Subcategory Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                           
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('subcatogory', $catid) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Add Subcategory') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
