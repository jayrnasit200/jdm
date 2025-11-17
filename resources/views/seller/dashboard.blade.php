<x-app-layout>
    <div class="container my-5">
        @forelse($products as $categoryName => $subcategories)
            <div class="mb-5">
                {{-- Category Title --}}
                <h3 class="fw-bold text-dark mb-4 border-bottom pb-2">{{ $categoryName }}</h3>

                @foreach($subcategories as $subcategoryName => $items)
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">{{ $subcategoryName }}</h5>

                        {{-- Product Slider --}}
                        <div class="scroll-row d-flex overflow-auto pb-2">
                            @foreach($items as $product)
                                <div class="product-card card me-3 shadow-sm" >
                                    {{-- Product Image --}}
                                    {{-- <div class="img-wrapper">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" >
                                        @else
                                            <img src="https://via.placeholder.com/200x180?text=No+Image" alt="No Image">
                                        @endif
                                    </div> --}}
                                    <div class="img-wrapper">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <img src="https://via.placeholder.com/200x180?text=No+Image" alt="No Image">
                                            @endif
                                        </a>
                                    </div>

                                    <div class="card-body text-center p-2">
                                        <h6 class="fw-bold product-name" title="{{ $product->name }}">
                                            {{ Str::limit($product->name, 40) }}
                                        </h6>
                                        <small class="text-muted d-block">{{ $product->model_number }}</small>
                                        <div class="d-flex justify-content-center align-items-center mt-1">
                                            <span class="text-success fw-semibold me-1">£{{ number_format($product->price, 2) }}</span>
                                            @php
                                                if ($product->vat) {
                                                    $vatAmount = ($product->price * sys_config('vat')) / 100;
                                                    $priceWithVat = $product->price + $vatAmount;
                                                }else {
                                                    $priceWithVat = '';
                                                }
                                            @endphp
                                            <span class="text-muted small"> £{{$priceWithVat}}</span>
                                        </div>

                                        {{-- <span class="text-success fw-semibold d-block mt-1">£{{ number_format($product->price, 2) }}</span>
                                        <span class="text-muted d-block">withvat</span> --}}

                                        {{-- Add to Cart Section --}}
                                        <div class="mt-2 d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-outline-secondary minus-btn" data-id="{{ $product->id }}">−</button>
                                            <input type="text" id="qty-{{ $product->id }}" class="form-control form-control-sm mx-1 text-center" style="width: 40px;" value="1" readonly>
                                            <button class="btn btn-sm btn-outline-secondary plus-btn" data-id="{{ $product->id }}">+</button>
                                            <button class="btn btn-sm btn-primary m-1 add-to-cart-btn" data-id="{{ $product->id }}"><i class="fa fa-shopping-cart"></i></button>

                                        </div>
                                    </div>
                                </div>

                                {{-- Product Info Modal --}}
                                <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body row">
                                                <div class="col-md-5">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                                                    @else
                                                        <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid rounded" alt="No Image">
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <h5 class="fw-bold">{{ $product->name }}</h5>
                                                    <p class="text-muted">{{ $product->model_number }}</p>
                                                    <p>{{ $product->description ?? 'No description available.' }}</p>
                                                    <p><strong>Price:</strong> <span class="text-success fw-semibold">£{{ number_format($product->price, 2) }}</span></p>
                                                    <p><strong>VAT:</strong> {{ ucfirst($product->vat ?? 'N/A') }}</p>
                                                    <p><strong>Status:</strong> {{ ucfirst($product->status ?? 'Unavailable') }}</p>
                                                    @if($product->barcode)
                                                        <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
                                                    @endif
                                                    <div class="d-flex align-items-center mt-3">
                                                        <button class="btn btn-sm btn-outline-secondary minus-btn" data-id="{{ $product->id }}">−</button>
                                                        <input type="text" id="qty-modal-{{ $product->id }}" class="form-control form-control-sm mx-2 text-center" style="width: 40px;" value="1" readonly>
                                                        <button class="btn btn-sm btn-outline-secondary plus-btn" data-id="{{ $product->id }}">+</button>
                                                        <button class="btn btn-sm btn-primary ms-3 add-to-cart-btn" data-id="{{ $product->id }}">Add to Cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-center text-muted">No products found.</p>
        @endforelse
    </div>

    {{-- Styles --}}
    <style>
        .scroll-row { gap: 16px; }
        .scroll-row::-webkit-scrollbar { height: 8px; }
        .scroll-row::-webkit-scrollbar-thumb { background: #bbb; border-radius: 4px; }

        .product-card {
            min-width: 200px;
            max-width: 200px;
            flex: 0 0 auto;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }
        .product-card:hover { transform: scale(1.04); }

        .img-wrapper {
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .img-wrapper img:hover {
            transform: scale(1.05);
        }

        .product-name {
            font-size: 0.95rem;
            min-height: 40px;
            line-height: 1.2;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
        }

        .card-body {
            flex: 1;
        }

        .btn-outline-secondary {
            Pending: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .add-to-cart-btn {
            font-size: 0.8rem;
        }

        h3, h5 { text-transform: capitalize; }
    </style>

    {{-- JS for quantity controls --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Quantity +/- functionality
            document.querySelectorAll('.plus-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    let id = this.dataset.id;
                    let input = document.querySelector(`#qty-${id}`) || document.querySelector(`#qty-modal-${id}`);
                    input.value = parseInt(input.value) + 1;
                });
            });
            document.querySelectorAll('.minus-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    let id = this.dataset.id;
                    let input = document.querySelector(`#qty-${id}`) || document.querySelector(`#qty-modal-${id}`);
                    let val = parseInt(input.value);
                    if (val > 1) input.value = val - 1;
                });
            });

            // Add to Cart click (demo)
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    let id = this.dataset.id;
                    let qty = document.querySelector(`#qty-${id}`)?.value || document.querySelector(`#qty-modal-${id}`)?.value;
                    alert(`Added product ID ${id} (Qty: ${qty}) to cart!`);
                });
            });
        });
    </script>
</x-app-layout>
