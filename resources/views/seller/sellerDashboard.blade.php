@extends('layouts.sellerDashboard')

@section('title')
    <title>Seller Dashboard | Home</title>
@endsection

@section('style')
<style>
    header { margin-bottom: 30px; border-left: 4px solid #ffeaa7; padding-left: 15px; }

    /* Stats container spreads across the new wide space */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 25px;
        border-radius: 14px;
        border: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-icon {
        width: 60px; height: 60px;
        background: #ffeaa7;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0984e3;
        font-size: 1.5rem;
    }

    /* Table stretches to 100% of the wide dashboard container */
    .table-wrapper {
        width: 100%;
        border-radius: 12px;
        border: 1px solid var(--glass-border);
        overflow: hidden;
    }

    .seller-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.02);
    }

    .seller-table th {
        background: var(--table-header-bg);
        padding: 18px;
        text-align: left;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .seller-table td {
        padding: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .product-cell { display: flex; align-items: center; gap: 15px; }
    .dummy-img {
        width: 45px; height: 45px;
        background: white; color: #0984e3;
        border-radius: 8px; font-weight: bold;
        display: flex; align-items: center; justify-content: center;
    }

    .status-pill {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .st-pending { background: var(--pending); color: white; }
    .st-shipped { background: var(--shipped); color: white; }
</style>
@endsection

@section('content')
    <header>
        <h1><i class="fas fa-store"></i> Seller Dashboard</h1>
        <p>Managing orders for <strong>Your Global Store</strong></p>
    </header>

    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-info">
                <h3 style="font-size: 0.9rem; opacity: 0.7;">Today's Orders</h3>
                <p style="font-size: 1.5rem; font-weight: bold;">{{ $todayOrders }}</p>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="stat-info">
                <h3 style="font-size: 0.9rem; opacity: 0.7;">Pending Payout</h3>
                <p style="font-size: 1.5rem; font-weight: bold;">$1,240.50</p>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-info">
                <h3 style="font-size: 0.9rem; opacity: 0.7;">Active Shipments</h3>
                <p style="font-size: 1.5rem; font-weight: bold;">5</p>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="seller-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Details</th>
                    <th>Customer</th>
                    <th>Product Price</th>
                    <th>Status</th>
                    <th>Quick Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <div class="product-cell">
                                <div class="dummy-img">P1</div>
                                <div>
                                    <p style="font-weight: 600;">{{ $order->product->product_name }}</p>
                                    <p style="font-size: 0.75rem; opacity: 0.7;">SKU: WH-001</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $order->fullname }}</td>
                        <td>₹ {{ $order->totalAmount }}</td>
                        <td><span class="status-pill st-pending">{{ $order->status }}</span></td>
                        <td>
                            <select class="action-select">
                                <option>Update Status</option>
                                <option>Print Invoice</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection