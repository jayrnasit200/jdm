<x-app-layout>
    <main class="container py-5">
        <div class="row mb-3">
            <!-- Search input on the left -->
            <div class="col-12 col-md-3 mb-2 mb-md-0 d-flex justify-content-start">
                <input type="text" id="productSearch" class="form-control w-100" placeholder="Search products...">
            </div>
            <div class="col-12 col-md-3 mb-2 mb-md-0 d-flex justify-content-start">
            </div>
            <!-- Cart button on the right -->
            <div class="col-12 col-md-6 d-flex justify-content-md-end">
                <button id="viewCartBtn" class="btn btn-dark rounded-pill">
                    🛒 View Cart <span id="cartCount" class="badge bg-light text-dark">0</span>
                </button>
            </div>
        </div>

        <!-- Header with View Cart Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="text-center flex-grow-1">
                <h1 class="display-5 mb-0">Shop Products</h1>
                <p class="lead mb-0">Explore our full range of goods and special offers</p>
            </div>
        </div>

        <!-- Category Filter Buttons -->
        @php
            $categories = $products->pluck('category')->filter()->unique('id');
        @endphp
        <div class="mb-4 d-flex flex-wrap justify-content-center gap-2">
            <button data-filter="all" class="btn btn-primary filter-btn active">All Products</button>
            <button data-filter="offer" class="btn btn-outline-danger filter-btn">🔥 Offers</button>
            @foreach($categories as $category)
                @if($category && $category->name)
                    <button data-filter="{{ $category->name }}" class="btn btn-outline-secondary filter-btn">
                        {{ ucfirst($category->name) }}
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Product Grid -->
        <div id="product-grid" class="row g-4">
            @foreach ($products as $product)
                @php
                    $vatRate = sys_config('vat') ?? 0;
                    $includeVat = $product->vat === 'yes';

                    $vatAmount = $includeVat ? ($product->price * $vatRate) / 100 : 0;
                    $priceWithVat = $product->price + $vatAmount;

                    // price that will go to cart
                    $priceForCart = $includeVat ? $priceWithVat : $product->price;

                    $productData = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $priceForCart,
                        'shop_id' => $shopid ?? 1,
                        'category' => $product->category->name ?? 'Uncategorized',
                        'special_offer' => $product->special_offer,
                        'status' => $product->status
                    ];
                @endphp

                <div class="col-6 col-md-3 col-lg-3 product-card"
     data-category="{{ $product->category->name ?? 'Uncategorized' }}"
     data-offer="{{ $product->special_offer ? '1' : '0' }}"
     data-status="{{ $product->status ?? 'active' }}"
     data-code="{{ $product->product_code ?? '' }}">   {{-- 👈 added --}}
    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden position-relative">

        <!-- Offer & Stock Labels -->
        @if($product->special_offer == "yes")
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">🔥 Offer</span>
        @endif

        <!-- Clickable Image (opens modal) -->
        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://media.licdn.com/dms/image/v2/D4D0BAQH6Mvw_HQhbtg/company-logo_200_200/B4DZXHz0dTH4AI-/0/1742814005705/jdm_distributors_logo?e=2147483647&v=beta&t=w9nO0U2WNKxgnvJKZgVaEDsOoELjbbix2y_6NeSOh5o' }}"
             class="card-img-top product-detail-trigger"
             style="height:180px;object-fit:cover;cursor:pointer"
             alt="{{ $product->name }}"
             data-bs-toggle="modal"
             data-bs-target="#productModal{{ $product->id }}">

        <div class="card-body d-flex flex-column">
            <h6 class="card-title text-truncate">{{ $product->name }}</h6>

            {{-- 👇 product code shown under name --}}
            @if(!empty($product->model_number))
                <small class="text-muted d-block mb-1">
                    Code: {{ $product->model_number }}
                </small>
            @endif

            <p class="card-text fw-bold mb-2">
                £{{ number_format($product->price, 2) }}
                @if ($product->vat == "yes")
                    <small class="text-muted d-block">+ VAT</small>
                @endif
            </p>

            <div class="mt-auto d-flex justify-content-center align-items-center gap-2">
                @if($product->status !== 'disable')
                    <button class="btn btn-outline-primary rounded-pill add-to-cart-btn"
                        data-product='@json($productData)'>Add to Cart</button>
                    <div class="d-none quantity-wrapper d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary rounded-circle decrement-btn">−</button>
                        <input type="text" class="form-control text-center quantity-input p-1" value="1" style="width:50px" readonly>
                        <button class="btn btn-outline-secondary rounded-circle increment-btn">+</button>
                    </div>
                @else
                    <span class="badge bg-warning text-dark">Out of Stock</span>
                @endif
            </div>
        </div>
    </div>
</div>


                <!-- Product Detail Modal -->
                <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4">
                            <div class="modal-header bg-dark text-white rounded-top-4">
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
                                    <p class="text-muted">{{ $product->model_number ?? '' }}</p>
                                    <p>{{ $product->description ?? 'No description available.' }}</p>
                                    <p>
                                        <strong>Price:</strong>
                                        @if($includeVat)
                                            £{{ number_format($priceWithVat, 2) }} (VAT Included)
                                        @else
                                            £{{ number_format($product->price, 2) }} (No VAT)
                                        @endif
                                    </p>
                                    @if($includeVat)
                                        <p><strong>VAT:</strong> {{ $vatRate }}% (Included)</p>
                                    @endif
                                    <p><strong>Status:</strong> {{ ucfirst($product->status ?? 'Available') }}</p>

                                    <button class="btn btn-outline-primary w-100 mt-2 add-to-cart-btn"
                                        data-product='@json($productData)'>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    </main>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title" id="cartModalLabel">🛍️ Your Cart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="cartItems"></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="cartTotal">£0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('checkout', ['shopid' => $shopid ?? 1]) }}" class="btn btn-primary rounded-pill">
                        Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shopId = "{{ $shopid ?? 1 }}";
            const cartKey = `cart_${shopId}`;
            const cartCountEl = document.getElementById('cartCount');
            const cartItemsContainer = document.getElementById('cartItems');
            const cartTotalEl = document.getElementById('cartTotal');
            const viewCartBtn = document.getElementById('viewCartBtn');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('productSearch');

            const getCart = () => {
                const stored = localStorage.getItem(cartKey);
                try {
                    return stored ? JSON.parse(stored) : {};
                } catch (e) {
                    return {};
                }
            };

            const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

            const updateCartCount = () => {
                const cart = getCart();
                let count = Object.values(cart).reduce((sum, p) => {
                    return sum + (Number(p.quantity) || 0);
                }, 0);
                cartCountEl.textContent = count;
            };

            const setupCards = () => {
                document.querySelectorAll('.product-card').forEach(card => {
                    const addBtn = card.querySelector('.add-to-cart-btn');
                    const qtyWrap = card.querySelector('.quantity-wrapper');
                    const inc = card.querySelector('.increment-btn');
                    const dec = card.querySelector('.decrement-btn');
                    const qty = card.querySelector('.quantity-input');
                    if (!addBtn) return;

                    const product = JSON.parse(addBtn.dataset.product);
                    const cart = getCart();

                    if (cart[product.id]) {
                        addBtn.classList.add('d-none');
                        if (qtyWrap) qtyWrap.classList.remove('d-none');
                        if (qty) qty.value = cart[product.id].quantity;
                    }

                    addBtn.onclick = () => {
                        const cart = getCart();
                        const price = typeof product.price === 'number'
                            ? product.price
                            : parseFloat(product.price) || 0;

                        cart[product.id] = {
                            ...product,
                            price: price,
                            quantity: 1
                        };

                        saveCart(cart);
                        addBtn.classList.add('d-none');
                        if (qtyWrap) qtyWrap.classList.remove('d-none');
                        if (qty) qty.value = 1;
                        updateCartCount();
                    };

                    if (inc) {
                        inc.onclick = () => {
                            const cart = getCart();
                            if (!cart[product.id]) return;
                            const currentQty = Number(cart[product.id].quantity) || 0;
                            cart[product.id].quantity = currentQty + 1;
                            saveCart(cart);
                            if (qty) qty.value = cart[product.id].quantity;
                            updateCartCount();
                        };
                    }

                    if (dec) {
                        dec.onclick = () => {
                            const cart = getCart();
                            if (!cart[product.id]) return;
                            const currentQty = Number(cart[product.id].quantity) || 0;

                            if (currentQty > 1) {
                                cart[product.id].quantity = currentQty - 1;
                                saveCart(cart);
                                if (qty) qty.value = cart[product.id].quantity;
                            } else {
                                delete cart[product.id];
                                saveCart(cart);
                                addBtn.classList.remove('d-none');
                                if (qtyWrap) qtyWrap.classList.add('d-none');
                            }
                            updateCartCount();
                        };
                    }
                });
            };

            const renderCart = () => {
                const cart = getCart();
                let html = '';
                let total = 0;

                const ids = Object.keys(cart);
                if (ids.length === 0) {
                    html = '<p class="text-center text-muted">Your cart is empty.</p>';
                } else {
                    html = `<table class="table align-middle mb-3">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>`;

                    ids.forEach(id => {
                        const item = cart[id];
                        const price = typeof item.price === 'number'
                            ? item.price
                            : parseFloat(item.price) || 0;
                        const quantity = Number(item.quantity) || 0;
                        const subtotal = price * quantity;
                        total += subtotal;

                        html += `<tr>
                            <td>${item.name}</td>
                            <td>£${price.toFixed(2)}</td>
                            <td>${quantity}</td>
                            <td>£${subtotal.toFixed(2)}</td>
                            <td><button class="btn btn-sm btn-danger remove-item" data-id="${id}">✕</button></td>
                        </tr>`;
                    });

                    html += '</tbody></table>';
                }

                cartItemsContainer.innerHTML = html;
                cartTotalEl.textContent = '£' + total.toFixed(2);

                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.onclick = () => {
                        const cart = getCart();
                        delete cart[btn.dataset.id];
                        saveCart(cart);
                        renderCart();
                        updateCartCount();
                        setupCards();
                    };
                });
            };

            const filterProducts = () => {
                const query = searchInput ? searchInput.value.toLowerCase() : '';
                const activeBtn = document.querySelector('.filter-btn.active');
                const activeFilter = activeBtn ? activeBtn.dataset.filter : 'all';

                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.querySelector('.card-title').textContent.toLowerCase();
                    const category = (card.dataset.category || '').toLowerCase();
                    const offer = card.dataset.offer;

                    const matchesSearch = name.includes(query) || category.includes(query);

                    let matchesFilter = true;
                    if (activeFilter === 'offer') {
                        matchesFilter = offer === '1';
                    } else if (activeFilter !== 'all') {
                        matchesFilter = category === activeFilter.toLowerCase();
                    }

                    card.style.display = matchesSearch && matchesFilter ? '' : 'none';
                });
            };

            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterButtons.forEach(b => b.classList.remove('active', 'btn-primary'));
                    btn.classList.add('active', 'btn-primary');
                    filterProducts();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', filterProducts);
            }

            setupCards();
            updateCartCount();

            viewCartBtn.onclick = () => {
                renderCart();
                const modalEl = document.getElementById('cartModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            };
        });
    </script>
</x-app-layout>
