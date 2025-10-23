<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Add New Product') }}</h2>
    </x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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

                        {{-- Product Form --}}
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Model Number --}}
                            <div class="mb-3">
                                <label class="form-label">Model Number</label>
                                <input type="text" name="model_number" class="form-control" value="{{ old('model_number') }}" required>
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                        {{-- Category --}}
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="categories_id" id="category" class="form-control select2" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Subcategory --}}
                            <div class="mb-3">
                                <label class="form-label">Subcategory</label>
                                <select name="subcategories_id" id="subcategory" class="form-control select2">
                                    <option value="">-- Select Subcategory --</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ old('subcategories_id') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            </div>

                            {{-- Main Image --}}
                            <div class="mb-3">
                                <label class="form-label">Main Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            {{-- Back Image --}}
                            <div class="mb-3">
                                <label class="form-label">Back Image</label>
                                <input type="file" name="backimage" class="form-control">
                            </div>

                            {{-- Nutrition Image --}}
                            <div class="mb-3">
                                <label class="form-label">Nutrition Image</label>
                                <input type="file" name="nutritionimage" class="form-control">
                            </div>

                            {{-- Barcode --}}
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                            </div>

                            {{-- Price --}}
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>

                            {{-- VAT --}}
                            <div class="mb-3">
                                <label class="form-label">VAT</label>
                                <select name="vat" class="form-control">
                                    <option value="yes" {{ old('vat') == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('vat') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="enable" {{ old('status') == 'enable' ? 'selected' : '' }}>Enable</option>
                                    <option value="disable" {{ old('status') == 'disable' ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>

                            {{-- Special Offer --}}
                            <div class="mb-3">
                                <label class="form-label">Special Offer</label>
                                <select name="special_offer" class="form-control">
                                    <option value="no" {{ old('special_offer') == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="yes" {{ old('special_offer') == 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        $('#category').on('change', function() {
            var categoryId = $(this).val();
    
            // Clear previous options
            $('#subcategory').html('<option value="">-- Select Subcategory --</option>');
    
            if (categoryId) {
                $.ajax({
                    url: "/get-subcategories/" + categoryId, // ✅ fixed line
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(key, subcategory) {
                                $('#subcategory').append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                            });
                        } else {
                            $('#subcategory').append('<option value="">No subcategories found</option>');
                        }
                    }
                });
            }
        });
    });
    </script>
    @endpush
    


</x-app-layout>
