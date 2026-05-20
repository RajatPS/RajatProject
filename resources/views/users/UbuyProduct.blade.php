<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-color: #ff6b9d;
            --success-color: #10b981;
            --reddish-pink-color: #f55b8e;
            --dark-reddish-pink: #ff4783;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient); 
            color: white; 
            min-height: 100vh;
        }

        .checkout-panel {
            background: var(--glass-bg); 
            backdrop-filter: blur(15px); 
            border: 1px solid var(--glass-border); 
            border-radius: 1.5rem; 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); 
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.15) !important; 
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important; 
            border-radius: 0.75rem !important;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.7); }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.5); 
            border-color: var(--accent-color) !important; 
        }

        .btn-primary-checkout {
            background: var(--accent-gradient) !important;
            color: white !important;
            font-weight: 700 !important; 
            padding: 0.75rem 1.5rem !important;
            border: none !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4); 
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 157, 0.6);
        }

        .btn-primary-backto-products {
            background-color: var(--reddish-pink-color) !important;
            color: white !important;
            font-weight: 700 !important; 
            padding: 0.75rem 1.5rem !important;
            border: none !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 4px 15px rgba(252, 86, 142, 0.6); 
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
        }
        .btn-primary-backto-products:hover {
            background-color: var(--dark-reddish-pink) !important;
            transform: translateY(-2px);
        }

        .space-y-3 > * + * { margin-top: 0.75rem; }
        
        .payment-option-label {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .payment-option-label:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .form-check-input:checked + .form-check-label {
            color: var(--accent-color);
        }
        .form-check-input:checked {
            border-color: var(--accent-color);
            background-color: var(--accent-color);
        }
        .form-check-input {
            border-radius: 50%;
        }
    </style>
</head>
<body>

    <div class="container p-3 p-md-5 my-3 my-lg-5">
        <header class="text-center mb-5">
            <h1 class="display-5 fw-bold text-white">Secure Checkout</h1>
            <p class="lead text-white opacity-75">Enter Delivery Address & Payment Method</p>
        </header>
        
        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
                <div class="checkout-panel p-4 p-md-5">
                    <h2 class="h4 fw-bold mb-4 border-bottom border-white border-opacity-30 pb-3">Delivery Information</h2>
                    <form id="checkoutForm" method="post">
                        @csrf
                        <div class="mb-4">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" id="fullName" name="user_name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="user_email" class="form-control" placeholder="jane@example.com" required>
                        </div>
                        <div class="mb-4">
                            <label for="phoneNumber" class="form-label">Contact Number</label>
                            <input type="text" id="phoneNumber" name="user_phone_number" class="form-control" placeholder="Your Phone Number" required>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="address" class="form-label">Address Line 1</label>
                                <input type="text" id="address" name="user_address" class="form-control" placeholder="Your Address" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="address2" class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" id="address2" name="user_address2" class="form-control" placeholder="Apartment, suite, etc.">
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-5">
                            <div class="col-12 col-sm-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="user_city" class="form-control" required>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" id="state" name="user_state" class="form-control" required>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="zip" class="form-label">Zip Code</label>
                                <input type="text" id="zip" name="user_zip" class="form-control" required>
                            </div>
                        </div>

                        <h3 class="h5 fw-bold mb-3 border-bottom border-white border-opacity-30 pb-2">Select Payment Method</h3>
                        <div class="space-y-3 mb-4">
                            <div class="form-check py-3 px-4 payment-option-label d-flex align-items-center" onclick="document.getElementById('paymentCard').checked = true">
                                <input class="form-check-input flex-shrink-0 me-3 mt-1" type="radio" name="paymentType" value="card" id="paymentCard" checked>
                                <label class="form-check-label fw-medium text-white d-flex align-items-center w-100" for="paymentCard">
                                    Credit/Debit Card <i class="bi bi-credit-card-2-front ms-auto"></i>
                                </label>
                            </div>
                            <div class="form-check py-3 px-4 payment-option-label d-flex align-items-center" onclick="document.getElementById('paymentUpi').checked = true">
                                <input class="form-check-input flex-shrink-0 me-3 mt-1" type="radio" name="paymentType" value="upi" id="paymentUpi">
                                <label class="form-check-label fw-medium text-white d-flex align-items-center w-100" for="paymentUpi">
                                    UPI / Wallet <i class="bi bi-phone ms-auto"></i>
                                </label>
                            </div>
                            <div class="form-check py-3 px-4 payment-option-label d-flex align-items-center" onclick="document.getElementById('paymentCod').checked = true">
                                <input class="form-check-input flex-shrink-0 me-3 mt-1" type="radio" name="paymentType" value="cod" id="paymentCod">
                                <label class="form-check-label fw-medium text-white d-flex align-items-center w-100" for="paymentCod">
                                    Cash on Delivery (COD) <i class="bi bi-wallet2 ms-auto"></i>
                                </label>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-primary-checkout mt-4" id="continueBtn">
                            <i class="bi bi-arrow-right-circle-fill me-2"></i> Continue Payment
                        </button>
                        <button type="button" onclick="window.location.href = '{{ url('/Uproducts') }}';" class="btn btn-primary-backto-products mt-3">
                            <i class="bi bi-arrow-left-circle-fill me-2"></i> Back to Products
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const continueBtn = document.getElementById('continueBtn');
        const checkoutForm = document.getElementById('checkoutForm');
        
        continueBtn.addEventListener('click', function(){
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
                Swal.fire({
                    title: 'Form Incomplete',
                    text: 'Please fill out all required fields before continuing.',
                    icon: 'warning',
                    confirmButtonColor: '#6c5ce7', 
                    confirmButtonText: 'OK'
                });
            return;
        }
            const paymentInput = document.querySelector('input[name="paymentType"]:checked');
            const paymentMethod = paymentInput ? paymentInput.value : null;

            if (!paymentMethod) {
                Swal.fire({
                    title: 'Payment Method Required',
                    text: 'Please select a payment method to continue.',
                    icon: 'warning',
                    confirmButtonColor: '#6c5ce7', 
                    confirmButtonText: 'OK'
                });
                return;
            }

            if(paymentMethod === 'card'){
                checkoutForm.action = "{{ url('users/CardPayment') }}";
            } else if(paymentMethod === 'upi'){
                checkoutForm.action = "{{ url('users/UpiPayment') }}";
            } else if(paymentMethod === 'cod'){
                checkoutForm.action = "{{ url('users/CodPayment') }}";
            }

            checkoutForm.submit();
        }); 
    });
</script>
</body>
</html>