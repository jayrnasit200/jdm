<x-app-layout>

    <style>
        body {
            background: #f3f4f6;
        }

        .checkout-hero {
            position: relative;
            border-radius: 1rem;
            padding: 1.2rem 1.6rem;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.25);
            overflow: hidden;
            margin-bottom: 1.5rem;

            /* 🇺🇸 Flag + red/blue gradient */
            background-image:
                linear-gradient(90deg,
                    rgba(11, 60, 93, 0.75),
                    rgba(243, 82, 82, 0.75)
                ),
                url("https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
        }

        .checkout-hero::after {
            content: "";
            position: absolute;
            right: -40px;
            top: -40px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle at center, rgba(255,255,255,0.22), transparent 60%);
        }

        .checkout-logo {
            width: 60px;
            height: 60px;
            border-radius: 999px;
            background: #ffffff;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(15,23,42,0.4);
        }

        .checkout-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .checkout-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .checkout-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .step-indicator {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .step-indicator span {
            opacity: 0.7;
        }

        .step-indicator .active-step {
            opacity: 1;
            color: #ffffff;
        }

        .step-indicator .divider {
            opacity: 0.5;
            margin: 0 0.35rem;
        }

        .checkout-card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 8px 24px rgba(15,23,42,0.12);
        }

        .checkout-card-header {
            border-radius: 1rem 1rem 0 0 !important;
            background: linear-gradient(90deg, #0f172a, #1f2937);
            color: #ffffff;
        }

        .checkout-total-text {
            font-size: 0.9rem;
            color: #4b5563;
        }

        .checkout-total-amount {
            font-size: 1.25rem;
        }

        .btn-pill {
            border-radius: 999px;
        }

        .table thead th {
            border-bottom-width: 1px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        #checkoutTable tbody td {
            vertical-align: middle;
        }

        @media (max-width: 576px) {
            .checkout-hero {
                padding: 1rem 1.2rem;
            }

            .checkout-title {
                font-size: 1.25rem;
            }
        }
    </style>

    <main class="container py-4">

        {{-- Hero / Header --}}
        <div class="checkout-hero">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="checkout-logo">
                        <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}"
                             alt="JDM Distributors">
                    </div>
                </div>
                <div class="col">
                    <div class="checkout-title d-flex align-items-center gap-2">
                        🛒 Checkout
                    </div>
                    <div class="checkout-subtitle">
                        Review your order, adjust quantities and add any delivery notes before placing your order.
                    </div>
                </div>
                <div class="col-12 col-md-auto text-md-end">
                    <div class="step-indicator">
                        <span>Cart</span>
                        <span class="divider">›</span>
                        <span class="active-step">Checkout</span>
                        <span class="divider">›</span>
                        <span>Order Complete</span>
                    </div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">
                        Prices shown are ex-VAT. VAT will be applied on the invoice where applicable.
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Checkout Card -->
        <div class="card checkout-card mb-4">
            <div class="card-header checkout-card-header d-flex justify-content-between align-items-center">
                <span>Order Summary</span>
                <span style="font-size: 0.75rem; opacity: 0.85;">
                    Excluding VAT · Editable prices & quantities
                </span>
            </div>

            <div class="card-body p-3 p-md-4">
                <!-- Checkout Table -->
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="checkoutTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">Product</th>
                                <th style="width: 18%;">Price (£)</th>
                                <th style="width: 15%;">Quantity</th>
                                <th style="width: 17%;">Subtotal (£)</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody id="checkoutBody">
                            <!-- Filled by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 🔻 Discount code row --}}
            <div class="px-3 px-md-4 py-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                    <div class="input-group input-group-sm" style="max-width: 280px;">
                        <span class="input-group-text">Discount code</span>
                        <input type="text"
                               id="discountCode"
                               class="form-control"
                               placeholder="e.g. JDM10, JDM25">
                    </div>
                    <button type="button"
                            id="applyDiscountBtn"
                            class="btn btn-sm btn-outline-primary btn-pill">
                        Apply
                    </button>
                    <button type="button"
                            id="removeDiscountBtn"
                            class="btn btn-sm btn-outline-secondary btn-pill d-none">
                        Remove
                    </button>
                </div>

                <div class="text-end small">
                    <div>
                        Discount: £<span id="discountValue">0.00</span>
                    </div>
                    <div id="discountLabel" class="text-muted"></div>
                </div>
            </div>

            <!-- TOTAL (still ex-VAT in UI) -->
            <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div class="checkout-total-text">
                    Total shown is <strong>exclusive of VAT</strong>. VAT will be calculated automatically on the invoice
                    for VAT-applicable products.
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="fw-semibold">Total (ex-VAT):</span>
                    <span class="fw-bold checkout-total-amount">£<span id="checkoutTotal">0.00</span></span>
                </div>
            </div>
        </div>

        <!-- COMMENTS BOX -->
        <div class="card mb-4 shadow-sm border-0" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0" style="border-radius: 1rem 1rem 0 0;">
                <label class="fw-bold mb-0">Comments About Your Order</label>
                <div style="font-size: 0.8rem; color:#6b7280;">
                    Use this section for delivery instructions, opening times, or any special notes.
                </div>
            </div>
            <div class="card-body pt-2">
                <textarea id="orderComments"
                          class="form-control"
                          rows="3"
                          placeholder="Write delivery notes, special instructions, etc."
                          style="border-radius: 0.75rem;"></textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-3 d-flex flex-column flex-md-row justify-content-between gap-2">
            <a href="{{ url('/') }}" class="btn btn-secondary btn-pill">
                ← Continue Shopping
            </a>

            <form id="checkoutForm" action="{{ route('checkout.place', ['shopid' => $shopid ?? 1]) }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cartDataInput">
                <button type="submit" class="btn btn-primary btn-pill px-4">
                    Place Order
                </button>
            </form>
        </div>
    </main>

    <script>
        // Map: { product_id: special_price } passed from controller
        const shopSpecialPrices = @json($specialPrices ?? []);

        document.addEventListener('DOMContentLoaded', () => {

        const shopId = "{{ $shopid ?? 1 }}";
        const cartKey = `cart_${shopId}`;
        const discountKey = `cart_discount_${shopId}`;

        const checkoutBody      = document.getElementById('checkoutBody');
        const checkoutTotalEl   = document.getElementById('checkoutTotal');
        const checkoutForm      = document.getElementById('checkoutForm');

        const discountCodeInput = document.getElementById('discountCode');
        const applyDiscountBtn  = document.getElementById('applyDiscountBtn');
        const removeDiscountBtn = document.getElementById('removeDiscountBtn');
        const discountValueEl   = document.getElementById('discountValue');
        const discountLabelEl   = document.getElementById('discountLabel');

        let activeDiscount = null; // { code: 'JDM10', percent: 10 }

        const getCart = () => {
            const stored = localStorage.getItem(cartKey);
            try {
                return stored ? JSON.parse(stored) : {};
            } catch (e) {
                console.error('Error parsing cart JSON', e);
                return {};
            }
        };

        const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

        const getSavedDiscount = () => {
            const stored = localStorage.getItem(discountKey);
            try {
                return stored ? JSON.parse(stored) : null;
            } catch (e) {
                return null;
            }
        };

        const saveDiscount = (discountObj) => {
            if (!discountObj) {
                localStorage.removeItem(discountKey);
            } else {
                localStorage.setItem(discountKey, JSON.stringify(discountObj));
            }
        };

        const parseDiscountCode = (code) => {
            if (!code) return null;
            const upper = code.trim().toUpperCase();
            const match = upper.match(/^JDM(\d{1,2})$/);
            if (!match) return null;
            const percent = parseInt(match[1], 10);
            if (percent < 1 || percent > 100) return null;
            return { code: upper, percent };
        };

        /* Apply percentage discount to every item (based on original_price) */
        const applyDiscountToCart = (cart, percent) => {
            for (let id in cart) {
                const item = cart[id];

                // Ensure original_price is set
                if (typeof item.original_price === 'undefined' || item.original_price === null) {
                    item.original_price = parseFloat(item.price) || 0;
                }

                const base = parseFloat(item.original_price) || 0;
                const discounted = base * (1 - percent / 100);
                item.price = parseFloat(discounted.toFixed(2));
            }
            saveCart(cart);
        };

        /* Remove discount: restore item.price from original_price */
        const removeDiscountFromCart = (cart) => {
            for (let id in cart) {
                const item = cart[id];
                if (typeof item.original_price !== 'undefined' && item.original_price !== null) {
                    item.price = parseFloat(item.original_price) || 0;
                }
            }
            saveCart(cart);
        };

        const updateDiscountSummary = () => {
            const cart = getCart();
            let totalOriginal = 0;
            let totalCurrent  = 0;

            for (let id in cart) {
                const item = cart[id];
                const price   = parseFloat(item.price) || 0;
                const qty     = parseInt(item.quantity) || 0;
                const orig    = (typeof item.original_price !== 'undefined' && item.original_price !== null)
                                ? parseFloat(item.original_price) || price
                                : price;

                totalCurrent  += price * qty;
                totalOriginal += orig * qty;
            }

            const diff = Math.max(totalOriginal - totalCurrent, 0);
            discountValueEl.textContent = diff.toFixed(2);

            if (activeDiscount && diff > 0) {
                discountLabelEl.textContent = `${activeDiscount.percent}% off applied with code ${activeDiscount.code}`;
                removeDiscountBtn.classList.remove('d-none');
            } else {
                discountLabelEl.textContent = '';
                removeDiscountBtn.classList.add('d-none');
            }
        };

        /* RENDER CHECKOUT TABLE (price = ex-VAT) */
        const renderCheckout = () => {
            const cart = getCart();
            checkoutBody.innerHTML = '';
            let total = 0;
            let updatedOriginals = false;

            for (let id in cart) {
                const item = cart[id];

                // Store original ex-VAT price the first time we ever see this item
                if (typeof item.original_price === 'undefined' || item.original_price === null) {
                    item.original_price = item.price;
                    updatedOriginals = true;
                }

                const price = parseFloat(item.price) || 0;  // ex-VAT (possibly discounted)
                const qty   = parseInt(item.quantity) || 1;
                const subtotal = (price * qty).toFixed(2);
                total += parseFloat(subtotal);

                checkoutBody.innerHTML += `
                    <tr data-id="${id}">
                        <td>${item.name}</td>
                        <td>
                            <input type="number"
                                   class="form-control form-control-sm price-input"
                                   value="${price.toFixed(2)}"
                                   min="0"
                                   step="0.01">
                        </td>
                        <td style="max-width: 80px;">
                            <input type="number"
                                   class="form-control form-control-sm qty-input text-center"
                                   value="${qty}"
                                   min="1"
                                   step="1">
                        </td>
                        <td class="subtotal">${subtotal}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-danger remove-item">✕</button>
                        </td>
                    </tr>
                `;
            }

            if (updatedOriginals) {
                saveCart(cart);
            }

            checkoutTotalEl.textContent = total.toFixed(2);
            attachInputListeners();
            updateDiscountSummary();
        };

        /* Update Subtotal + Total on Change */
        const attachInputListeners = () => {
            document.querySelectorAll('.price-input, .qty-input').forEach(input => {
                input.addEventListener('input', () => {
                    const row  = input.closest('tr');
                    const id   = row.dataset.id;
                    const cart = getCart();

                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty   = parseInt(row.querySelector('.qty-input').value) || 1;

                    // Update the *current* price and quantity
                    cart[id].price    = price;   // still ex-VAT (possibly discounted)
                    cart[id].quantity = qty;

                    const subtotal = (price * qty).toFixed(2);
                    row.querySelector('.subtotal').textContent = subtotal;

                    saveCart(cart);
                    updateTotalAndDiscount();
                });
            });

            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id   = btn.closest('tr').dataset.id;
                    const cart = getCart();
                    delete cart[id];
                    saveCart(cart);
                    renderCheckout();
                });
            });
        };

        const updateTotalAndDiscount = () => {
            const cart = getCart();
            let total = 0;
            for (let id in cart) {
                const item  = cart[id];
                const price = parseFloat(item.price) || 0;
                const qty   = parseInt(item.quantity) || 0;
                total += price * qty;
            }
            checkoutTotalEl.textContent = total.toFixed(2);
            updateDiscountSummary();
        };

        /* Discount code: Apply & Remove */
        applyDiscountBtn.addEventListener('click', () => {
            const parsed = parseDiscountCode(discountCodeInput.value);
            if (!parsed) {
                alert('Invalid code. Use format like JDM10, JDM20, JDM50.');
                return;
            }

            const cart = getCart();
            if (Object.keys(cart).length === 0) {
                alert('Your cart is empty!');
                return;
            }

            activeDiscount = parsed;
            applyDiscountToCart(cart, activeDiscount.percent);
            saveDiscount(activeDiscount);
            renderCheckout();
        });

        removeDiscountBtn.addEventListener('click', () => {
            const cart = getCart();
            removeDiscountFromCart(cart);
            activeDiscount = null;
            saveDiscount(null);
            discountCodeInput.value = '';
            renderCheckout();
        });

        // 🔹 Initialise cart + special shop prices + discount from localStorage
        (function init() {
            let cart = getCart();

            // 1️⃣ Apply shop-specific prices as base
            for (let id in cart) {
                const item      = cart[id];
                const productId = parseInt(id, 10);

                if (shopSpecialPrices[productId] !== undefined) {
                    const special = parseFloat(shopSpecialPrices[productId]) || 0;
                    item.original_price = special;
                    item.price          = special;
                } else {
                    if (typeof item.original_price === 'undefined' || item.original_price === null) {
                        item.original_price = parseFloat(item.price) || 0;
                    }
                }
            }
            saveCart(cart);

            // 2️⃣ Apply saved discount (if any)
            const saved = getSavedDiscount();
            if (saved && saved.percent) {
                activeDiscount = saved;
                cart = getCart();
                applyDiscountToCart(cart, activeDiscount.percent);
                discountCodeInput.value = activeDiscount.code;
            }

            // 3️⃣ Render table
            renderCheckout();
        })();

        /* SUBMIT ORDER – send ex-VAT, plus vat=yes/no to backend */
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const cart = getCart();
            if (Object.keys(cart).length === 0) {
                alert('Your cart is empty!');
                return;
            }

            const comments = document.getElementById('orderComments').value;

            const transformedCart = Object.keys(cart).map(id => {
                const item = cart[id];

                const qty    = parseInt(item.quantity) || 0;
                const price  = parseFloat(item.price) || 0; // ex-VAT (possibly discounted)
                const original = item.original_price !== undefined
                    ? parseFloat(item.original_price) || price
                    : price;

                const discountPerUnit = original > price ? (original - price) : 0;
                const discountTotal   = discountPerUnit * qty;

                const vatFlag = item.vat ?? 'no';
                const vatRate = item.vat_rate ?? null;

                return {
                    ...item,
                    quantity: qty,
                    original_price: Number(original.toFixed(2)),   // ex-VAT original
                    price_ex_vat:  Number(price.toFixed(2)),       // ex-VAT current
                    price:         Number(price.toFixed(2)),       // still ex-VAT
                    vat:           vatFlag,
                    vat_rate:      vatRate,
                    discount_per_unit: Number(discountPerUnit.toFixed(2)),
                    discount_total:     Number(discountTotal.toFixed(2)),
                };
            });

            try {
                const response = await fetch(checkoutForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart_data: transformedCart,
                        comments_about_your_order: comments
                    })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem(cartKey);
                    localStorage.removeItem(discountKey);
                    alert('✅ Order placed successfully!');
                    window.location.href = `/orders/${data.order_id}`;
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('⚠️ Something went wrong.');
            }
        });

    });
    </script>

</x-app-layout>