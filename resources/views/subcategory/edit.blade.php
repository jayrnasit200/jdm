<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Edit Subcategory') }}</h2>
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

                        <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="categories_id" value="{{ $subcategory->categories_id }}">

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Subcategory Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name', $subcategory->name) }}" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">{{ __('Update Subcategory') }}</button>
                                <a href="{{ route('subcatogory', $subcategory->categories_id) }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
