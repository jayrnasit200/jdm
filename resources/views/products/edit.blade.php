<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Edit Product') }}</h2>
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
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ old('id', $product->id) }}">

                            {{-- Model Number --}}
                            <div class="mb-3">
                                <label class="form-label">Model Number</label>
                                <input type="text" name="model_number" class="form-control" value="{{ old('model_number', $product->model_number) }}" required>
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                            </div>

                            {{-- Category --}}
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="categories_id" id="category" class="form-control select2" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Subcategory --}}
                            <div class="mb-3">
                                <label class="form-label">Subcategory</label>
                                <select name="subcategories_id" id="subcategory" class="form-control select2" required>
                                    <option value="">-- Select Subcategory --</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" 
                                            {{ old('subcategories_id', $product->subcategories_id) == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                            </div>

                            {{-- Images --}}
                            @foreach(['image' => 'Main', 'backimage' => 'Back', 'nutritionimage' => 'Nutrition'] as $field => $label)
                                <div class="mb-3">
                                    <label class="form-label">Current {{ $label }} Image</label>
                                    @if($product->$field)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$product->$field) }}" alt="{{ $label }} Image" class="img-thumbnail" width="120">
                                        </div>
                                    @endif
                                    <label class="form-label">Change {{ $label }} Image</label>
                                    <input type="file" name="{{ $field }}" class="form-control">
                                </div>
                            @endforeach

                            {{-- Barcode --}}
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                            </div>

                            {{-- Price --}}
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            </div>

                            {{-- VAT --}}
                            <div class="mb-3">
                                <label class="form-label">VAT</label>
                                <select name="vat" class="form-control">
                                    <option value="yes" {{ old('vat', $product->vat) == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('vat', $product->vat) == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="enable" {{ old('status', $product->status) == 'enable' ? 'selected' : '' }}>Enable</option>
                                    <option value="disable" {{ old('status', $product->status) == 'disable' ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>

                            {{-- Special Offer --}}
                            <div class="mb-3">
                                <label class="form-label">Special Offer</label>
                                <select name="special_offer" class="form-control">
                                    <option value="no" {{ old('special_offer', $product->special_offer) == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="yes" {{ old('special_offer', $product->special_offer) == 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    placeholder: "Select an option",
                    allowClear: true,
                    width: '100%'
                });

                // Load subcategories when category changes
                $('#category').on('change', function() {
                    var categoryId = $(this).val();
                    $('#subcategory').html('<option value="">-- Select Subcategory --</option>');

                    if(categoryId) {
                        $.ajax({
                            url: '/get-subcategories/' + categoryId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $.each(data, function(key, subcategory) {
                                    var selected = subcategory.id == "{{ old('subcategories_id', $product->subcategories_id) }}" ? 'selected' : '';
                                    $('#subcategory').append('<option value="' + subcategory.id + '" '+selected+'>' + subcategory.name + '</option>');
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error);
                            }
                        });
                    }
                });

                // Trigger change on page load to populate subcategories for existing category
                $('#category').trigger('change');
            });
        </script>
    @endpush
</x-app-layout>
