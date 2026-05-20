<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Cash on Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --success-color: #10b981;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient); 
            color: white; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .confirmation-card {
            background: var(--glass-bg); 
            backdrop-filter: blur(15px); 
            border: 1px solid var(--glass-border); 
            border-radius: 2rem; 
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3); 
            max-width: 700px;
            width: 100%;
            padding: 3rem;
            text-align: center;
        }

        .success-icon {
            font-size: 5rem;
            color: var(--success-color);
            margin-bottom: 1.5rem;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            80% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .order-details {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0;
        }

        .detail-row:last-child { border-bottom: none; }

        .btn-action {
            font-weight: 700;
            padding: 0.8rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid var(--glass-border);
        }

        .btn-print:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-home {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 157, 0.6);
            color: white;
        }

        @media print {
            .btn-action, header, .no-print { display: none !important; }
            body { background: white; color: black; }
            .confirmation-card { border: none; box-shadow: none; backdrop-filter: none; background: white; color: black; }
            .order-details { border: 1px solid #ccc; color: black; }
            .success-icon { color: green; }
        }
    </style>
</head>
<body>

    <div class="confirmation-card">
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        
        <h1 class="display-6 fw-bold">Order Confirmed!</h1>
        <p class="lead opacity-75">Thank you for your purchase. Your order has been placed successfully via Cash on Delivery.</p>

        <div class="order-details" id="invoice-content">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
            <div class="order-id-container mt-4">
                <h5 class="fw-bold mb-3 border-bottom border-white border-opacity-25 pb-2">Your Order IDs</h5>
                
                @if(session('orderInfo'))
                    @foreach(session('orderInfo') as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 px-3 mb-2" 
                            style="background: rgba(255, 255, 255, 0.1); border-radius: 0.5rem; border: 1px solid rgba(255, 255, 255, 0.2);">
                            
                            <span class="opacity-75">Order for {{ $item['product_name'] }}:</span>
                            <span class="fw-bold text-info">#ORD{{ $item['order_id'] }}</span>
                            
                        </div>
                    @endforeach
                @else
                    <p class="text-center opacity-50">No orders found.</p>
                @endif
            </div>
            <div class="detail-row">
                <span>Payment Method:</span>
                <span class="badge ">{{ session('payment_method') }}</span>
            </div>
            
            <div class="detail-row">
                <span>Total Amount:</span>
                <span class="fw-bold text-info">₹{{ collect(session('orderInfo'))->sum('total_amount') ?? '0.00' }}</span>
            </div>
            <div class="detail-row">
                <span>Estimated Delivery:</span>
                <span>3-5 Business Days</span>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mt-4">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="bi bi-printer-fill"></i> Print Invoice
            </button>
            <a href="{{ url('/Uproducts') }}" class="btn-action btn-home">
                <i class="bi bi-house-door-fill"></i> Back to Home
            </a>
        </div>

        <p class="mt-5 opacity-50 small">
            A confirmation email has been sent to your registered email address.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>