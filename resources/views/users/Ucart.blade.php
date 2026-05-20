<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Real-Time Checkout</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and Background */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .page-content-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            min-height: calc(100vh - 80px); /* Adjust based on navbar height */
        }

        .cart-container  {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 1200px; /* Use a pixel value or larger percentage for better control */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);    
        }

        
        #cart-items {
            max-height: 50vh;
            overflow-y: auto;
            padding-right: 15px;
        }
        #cart-items::-webkit-scrollbar {
            width: 15px;
        }

        #cart-items::-webkit-scrollbar-track {
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
        }

        #cart-items::-webkit-scrollbar-thumb {
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.3);
        }

        .cart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3), 
                        0 0 50px rgba(108, 92, 231, 0.8);
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        .cart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6c5ce7, #a29bfe, #fd79a8);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Header Styling */
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .cart-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
        }
        
        /* Total Amount Display */
        .total-display {
            font-size: 1.5rem;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #fdcb6e;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
        }

        /* Cart Item Card Styling */
        .cart-item-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            margin-bottom: 20px;
            padding: 15px;
            transition: background 0.3s ease;
        }
        
        .cart-item-card:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Custom Checkbox Styling for Glassmorphism */
        .product-select-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            background-color: transparent;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 6px;
            margin: 0;
            flex-shrink: 0;
        }

        .product-select-checkbox:checked {
            background-color: #a29bfe; /* Purple highlight */
            border-color: #a29bfe;
            box-shadow: 0 0 10px rgba(162, 155, 254, 0.7);
        }


        .product-image {
            width: 100%;
            height: 100px;
            background: linear-gradient(45deg, #a29bfe, #6c5ce7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
            margin-bottom: 10px;
        }

        .product-details h5 {
            font-weight: 600;
            color: #fd79a8;
            margin-bottom: 5px;
        }

        .product-price {
            font-weight: 500;
            color: #fdcb6e;
        }
        
        .product-weight {
            font-weight: 500;
            color: #fdcb6e;
        }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .qty-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.2s;
            line-height: 1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .qty-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #a29bfe;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            margin: 0 10px;
            border-radius: 8px;
            padding: 5px 0;
        }
        
        /* Summary Card */
        .summary-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
        }
        
        .summary-card h4 {
            color: #fd79a8;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .summary-row.total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fdcb6e;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed rgba(255, 255, 255, 0.4);
        }
        
        .checkout-btn {
            width: 100%;
            margin-top: 20px;
            background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(253, 121, 168, 0.3);
        }

        .remove-btn {
            width: 100%;
            margin-top: 20px;
            background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(253, 121, 168, 0.3);
        }

        /* Background Floaters */
        .floating-elements {
            z-index: -1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 40px;
            height: 40px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-circle:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.6;
            }
        }

        /* Responsive adjustments for controls */
        @media (max-width: 767px) {
            .cart-item-card {
                /* On small screens, stack the checkbox and image properly */
                padding: 15px 10px;
            }
            .cart-item-card .col-md-1, 
            .cart-item-card .col-md-2, 
            .cart-item-card .col-md-5, 
            .cart-item-card .col-md-4 {
                width: 100% !important;
                margin-bottom: 10px;
            }

            .quantity-control {
                justify-content: center !important;
            }
        }
    </style>
</head>
<body>
    
@include('layouts.navbar')
    
<div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
</div>

    @if(session('success') || session('error'))
        <div id="status-alert" 
            class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} position-fixed top-0 start-50 translate-middle-x mt-3" 
            style="z-index: 9999; 
                    background-color: {{ session('success') ? '#2ecc71' : '#ff7675' }}; 
                    color: white; 
                    border: none; 
                    min-width: 320px; 
                    text-align: center; 
                    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
                    border-radius: 12px;">
            
            <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-circle' }} me-2"></i>
            {{ session('success') ?? session('error') }}
        </div>
    @endif

    <div class="page-content-wrapper">
        <div class="container">
    
            <div class="cart-container">
                <div class="cart-header">
                    <h1><i class="fas fa-shopping-cart me-2"></i> Your Shopping Cart</h1>
                    <div class="total-display">
                        <i class="fas fa-rupee-sign me-2"></i>
                        Total: <span id="cart-total-display">0.00</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 flex-column">
                        <form method="post" action="Ucheckout" id="checkoutForm">
                            @csrf
                            <div id="cart-items">
                                @foreach($cartItems as $cartItem)
                                    @php $product = $products->firstWhere('id', $cartItem->product_id); @endphp
                                    @if($product)
                                    <div class="cart-item-card d-flex flex-column flex-md-row align-items-center" data-product-id="{{$product->id}}">
                                        <div class="col-12 col-md-1 d-flex justify-content-center mb-3 mb-md-0">
                                            <input type="checkbox" class="product-select-checkbox form-check-input">
                                        </div>
                                        <div class="col-12 col-md-2 mb-3 mb-md-0 me-md-3">
                                            <div class="product-image">
                                                <img src="{{asset('storage/'.$product->images->first()->image)}}" alt="{{$product->product_name}}" style="object-fit: contain; width: 100%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5 product-details text-center text-md-start mb-3 mb-md-0">
                                            <h5>{{$product->product_name}}</h5>
                                            <p class="product-price">₹<span class="unit-price">{{$product->price}}</span></p>
                                            <p class="product-weight">{{$product->weight}} g</p>
                                        </div>
                                        <div class="col-12 col-md-4 quantity-control justify-content-center justify-content-md-end">
                                            <button class="qty-btn btn btn-sm btn-dec" type="button" data-action="decrement"><i class="fas fa-minus"></i></button>
                                            <input type="number" class="qty-input form-control form-control-sm mx-2" value="1" min="1" max="20" readonly style="width: 60px;">
                                            <button class="qty-btn btn btn-sm btn-inc" type="button" data-action="increment"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <input type="hidden" name="products" id="selected-products">
                        </form>

                        <div class="text-center text-md-start mt-auto pt-4">
                            <a href="/Uproducts" class="btn btn-outline-light rounded-pill"><i class="fas fa-arrow-left me-2"></i> Continue Shopping</a>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="summary-card">
                            <h4>Order Summary</h4>
                            
                            <div class="summary-row">
                                <span>Items Subtotal</span>
                                <span>₹<span id="subtotal">0.00</span></span>
                            </div>

                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>₹<span id="shipping-amount">0.00</span></span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Tax (5%)</span>
                                <span>₹<span id="tax-amount">0.00</span></span>
                            </div>
                            
                            <div class="summary-row total">
                                <span>Total</span>
                                <span>₹<span id="final-total">0.00</span></span>
                            </div>

                            <button type="button" class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
                            <form method="post" action="removeProductsFromCart" id="removeForm">
                                @csrf
                                <input type="hidden" name="products" id="selected-products-remove">
                            </form>
                            <button type="button" class="remove-btn mt-3" id="remove-Products-btn">
                                <i class="fas fa-trash-alt me-2"></i>Remove Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax-amount');
        const shippingEl = document.getElementById('shipping-amount');
        const finalTotalEl = document.getElementById('final-total');
        const cartTotalDisplay = document.getElementById('cart-total-display');
        const TAX_RATE = 0.05;

        function getSelectedItems() {
            const selected = [];
            document.querySelectorAll('.cart-item-card').forEach(card => {
                const cb = card.querySelector('.product-select-checkbox');
                if (cb && cb.checked) {
                    selected.push({
                        id: card.dataset.productId,
                        qty: card.querySelector('.qty-input').value
                    });
                }
            });
            return selected;
        }

        //Update Totals 
        function updateCartTotal() {
            const items = document.querySelectorAll('.cart-item-card');
            let subtotal = 0;
            let totalQuantity = 0;

            items.forEach(item => {
                const checkbox = item.querySelector('.product-select-checkbox');
                if (checkbox && checkbox.checked) {
                    const price = parseFloat(item.querySelector('.unit-price').textContent) || 0;
                    const qty = parseInt(item.querySelector('.qty-input').value) || 0;
                    subtotal += price * qty;
                    totalQuantity += qty;
                }
            });

            const tax = subtotal * TAX_RATE;
            const shipping = totalQuantity > 0 ? (totalQuantity < 10 ? totalQuantity * 10 : totalQuantity * 5) : 0;
            const finalTotal = subtotal + tax + shipping;

            subtotalEl.textContent = subtotal.toFixed(2);
            taxEl.textContent = tax.toFixed(2);
            shippingEl.textContent = shipping.toFixed(2);
            finalTotalEl.textContent = finalTotal.toFixed(2);
            cartTotalDisplay.textContent = finalTotal.toFixed(2);
        }

        // Quantity Control 
        cartItemsContainer.addEventListener('click', function (e) {
            const btn = e.target.closest('.qty-btn');
            if (!btn) return;

            const input = btn.closest('.cart-item-card').querySelector('.qty-input');
            let val = parseInt(input.value);

            if (btn.dataset.action === 'increment') val++;
            else if (btn.dataset.action === 'decrement' && val > 1) val--;

            input.value = val;
            updateCartTotal();
        });

        cartItemsContainer.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select-checkbox')) {
                updateCartTotal();
            }
        });

        // Remove Products Button
        document.getElementById('remove-Products-btn').addEventListener('click', function () {
            const selected = getSelectedItems();
            if (selected.length === 0) {
                alert('Please select at least one product to remove.');
                return;
            }
            
            if (confirm('Are you sure you want to remove the selected items from your cart?')) {
                document.getElementById('selected-products-remove').value = JSON.stringify(selected);
                document.getElementById('removeForm').submit();
            }
        });

        updateCartTotal();

        const alertBox = document.getElementById('status-alert');
        if (alertBox) {
            setTimeout(() => {
                // Smooth fade out effect
                alertBox.style.transition = "all 0.6s cubic-bezier(0.4, 0, 0.2, 1)";
                alertBox.style.opacity = "0";
                alertBox.style.transform = "translate(-50%, -20px)"; 
                
                setTimeout(() => alertBox.remove(), 600);
            }, 5000);
        }

        //quantity limit check before checkout
        document.getElementById('checkout-btn').addEventListener('click', function (e) {
            const selected = getSelectedItems();
            
            // Check if anything is selected
            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Selection',
                    text: 'Please select at least one product to checkout.'
                });
                return;
            }

            // Check for quantity limits
            let exceedsLimit = false;
            selected.forEach(item => {
                if (parseInt(item.qty) > 20) {
                    exceedsLimit = true;
                }
            });

            if (exceedsLimit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantity Limit Exceeded',
                    text: 'The maximum quantity allowed per item is 20.',
                    confirmButtonColor: '#ff7675'
                });
                return;
            }

            document.getElementById('selected-products').value = JSON.stringify(selected);
            document.getElementById('checkoutForm').submit();
        });



        let qtyTimer;
        let isRapidFiring = false;
        function changeQty(input, action) {
            let val = parseInt(input.value);
            if (action === 'increment') {
                if (val < 20) {
                    val++;
                } else {
                    stopTimer();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit reached',
                        text: 'You can only add up to 20 units of this item.',
                        position: 'center',
                        confirmButtonColor: '#ff7675'
                    });
                }
            } else if (action === 'decrement' && val > 1) {
                val--;
            }
            input.value = val;
            updateCartTotal();
        }

        function stopTimer() {
            clearTimeout(qtyTimer);
            clearInterval(qtyTimer);
        }

        cartItemsContainer.addEventListener('mousedown', function (e) {
            const btn = e.target.closest('.qty-btn');
            if (!btn) return;
            
            e.preventDefault(); 

            const input = btn.closest('.cart-item-card').querySelector('.qty-input');
            const action = btn.dataset.action;

            changeQty(input, action);

            stopTimer(); 
            qtyTimer = setTimeout(() => {
                qtyTimer = setInterval(() => {
                    changeQty(input, action);
                }, 100);
            }, 500); 
        });

        window.addEventListener('mouseup', stopTimer);
        cartItemsContainer.addEventListener('mouseleave', stopTimer);

        cartItemsContainer.addEventListener('click', function (e) {
            if (e.target.closest('.qty-btn')) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    });
</script>

</body>
</html>
