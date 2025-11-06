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
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                            class="card-img-top rounded-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text fw-bold mb-1">£{{ number_format($priceWithVat, 2) }}</p>

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
        const cartCountEl = document.getElementById('cartCount');
        const cartItemsContainer = document.getElementById('cartItems');
        const cartTotalEl = document.getElementById('cartTotal');
        const viewCartBtn = document.getElementById('viewCartBtn');

        // Filter products
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.dataset.filter;
                document.querySelectorAll('.product-card').forEach(card => {
                    card.style.display = (category === 'all' || card.dataset.category === category) ? 'block' : 'none';
                });
                document.querySelectorAll('.filter-btn').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-outline-secondary'); });
                btn.classList.add('btn-primary'); btn.classList.remove('btn-outline-secondary');
            });
        });

        // Update header cart count
        const updateCartCount = () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || {};
            let count = 0;
            for (let shopId in cart) {
                for (let productId in cart[shopId]) {
                    count += cart[shopId][productId].quantity;
                }
            }
            cartCountEl.textContent = count;
        }

        // Setup product cards
        const setupProductCards = () => {
            document.querySelectorAll('.product-card').forEach(card => {
                const addBtn = card.querySelector('.add-to-cart-btn');
                const qtyWrapper = card.querySelector('.quantity-wrapper');
                const decrementBtn = card.querySelector('.decrement-btn');
                const incrementBtn = card.querySelector('.increment-btn');
                const qtyInput = card.querySelector('.quantity-input');
                const product = JSON.parse(addBtn.dataset.product);

                const cart = JSON.parse(localStorage.getItem('cart')) || {};
                const shopCart = cart[product.shop_id] || {};
                if(shopCart[product.id]) {
                    addBtn.classList.add('d-none');
                    qtyWrapper.classList.remove('d-none');
                    qtyInput.value = shopCart[product.id].quantity;
                }

                addBtn.addEventListener('click', () => {
                    cart[product.shop_id] = cart[product.shop_id] || {};
                    cart[product.shop_id][product.id] = {...product, quantity: 1};
                    localStorage.setItem('cart', JSON.stringify(cart));
                    addBtn.classList.add('d-none');
                    qtyWrapper.classList.remove('d-none');
                    qtyInput.value = 1;
                    updateCartCount();
                });

                incrementBtn.addEventListener('click', () => {
                    const cart = JSON.parse(localStorage.getItem('cart'));
                    cart[product.shop_id][product.id].quantity += 1;
                    qtyInput.value = cart[product.shop_id][product.id].quantity;
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                });

                decrementBtn.addEventListener('click', () => {
                    const cart = JSON.parse(localStorage.getItem('cart'));
                    if(cart[product.shop_id][product.id].quantity > 1) {
                        cart[product.shop_id][product.id].quantity -= 1;
                        qtyInput.value = cart[product.shop_id][product.id].quantity;
                    } else {
                        delete cart[product.shop_id][product.id];
                        card.querySelector('.add-to-cart-btn').classList.remove('d-none');
                        card.querySelector('.quantity-wrapper').classList.add('d-none');
                    }
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                });
            });
        }

        // Render cart modal
        const renderCart = () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || {};
            let html = '';
            let total = 0;
            if(Object.keys(cart).length === 0) {
                html = '<p class="text-center text-muted">Your cart is empty.</p>';
            } else {
                for(let shopId in cart){
                    html += `<h5 class="mb-2">Shop ID: ${shopId}</h5>`;
                    html += `<table class="table table-bordered mb-3">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    for(let productId in cart[shopId]){
                        const item = cart[shopId][productId];
                        const subtotal = Number(item.price) * item.quantity;
                        total += subtotal;
                        html += `<tr>
                            <td>${item.name}</td>
                            <td>£${Number(item.price).toFixed(2)}</td>
                            <td>${item.quantity}</td>
                            <td>£${subtotal.toFixed(2)}</td>
                            <td><button class="btn btn-sm btn-danger remove-cart-item" data-shop="${shopId}" data-product="${productId}">Remove</button></td>
                        </tr>`;
                    }
                    html += `</tbody></table>`;
                }
            }
            cartItemsContainer.innerHTML = html;
            cartTotalEl.textContent = `£${total.toFixed(2)}`;

            document.querySelectorAll('.remove-cart-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    const cart = JSON.parse(localStorage.getItem('cart'));
                    const shop = btn.dataset.shop;
                    const prod = btn.dataset.product;
                    delete cart[shop][prod];
                    if(Object.keys(cart[shop]).length === 0) delete cart[shop];
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                    updateCartCount();
                    setupProductCards(); // refresh product card buttons
                });
            });
        }

        setupProductCards();
        updateCartCount();

        viewCartBtn.addEventListener('click', () => {
            renderCart();
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        });
    });
    </script>
    </x-app-layout>
