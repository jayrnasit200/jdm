@extends('layouts.owner')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update product details in the catalogue.')

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
                        <h6 class="mb-0 text-break">{{ $product->name }}</h6>
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

                    <form action="{{ route('owner.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small">Model Number</label>
                            <input type="text" name="model_number"
                                   class="form-control form-control-sm @error('model_number') is-invalid @enderror"
                                   value="{{ old('model_number', $product->model_number) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Name</label>
                            <input type="text" name="name"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Category</label>
                            <select name="categories_id" id="category" class="form-control form-control-sm" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected((string) old('categories_id', $product->categories_id) === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Subcategory</label>
                            <select name="subcategories_id" id="subcategory" class="form-control form-control-sm">
                                <option value="">-- Select Subcategory --</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        @selected((string) old('subcategories_id', $product->subcategories_id) === (string) $subcategory->id)>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>

                        @foreach(['image' => 'Main', 'backimage' => 'Back', 'nutritionimage' => 'Nutrition'] as $field => $label)
                            <div class="mb-3">
                                <label class="form-label small">Current {{ $label }} Image</label>
                                @if($product->$field)
                                    <div class="mb-2">
                                        <img src="{{ media_url($product->$field) }}"
                                             alt="{{ $label }} Image"
                                             class="img-thumbnail"
                                             width="120">
                                    </div>
                                @else
                                    <div class="text-muted small mb-2">No image uploaded.</div>
                                @endif
                                <label class="form-label small">Change {{ $label }} Image</label>
                                <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*">
                            </div>
                        @endforeach

                        <div class="mb-3">
                            <label class="form-label small">Barcode</label>
                            <input type="text" name="barcode" class="form-control form-control-sm"
                                   value="{{ old('barcode', $product->barcode) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Price</label>
                            <input type="text" name="price"
                                   class="form-control form-control-sm @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">VAT</label>
                                <select name="vat" class="form-control form-control-sm">
                                    <option value="yes" {{ old('vat', $product->vat) == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('vat', $product->vat) == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="enable" {{ old('status', $product->status) == 'enable' ? 'selected' : '' }}>Enable</option>
                                    <option value="disable" {{ old('status', $product->status) == 'disable' ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Special Offer</label>
                                <select name="special_offer" class="form-control form-control-sm">
                                    <option value="no" {{ old('special_offer', $product->special_offer) == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="yes" {{ old('special_offer', $product->special_offer) == 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-sm">Update Product</button>
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
        var selectedCategory = @json(old('categories_id', $product->categories_id));
        var selectedSubcategory = @json(old('subcategories_id', $product->subcategories_id));
        var subcategoriesUrl = @json(url('/owner/products/subcategories'));

        function initSelect2($el, placeholder) {
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }

        function loadSubcategories(categoryId, selectedId) {
            var $sub = $('#subcategory');
            $sub.html('<option value="">-- Select Subcategory --</option>');

            if (!categoryId) {
                initSelect2($sub, 'Select subcategory');
                return;
            }

            $.getJSON(subcategoriesUrl + '/' + categoryId)
                .done(function (data) {
                    $.each(data, function (_, subcategory) {
                        var isSelected = String(subcategory.id) === String(selectedId || '');
                        $sub.append(new Option(subcategory.name, subcategory.id, isSelected, isSelected));
                    });
                    initSelect2($sub, 'Select subcategory');
                    if (selectedId) {
                        $sub.val(String(selectedId)).trigger('change.select2');
                    }
                })
                .fail(function () {
                    initSelect2($sub, 'Select subcategory');
                });
        }

        initSelect2($('#category'), 'Select category');
        if (selectedCategory) {
            $('#category').val(String(selectedCategory)).trigger('change.select2');
        }
        loadSubcategories(selectedCategory, selectedSubcategory);

        $('#category').on('change', function () {
            loadSubcategories($(this).val(), null);
        });
    });
</script>
@endpush
