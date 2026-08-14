@extends('layouts.owner')

@section('title', 'Add Product')
@section('page_title', 'Add New Product')
@section('page_subtitle', 'Create a product for the catalogue.')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-soft border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                        <h6 class="mb-0">Product details</h6>
                        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary btn-sm">
                            ← All Products
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small">Model Number</label>
                            <input type="text" name="model_number"
                                   class="form-control form-control-sm @error('model_number') is-invalid @enderror"
                                   value="{{ old('model_number') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Name</label>
                            <input type="text" name="name"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Category</label>
                            <select name="categories_id" id="category" class="form-control form-control-sm select2" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Subcategory</label>
                            <select name="subcategories_id" id="subcategory" class="form-control form-control-sm select2">
                                <option value="">-- Select Subcategory --</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('subcategories_id') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Main Image</label>
                            <input type="file" name="image" class="form-control form-control-sm" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Back Image</label>
                            <input type="file" name="backimage" class="form-control form-control-sm" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Nutrition Image</label>
                            <input type="file" name="nutritionimage" class="form-control form-control-sm" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Barcode</label>
                            <input type="text" name="barcode" class="form-control form-control-sm" value="{{ old('barcode') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Price</label>
                            <input type="text" name="price"
                                   class="form-control form-control-sm @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">VAT</label>
                                <select name="vat" class="form-control form-control-sm">
                                    <option value="yes" {{ old('vat') == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('vat', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="enable" {{ old('status', 'enable') == 'enable' ? 'selected' : '' }}>Enable</option>
                                    <option value="disable" {{ old('status') == 'disable' ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Special Offer</label>
                                <select name="special_offer" class="form-control form-control-sm">
                                    <option value="no" {{ old('special_offer', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="yes" {{ old('special_offer') == 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-sm">Add Product</button>
                            <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true,
            width: '100%'
        });

        $('#category').on('change', function () {
            var categoryId = $(this).val();
            $('#subcategory').html('<option value="">-- Select Subcategory --</option>').trigger('change');

            if (!categoryId) {
                return;
            }

            $.ajax({
                url: '{{ url('/owner/products/subcategories') }}/' + categoryId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        $.each(data, function (key, subcategory) {
                            $('#subcategory').append(
                                '<option value="' + subcategory.id + '">' + subcategory.name + '</option>'
                            );
                        });
                    } else {
                        $('#subcategory').append('<option value="">No subcategories found</option>');
                    }
                }
            });
        });
    });
</script>
@endpush
