<x-app-layout>
    <main class="container py-5">

        <!-- Header with View Cart Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="display-5 mb-0 text-center">Shop All Products</h1>
                <p class="lead text-center">Discover our full collection of modern, sustainable goods.</p>
            </div>
            <div class="text-end">
                <button id="viewCartBtn" class="btn btn-dark">
                    View Cart <span id="cartCount" class="badge bg-light text-dark">0</span>
                </button>
            </div>
        </div>

        <!-- Category Filters -->
        @php
            $categories = $products->pluck('category')->filter()->unique('id');
        @endphp
        <div class="mb-4 d-flex flex-wrap justify-content-center gap-2">
            <button data-filter="all" class="btn btn-primary filter-btn">All Products</button>
            @foreach($categories as $category)
                @if($category && $category->name)
                    <button data-filter="{{ $category->name }}" class="btn btn-outline-secondary filter-btn">
                        {{ ucfirst($category->name) }}
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Product Grid -->
        <div class="row" id="product-grid">
            @forelse ($products as $product)
                @php
                    $vatAmount = $product->vat ? ($product->price * sys_config('vat')) / 100 : 0;
                    $priceWithVat = $product->price + $vatAmount;
                    $productData = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $priceWithVat,
                        'shop_id' => $shopid ?? 1
                    ];
                @endphp
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 product-card" data-category="{{ $product->category->name ?? 'Uncategorized' }}">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Clickable Image to open modal -->
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                            class="card-img-top rounded-top product-detail-trigger"
                            style="height: 200px; object-fit: cover;"
                            alt="{{ $product->name }}"
                            data-bs-toggle="modal"
                            data-bs-target="#productModal{{ $product->id }}">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            @php
                                $vatRate = sys_config('vat') ?? 0; // VAT percentage
                                $vatAmount = $product->vat ? ($product->price * $vatRate) / 100 : 0;
                                $priceWithVat = $product->price + $vatAmount;
                            @endphp

                            <p class="card-text fw-bold mb-1">
                                £{{ number_format($product->price, 2) }}
                                <small class="text-muted"> (incl. VAT £{{ number_format($priceWithVat, 2) }})</small>
                            </p>

                            {{-- <p class="card-text fw-bold mb-1">£{{ number_format($priceWithVat, 2) }}</p> --}}

                            <div class="mt-auto d-flex justify-content-center align-items-center gap-2">
                                <button class="btn btn-outline-primary rounded-pill add-to-cart-btn"
                                        data-product='{{ json_encode($productData) }}'>Add to Cart</button>
                                <div class="d-none quantity-wrapper d-flex align-items-center gap-2">
                                    <button class="btn btn-outline-secondary btn btn-light text-dark rounded-circle decrement-btn">−</button>
                                    <input type="text" class="form-control text-center quantity-input p-1" value="1" style="width: 50px;" readonly>
                                    <button class="btn btn-outline-secondary btn btn-light text-dark rounded-circle increment-btn">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Detail Modal -->
                <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body row">
                                <div class="col-md-5">
                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                        class="img-fluid rounded" alt="{{ $product->name }}">
                                </div>
                                <div class="col-md-7">
                                    <h5 class="fw-bold">{{ $product->name }}</h5>
                                    <p class="text-muted mb-2">{{ $product->model_number ?? '' }}</p>
                                    <p>{{ $product->description ?? 'No description available.' }}</p>

                                    @php
                                    $vatRate = sys_config('vat') ?? 0; // VAT percentage
                                    $vatAmount = $product->vat ? ($product->price * $vatRate) / 100 : 0;
                                    $priceWithVat = $product->price + $vatAmount;
                                @endphp


                                 <p><strong>Price:</strong> <span class="text-success fw-semibold">£{{ number_format($priceWithVat, 2) }}</span></p>
                                    <p><strong>VAT:</strong> £{{ number_format($priceWithVat, 2) }} </p>
                                    <p><strong>Status:</strong> {{ ucfirst($product->status ?? 'Unavailable') }}</p>
                                    @if($product->barcode)
                                        <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
                                    @endif
                                    <button class="btn btn-outline-primary w-100 add-to-cart-btn mt-2"
                                            data-product='{{ json_encode($productData) }}'>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-12 text-center text-muted">No products available.</p>
            @endforelse
        </div>
    </main>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems"></div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total:</h5>
                        <h5 id="cartTotal">£0.00</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ url('cart.checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shopId = "{{ $shopid ?? 1 }}"; // current shop session ID
            const cartKey = `cart_${shopId}`; // separate cart for each shop
            const cartCountEl = document.getElementById('cartCount');
            const cartItemsContainer = document.getElementById('cartItems');
            const cartTotalEl = document.getElementById('cartTotal');
            const viewCartBtn = document.getElementById('viewCartBtn');

            // 🔹 Load Cart
            const getCart = () => JSON.parse(localStorage.getItem(cartKey)) || {};

            // 🔹 Save Cart
            const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

            // 🔹 Update Cart Count
            const updateCartCount = () => {
                const cart = getCart();
                let count = 0;
                for (let productId in cart) count += cart[productId].quantity;
                cartCountEl.textContent = count;
            };

            // 🔹 Product Cards Setup
            const setupProductCards = () => {
                document.querySelectorAll('.product-card').forEach(card => {
                    const addBtn = card.querySelector('.add-to-cart-btn');
                    const qtyWrapper = card.querySelector('.quantity-wrapper');
                    const decrementBtn = card.querySelector('.decrement-btn');
                    const incrementBtn = card.querySelector('.increment-btn');
                    const qtyInput = card.querySelector('.quantity-input');
                    const product = JSON.parse(addBtn.dataset.product);

                    const cart = getCart();
                    if(cart[product.id]) {
                        addBtn.classList.add('d-none');
                        qtyWrapper.classList.remove('d-none');
                        qtyInput.value = cart[product.id].quantity;
                    }

                    addBtn.addEventListener('click', () => {
                        const cart = getCart();
                        cart[product.id] = {...product, quantity: 1};
                        saveCart(cart);
                        addBtn.classList.add('d-none');
                        qtyWrapper.classList.remove('d-none');
                        qtyInput.value = 1;
                        updateCartCount();
                    });

                    incrementBtn.addEventListener('click', () => {
                        const cart = getCart();
                        cart[product.id].quantity += 1;
                        saveCart(cart);
                        qtyInput.value = cart[product.id].quantity;
                        updateCartCount();
                    });

                    decrementBtn.addEventListener('click', () => {
                        const cart = getCart();
                        if(cart[product.id].quantity > 1) {
                            cart[product.id].quantity -= 1;
                            saveCart(cart);
                            qtyInput.value = cart[product.id].quantity;
                        } else {
                            delete cart[product.id];
                            saveCart(cart);
                            card.querySelector('.add-to-cart-btn').classList.remove('d-none');
                            card.querySelector('.quantity-wrapper').classList.add('d-none');
                        }
                        updateCartCount();
                    });
                });
            };

            // 🔹 Render Cart Modal (shop-specific)
            const renderCart = () => {
                const cart = getCart();
                let html = '', total = 0;

                if(Object.keys(cart).length === 0) {
                    html = '<p class="text-center text-muted">Your cart is empty.</p>';
                } else {
                    html += `<table class="table table-bordered mb-3">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th></tr></thead><tbody>`;
                    for(let productId in cart){
                        const item = cart[productId];
                        const subtotal = Number(item.price) * item.quantity;
                        total += subtotal;
                        html += `<tr>
                            <td>${item.name}</td>
                            <td>£${Number(item.price).toFixed(2)}</td>
                            <td>${item.quantity}</td>
                            <td>£${subtotal.toFixed(2)}</td>
                            <td><button class="btn btn-sm btn-danger remove-cart-item" data-product="${productId}">Remove</button></td>
                        </tr>`;
                    }
                    html += `</tbody></table>`;
                }

                cartItemsContainer.innerHTML = html;
                cartTotalEl.textContent = `£${total.toFixed(2)}`;

                // Remove item event
                document.querySelectorAll('.remove-cart-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const cart = getCart();
                        delete cart[btn.dataset.product];
                        saveCart(cart);
                        renderCart();
                        updateCartCount();
                        setupProductCards();
                    });
                });
            };

            setupProductCards();
            updateCartCount();

            viewCartBtn.addEventListener('click', () => {
                renderCart();
                new bootstrap.Modal(document.getElementById('cartModal')).show();
            });
        });
        </script>

    </x-app-layout>
