<x-app-layout>

    <style>
        .price-page-wrapper {
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .price-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
        }
        .price-card-header {
            background:
                linear-gradient(135deg, rgba(11,60,93,0.92), rgba(243,82,82,0.92)),
                url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg');
            background-size: cover;
            background-position: center right;
            color: #ffffff;
            padding: 16px 20px;
            border-radius: 1rem 1rem 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-card-header h4 {
            margin: 0;
            font-weight: 600;
        }
    </style>

    <div class="container price-page-wrapper">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MAIN CARD: Form --}}
        <div class="card price-card mb-4">
            <div class="price-card-header">
                <div>
                    <h4>Shop Specific Prices</h4>
                    <small>Set custom prices for a shop. These override the default product price.</small>
                </div>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-light btn-sm">
                    ← Back
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('seller.shop-prices.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label">Shop</label>
                        <select name="shop_id" class="form-select select2" required>
                            <option value="">Select shop...</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}"
                                    {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->ref }} - {{ $shop->shopname }}
                                </option>
                            @endforeach
                        </select>
                        @error('shop_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-select select2" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->model_number }} | {{ $product->name }}
                                    (£{{ number_format($product->price,2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Special Price (£)</label>
                        <input type="number"
                               name="price"
                               class="form-control"
                               step="0.01"
                               min="0"
                               value="{{ old('price') }}"
                               required>
                        @error('price')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary rounded-pill px-4">
                            Save Price
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE: Existing shop/product prices --}}
        <div class="card price-card">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center"
                 style="border-radius:1rem 1rem 0 0;">
                <h5 class="mb-0">Existing Shop Prices</h5>
                <small class="text-muted">Each row is unique per Shop + Product. Saving again will update the price.</small>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Shop</th>
                                <th>Product</th>
                                <th>Special Price (£)</th>
                                <th>Default Price (£)</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prices as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
                                        {{ $row->shop->ref ?? 'N/A' }} -
                                        {{ $row->shop->shopname ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $row->product->name ?? 'N/A' }}<br>
                                        @if(!empty($row->product->model_number))
                                            <small class="text-muted">Code: {{ $row->product->model_number }}</small>
                                        @endif
                                    </td>
                                    <td>£{{ number_format($row->price, 2) }}</td>
                                    <td>
                                        @if($row->product)
                                            £{{ number_format($row->product->price, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $row->updated_at?->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No special prices set yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Select...',
                    allowClear: true
                });
            });
        </script>
    @endpush

</x-app-layout>
