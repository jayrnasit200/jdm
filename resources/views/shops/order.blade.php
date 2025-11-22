<x-app-layout>

    {{-- Page styles --}}
    <style>
        body {
            background: #f3f4f6;
        }

        /* Hero with US flag in background */
        .shop-hero {
            position: relative;
            border-radius: 1rem;
            padding: 1.5rem 2rem;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.25);
            overflow: hidden;

            /* Flag + dark blue overlay */
            background-image:
                linear-gradient(90deg, rgba(11, 60, 93, 0.94), rgba(249, 99, 99, 0.94)),
                url("https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
        }

        .shop-hero::after {
            content: "";
            position: absolute;
            right: -40px;
            top: -40px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle at center, rgba(255,255,255,0.2), transparent 60%);
            opacity: 0.9;
        }

        .shop-hero-title {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .shop-hero-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .filter-pill {
            border-radius: 999px;
            padding-inline: 1rem;
            padding-block: 0.4rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .filter-btn.active {
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .product-card .card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            border-radius: 1rem;
        }

        .product-card .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.18);
        }

        .product-badge-offer {
            background: linear-gradient(135deg, #dc2626, #f97316);
            color: #fff;
            font-size: 0.7rem;
            border-radius: 999px;
            padding: 0.25rem 0.6rem;
        }

        .product-price {
            font-size: 1rem;
        }

        .product-code {
            font-size: 0.75rem;
            letter-spacing: 0.03em;
        }

        .cart-button-pill {
            border-radius: 999px;
            padding-inline: 1.4rem;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25);
        }

        .cart-summary-text {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .modal-header-gradient {
            background: linear-gradient(135deg, #111827, #1f2937);
            color: #ffffff;
        }

        @media (max-width: 576px) {
            .shop-hero {
                padding: 1.2rem 1.3rem;
            }

            .shop-hero-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <main class="container py-4">

        {{-- Top bar: search + cart --}}
        <div class="row align-items-center mb-3 g-2">
            <!-- Search input -->
            <div class="col-12 col-md-4">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text"
                           id="productSearch"
                           class="form-control border-start-0"
                           placeholder="Search products or categories...">
                </div>
            </div>

            <!-- Spacer -->
            <div class="col-12 col-md-2"></div>

            <!-- Cart button -->
            <div class="col-12 col-md-6 d-flex justify-content-md-end justify-content-start">
                <div class="d-flex flex-column align-items-md-end align-items-start w-100 w-md-auto">
                    <button id="viewCartBtn" class="btn btn-dark cart-button-pill d-flex align-items-center gap-2">
                        <span>🛒 View Cart</span>
                        <span id="cartCount" class="badge bg-light text-dark ms-1">0</span>
                    </button>
                    <span class="cart-summary-text mt-1">
                        Review your selection and proceed to checkout when ready.
                    </span>
                </div>
            </div>
        </div>

        {{-- Hero section with US flag background --}}
        <div class="shop-hero mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="shop-hero-title mb-1">
                        Shop Products
                    </div>
                    <div class="shop-hero-subtitle">
                        Explore our full wholesale range, seasonal lines, and special offers – all in one place.
                    </div>
                </div>
                {{-- <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">
                        <i class="fa fa-truck-fast me-1"></i> Next delivery slot subject to availability
                    </span>
                </div> --}}
            </div>
        </div>

        <!-- Category Filter Buttons -->
        @php
            $categories = $products->pluck('category')->filter()->unique('id');
            $vatRate    = sys_config('vat') ?? 0;
        @endphp
        <div class="mb-4 d-flex flex-wrap justify-content-center gap-2">
            <button data-filter="all" class="btn btn-primary filter-btn filter-pill active">
                All Products
            </button>
            <button data-filter="offer" class="btn btn-outline-danger filter-btn filter-pill">
                🔥 Offers
            </button>
            @foreach($categories as $category)
                @if($category && $category->name)
                    <button data-filter="{{ $category->name }}"
                            class="btn btn-outline-secondary filter-btn filter-pill">
                        {{ ucfirst($category->name) }}
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Product Grid -->
        <div id="product-grid" class="row g-4">
            @foreach ($products as $product)
                @php
                    $includeVat   = $product->vat === 'yes';       // "yes" / "no"
                    $priceForCart = $product->price;               // ex-VAT

                    $productData = [
                        'id'            => $product->id,
                        'name'          => $product->name,
                        'price'         => $priceForCart,                       // ex-VAT
                        'shop_id'       => $shopid ?? 1,
                        'category'      => $product->category->name ?? 'Uncategorized',
                        'special_offer' => $product->special_offer,
                        'status'        => $product->status,
                        'vat'           => $product->vat,                       // "yes" or "no"
                        'vat_rate'      => $includeVat ? $vatRate : 0,         // e.g. 20 or 0
                    ];
                @endphp

                <div class="col-6 col-md-4 col-lg-3 product-card"
                     data-category="{{ $product->category->name ?? 'Uncategorized' }}"
                     data-offer="{{ $product->special_offer ? '1' : '0' }}"
                     data-status="{{ $product->status ?? 'active' }}"
                     data-code="{{ $product->product_code ?? '' }}">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden position-relative">

                        <!-- Offer Label -->
                        @if($product->special_offer == "yes")
                            <span class="position-absolute top-0 end-0 m-2 product-badge-offer">
                                🔥 Offer
                            </span>
                        @endif

                        <!-- Clickable Image (opens modal) -->
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://media.licdn.com/dms/image/v2/D4D0BAQH6Mvw_HQhbtg/company-logo_200_200/B4DZXHz0dTH4AI-/0/1742814005705/jdm_distributors_logo?e=2147483647&v=beta&t=w9nO0U2WNKxgnvJKZgVaEDsOoELjbbix2y_6NeSOh5o' }}"
                             class="card-img-top product-detail-trigger"
                             style="height:180px;object-fit:cover;cursor:pointer"
                             alt="{{ $product->name }}"
                             data-bs-toggle="modal"
                             data-bs-target="#productModal{{ $product->id }}">

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-truncate mb-1">{{ $product->name }}</h6>

                            @if(!empty($product->model_number))
                                <small class="text-muted d-block mb-2 product-code">
                                    Code: {{ $product->model_number }}
                                </small>
                            @endif

                            <p class="card-text fw-bold mb-2 product-price">
                                £{{ number_format($product->price, 2) }}
                                @if ($product->vat == "yes")
                                    <small class="text-muted d-block">+ VAT</small>
                                @endif
                            </p>

                            <div class="mt-auto d-flex justify-content-center align-items-center gap-2">
                                @if($product->status !== 'disable')
                                    <button class="btn btn-outline-primary rounded-pill add-to-cart-btn px-3 py-1"
                                            data-product='@json($productData)'>
                                        Add to Cart
                                    </button>
                                    <div class="d-none quantity-wrapper d-flex align-items-center gap-1">
                                        <button class="btn btn-outline-secondary btn-sm rounded-circle decrement-btn">−</button>
                                        <input type="text"
                                               class="form-control text-center quantity-input p-1"
                                               value="1"
                                               style="width:50px"
                                               readonly>
                                        <button class="btn btn-outline-secondary btn-sm rounded-circle increment-btn">+</button>
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
                            <div class="modal-header modal-header-gradient rounded-top-4">
                                <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body row">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                         class="img-fluid rounded"
                                         alt="{{ $product->name }}">
                                </div>
                                <div class="col-md-7">
                                    <h5 class="fw-bold">{{ $product->name }}</h5>
                                    @if(!empty($product->model_number))
                                        <p class="text-muted mb-1">Code: {{ $product->model_number }}</p>
                                    @endif
                                    <p class="mb-2">{{ $product->description ?? 'No description available.' }}</p>
                                    <p class="mb-1">
                                        <strong>Price:</strong>
                                        £{{ number_format($product->price, 2) }}
                                        @if($includeVat)
                                            <small class="text-muted">(VAT to be added)</small>
                                        @endif
                                    </p>
                                    @if($includeVat)
                                        <p class="mb-1"><strong>VAT Rate:</strong> {{ $vatRate }}%</p>
                                    @endif
                                    <p class="mb-3"><strong>Status:</strong> {{ ucfirst($product->status ?? 'Available') }}</p>

                                    <button class="btn btn-outline-primary w-100 mt-1 add-to-cart-btn"
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
                <div class="modal-header modal-header-gradient rounded-top-4">
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
                        Proceed to Checkout
                    </a>
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
                    html = '<p class="text-center text-muted mb-0">Your cart is empty.</p>';
                } else {
                    html = `<table class="table table-sm align-middle mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
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
                            <td class="text-end">£${price.toFixed(2)}</td>
                            <td class="text-center">${quantity}</td>
                            <td class="text-end">£${subtotal.toFixed(2)}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-danger remove-item" data-id="${id}">
                                    ✕
                                </button>
                            </td>
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
