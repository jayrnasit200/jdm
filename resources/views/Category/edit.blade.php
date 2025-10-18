<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Edit Category') }}</h2>
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

                        <form action="{{ route('categories.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id"  class="form-control"  value="{{  $category->id }}" >

                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name', $category->name) }}" required>
                            </div>
                        
                        
                        
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <a href="{{ url('categorie') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
