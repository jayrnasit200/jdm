<x-app-layout>
    <main class="container py-5">

        <h2 class="mb-4">🛒 Checkout</h2>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <!-- Checkout Table -->
        <div class="table-responsive">
            <table class="table align-middle" id="checkoutTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (£)</th>
                        <th>Quantity</th>
                        <th>Subtotal (£)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="checkoutBody">
                    <!-- Filled by JS -->
                </tbody>
            </table>
        </div>

        <!-- TOTAL -->
        <div class="d-flex justify-content-end fw-bold fs-5">
            Total: £<span id="checkoutTotal">0.00</span>
        </div>

        <!-- COMMENTS BOX -->
        <div class="mt-4">
            <label class="fw-bold mb-1">Comments About Your Order</label>
            <textarea id="orderComments"
                      class="form-control"
                      rows="3"
                      placeholder="Write delivery notes, special instructions, etc."></textarea>
        </div>

        <!-- Buttons -->
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ url('/') }}" class="btn btn-secondary rounded-pill">Continue Shopping</a>

            <form id="checkoutForm" action="{{ route('checkout.place', ['shopid' => $shopid ?? 1]) }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cartDataInput">
                <button type="submit" class="btn btn-primary rounded-pill">Place Order</button>
            </form>
        </div>
    </main>

    <!-- JS -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const shopId = "{{ $shopid ?? 1 }}";
        const cartKey = `cart_${shopId}`;
        const checkoutBody = document.getElementById('checkoutBody');
        const checkoutTotalEl = document.getElementById('checkoutTotal');
        const checkoutForm = document.getElementById('checkoutForm');

        const getCart = () => JSON.parse(localStorage.getItem(cartKey)) || {};
        const saveCart = (cart) => localStorage.setItem(cartKey, JSON.stringify(cart));

        /* RENDER CHECKOUT TABLE */
        const renderCheckout = () => {
            const cart = getCart();
            checkoutBody.innerHTML = '';
            let total = 0;

            for (let id in cart) {
                const item = cart[id];
                const subtotal = (item.price * item.quantity).toFixed(2);
                total += parseFloat(subtotal);

                checkoutBody.innerHTML += `
                    <tr data-id="${id}">
                        <td>${item.name}</td>
                        <td><input type="number" class="form-control form-control-sm price-input"
                                   value="${item.price.toFixed(2)}" min="0" step="0.01"></td>

                        <td><input type="number" class="form-control form-control-sm qty-input"
                                   value="${item.quantity}" min="1" step="1"></td>

                        <td class="subtotal">${subtotal}</td>

                        <td>
                            <button class="btn btn-sm btn-danger remove-item">✕</button>
                        </td>
                    </tr>
                `;
            }

            checkoutTotalEl.textContent = total.toFixed(2);
            attachInputListeners();
        };

        /* Update Subtotal + Total on Change */
        const attachInputListeners = () => {
            document.querySelectorAll('.price-input, .qty-input').forEach(input => {
                input.addEventListener('input', () => {
                    const row = input.closest('tr');
                    const id = row.dataset.id;
                    const cart = getCart();

                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseInt(row.querySelector('.qty-input').value) || 1;

                    cart[id].price = price;
                    cart[id].quantity = qty;

                    const subtotal = (price * qty).toFixed(2);
                    row.querySelector('.subtotal').textContent = subtotal;

                    saveCart(cart);
                    updateTotal();
                });
            });

            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.closest('tr').dataset.id;
                    const cart = getCart();
                    delete cart[id];
                    saveCart(cart);
                    renderCheckout();
                });
            });
        };

        /* Update Total Only */
        const updateTotal = () => {
            const cart = getCart();
            let total = 0;
            for (let id in cart) total += cart[id].price * cart[id].quantity;
            checkoutTotalEl.textContent = total.toFixed(2);
        };

        renderCheckout();

        /* SUBMIT ORDER */
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const cart = getCart();
            if (Object.keys(cart).length === 0) {
                alert('Your cart is empty!');
                return;
            }

            const comments = document.getElementById('orderComments').value;

            try {
                const response = await fetch(checkoutForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart_data: Object.values(cart),
                        comments_about_your_order: comments
                    })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem(cartKey);
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
