<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        /* General Reset and Body Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            min-height: 100vh;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated Background Elements */
        .floating-elements {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite ease-in-out;
        }
        .floating-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        .floating-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 15%;
            animation-delay: 5s;
        }
        .floating-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 15%;
            left: 50%;
            animation-delay: 10s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* Main Container */
        .cart-container { 
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 85vw;
            max-height: 90vh;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.25),
                0 0 60px rgba(108, 92, 231, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
            overflow: hidden;
            color: white;
            margin: 50px auto;
            z-index: 1;
        }
        
        /* Top Accent Line */
        .cart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #6c5ce7, #a29bfe, #fd79a8, #fdcb6e);
            background-size: 200% 100%;
            animation: gradientShift 4s ease infinite;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Header Section */
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 36px;
            padding-bottom: 24px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.15);
        }
        .cart-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .cart-header h1 i {
            background: linear-gradient(135deg, #fd79a8, #fdcb6e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-outline-light {
            padding: 12px 28px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        
        /* Scrolling Area */
        #order-list {
            max-height: 58vh; 
            overflow-y: auto;
            padding-right: 12px;
            margin-right: -8px;
        }
        
        /* Custom Scrollbar */
        #order-list::-webkit-scrollbar {
            width: 10px;
        }
        #order-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 8px 0;
        }
        #order-list::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #a29bfe 0%, #6c5ce7 100%);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        #order-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #b8b2ff 0%, #7d73ec 100%);
            background-clip: padding-box;
        }
        
        /* Order Card */
        .order-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            margin-bottom: 24px;
            padding: 28px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #fd79a8, #fdcb6e);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .order-card:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.12) 100%);
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .order-card:hover::before {
            opacity: 1;
        }
        
        /* Order Header */
        .order-header-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.2);
        }
        .order-id-status {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .order-id-status h5 {
            font-weight: 700;
            font-size: 1.35rem;
            color: #ffffff;
            margin-bottom: 0;
            letter-spacing: -0.3px;
        }
        
        /* Status Badges */
        .order-status {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            align-self: flex-start;
        }
        .order-status::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .status-completed { 
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }
        .status-pending { 
            background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%);
            color: #2c3e50;
        }
        .status-shipped { 
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }
        .status-cancelled { 
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        
        /* Order Total and Date */
        .order-total-date { 
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .order-total-date p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }
        .order-total-date span {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fdcb6e 0%, #fd79a8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        
        /* Items Section */
        .items-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }
        .items-header i {
            color: #f07955;
        }
        
        /* Order Items Table */
        .order-items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
        }
        .order-items-table thead {
            background: rgba(52, 29, 231, 0.2);
        }
        .order-items-table th {
            padding: 14px 16px;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(162, 155, 254, 0.3);
        }
        .order-items-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.95rem;
        }
        .order-items-table tbody tr:last-child td {
            border-bottom: none;
        }
        .order-items-table tbody tr {
            transition: background 0.2s ease;
        }
        .order-items-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .item-name {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .item-name::before {
            content: '•';
            color: #fd79a8;
            font-size: 1.2rem;
        }
        .item-qty {
            font-weight: 600;
        }
        .item-price {
            color: #fdcb6e;
            font-weight: 700;
            font-size: 1.05rem;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.6);
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.4;
        }
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-container {
                padding: 24px;
                max-width: 95vw;
            }
            .cart-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
            .cart-header h1 {
                font-size: 1.8rem;
            }
            .order-header-info {
                flex-direction: column;
                gap: 16px;
            }
            .order-total-date {
                text-align: left;
            }
        }

        .product-img-wrapper {
            width: 60px;
            height: 60px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background: cover;
            display: flex;
        }

        /* Container for the buttons to keep them tidy */
        .action-buttons-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 140px;
        }

        /* Base style for both custom buttons */
        .btn-glass-custom {
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.5); /* Makes text visible! */
        }

        /* VIEW PRODUCT - Purple/Gold Theme */
        .btn-view-product {
            background: rgba(162, 155, 254, 0.2);
            color: #fdcb6e; /* Bright Gold text */
        }

        .btn-view-product:hover {
            background: rgba(162, 155, 254, 0.4);
            color: #ffffff;
            box-shadow: 0 0 15px rgba(162, 155, 254, 0.5);
            transform: translateY(-2px);
        }

        /* CANCEL ORDER - Frosted Red Theme */
        .btn-cancel-order {
            background: rgba(231, 76, 60, 0.25);
            color: #ff9f89; /* Bright Coral/Red text */
            border: 1px solid rgba(231, 76, 60, 0.4);
        }

        .btn-cancel-order:hover {
            background: rgba(231, 76, 60, 0.9); /* Almost solid on hover for max contrast */
            color: #ffffff;
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.5);
            transform: translateY(-2px);
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
    
    <div class="cart-container">
        <div class="cart-header">
            <h1><i class="fas fa-receipt"></i> My Order History</h1>
            <a href="/Uproducts" class="btn btn-outline-light rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Back to Shop
            </a>
        </div>

        <div id="order-list">
            @forelse($orders as $order)
            <div class="order-card">
                <div class="order-header-info">
                    <div class="order-id-status">
                        <h5>#ORD-{{ $order->id }}</h5>
                        <span class="order-status status-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="order-total-date">
                        <p class="mb-0">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $order->order_date }}
                        </p>
                        <span>₹{{ number_format($order->totalAmount, 2) }}</span>
                    </div>
                </div>
                
                <div class="items-header">
                    <i class="fas fa-box-open"></i>
                    <span>Order Items</span>
                </div>
                
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Product_image</th>
                            <th style="width: 20%;">Product</th>
                            <th style="width: 20%;">Quantity</th>
                            <th style="width: 20%;">Price</th>
                            <th style="width: 20%;">others</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="product-img-wrapper">
                                    <img src="{{ asset('storage/' . $order->product->images->first()->image) }}" 
                                    alt="Product" class="order-product-img">
                                </div>
                            </td>
                            
                            <td class="item-name">
                                <a href="{{ url('users/UsingleProduct?product_id='.$order->product->id) }}" class="product-detail-link">
                                    {{ $order->product->product_name }}
                                </a>
                            </td>
                            
                            <td class="item-qty">x{{ $order->quantity }}</td>
                            
                            <td class="item-price">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>₹{{ number_format($order->totalAmount, 2) }}</span>
                                    <a href="{{ url('users/UsingleProduct?product_id=' . $order->product->id) }}" class="btn-view-detail">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons-wrapper">
                                    <a href="{{ url('users/UsingleProduct?product_id=' . $order->product->id) }}" class="btn-glass-custom btn-view-product">
                                        <i class="fas fa-eye"></i>
                                        View Item
                                    </a>
                                    @if($order->can_cancel)
                                        @if($order->status === 'Pending' || $order->status === 'Confirmed')
                                        <form action="{{ url('users/cancelOrder')}}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button type="submit" class="btn-glass-custom btn-cancel-order w-100" ">
                                                <i class="fas fa-times-circle"></i>
                                                Cancel
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                    @if($order->can_return)
                                        @if($order->status === 'delivered')
                                        <form action="{{ url('users/returnOrder')}}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button type="submit" class="btn-glass-custom btn-return-order w-100" ">
                                                <i class="fas fa-undo"></i>
                                                Return
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Orders Yet</h3>
                <p>Start shopping to see your orders here!</p>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>