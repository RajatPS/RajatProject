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

    .seller-table td {
        padding: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        vertical-align: middle; /* Centers content vertically */
    }

    .action-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-start;
    }

    .action-select {
        padding: 6px 10px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.8rem;
    }

    .action-btn {
        padding: 6px 12px;
        background: #0984e3;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        transition: 0.3s;
    }

    .action-btn:hover { background: #00a8ff; }



    /* Container for the actions */
    .action-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Styled Select Box */
    .status-dropdown {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .status-dropdown:focus {
        border-color: #ffeaa7;
        background: rgba(255, 255, 255, 0.1);
    }

    .status-dropdown option {
        background: #2d3436; /* Dark background for dropdown options */
        color: white;
    }

    /* The Update Button */
    .btn-update-status {
        background: #00cec9;
        color: #2d3436;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s transform ease;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 206, 201, 0.3);
    }

    .btn-update-status:hover {
        background: #81ecec;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 206, 201, 0.4);
    }

    .btn-update-status:active {
        transform: translateY(0);
    }

    /* Icon rotation on hover */
    .btn-update-status:hover i {
        transform: rotate(180deg);
        transition: 0.5s;
    }

    /* Styling for the New Filter Buttons */
    .tab-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.6);
        padding: 12px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .tab-btn i { margin-right: 8px; }

    .active-tab {
        background: #ffeaa7 !important;
        color: #2d3436 !important;
        border-color: #ffeaa7 !important;
        box-shadow: 0 4px 15px rgba(255, 234, 167, 0.3);
    }

    .tab-btn:hover:not(.active-tab) {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

</style>
@endsection

@section('content')
    <header>
        <h1><i class="fas fa-store"></i> Seller Dashboard</h1>
        <p>Managing orders for <strong>Your Global Store</strong></p>
    </header>

    <div class="stats-container">
        </div>

    <div class="filter-nav" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <button onclick="switchTab('pending')" id="btn-pending" class="tab-btn active-tab">
            <i class="fas fa-clock"></i> Pending Orders ({{ $pendingOrders->count() }})
        </button>
        <button onclick="switchTab('confirmed')" id="btn-confirmed" class="tab-btn">
            <i class="fas fa-check-double"></i> Confirmed Orders ({{ $confirmedOrders->count() ?? 0 }})
        </button>
    </div>

    <div class="table-wrapper">
        <div id="pending-section">
            <table class="seller-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Details</th>
                        <th>Customer</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Quick Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $order)
                        <tr>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>
                                <div class="product-cell">
                                    <div class="dummy-img">P1</div>
                                    <div><p style="font-weight: 600;">{{ $order->product->product_name }}</p></div>
                                </div>
                            </td>
                            <td>{{ $order->fullname }}</td>
                            <td>₹ {{ $order->totalAmount }}</td>
                            <td><span class="status-pill st-pending">{{ $order->status }}</span></td>
                            <td>
                                <div class="action-cell">
                                    <select class="status-dropdown" onchange="updateStatusOnly({{ $order->id }}, this.value)">
                                        <option value="pending" selected>Pending</option>
                                        <option value="confirmed">Confirm</option>
                                        <option value="cancelled">Cancel</option>
                                    </select>
                                    <input type="hidden" class="product-id" value="{{ $order->product_id }}">
                                    <button class="btn-update-status" onclick="generateQROnly(this, {{ $order->id }})">
                                        Generate QR <i class="fas fa-qrcode"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="confirmed-section" style="display: none;">
            <table class="seller-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Details</th>
                        <th>Customer</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($confirmedOrders ?? [] as $order)
                        <tr>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>
                                <div class="product-cell">
                                    <div class="dummy-img" style="background: #55efc4;">P1</div>
                                    <div><p style="font-weight: 600;">{{ $order->product->product_name }}</p></div>
                                </div>
                            </td>
                            <td>{{ $order->fullname }}</td>
                            <td>₹ {{ $order->totalAmount }}</td>
                            <td><span class="status-pill" style="background: #00cec9;">Confirmed</span></td>
                            <td>
                                <div class="action-cell">
                                    <input type="hidden" class="product-id" value="{{ $order->product_id }}">
                                    
                                    <button class="btn-update-status" 
                                            style="background: #a29bfe; color: white;" 
                                            onclick="generateQROnly(this, {{ $order->id }})">
                                        Re-print QR <i class="fas fa-qrcode"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<script>
function generateQROnly(btnElement, orderId) {
    const actionCell = btnElement.closest('.action-cell');
    const productId = actionCell.querySelector('.product-id').value;

    const qrData = `OrderID:${orderId}|ProductID:${productId}`;
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrData)}`;
    window.open(qrUrl, '_blank');
    
    console.log("QR Generated for Order:", orderId);
}

function updateStatusOnly(orderId, newStatus) {
    Swal.fire({
        title: 'Updating Status...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch(`/seller/orders/updateStatus/${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Saved!', 'Database updated successfully.', 'success');
        } else {
            Swal.fire('Error', 'Could not update database.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Server connection failed.', 'error');
    });
}

function switchTab(tabName) {
    const pendingSec = document.getElementById('pending-section');
    const confirmedSec = document.getElementById('confirmed-section');
    const btnPending = document.getElementById('btn-pending');
    const btnConfirmed = document.getElementById('btn-confirmed');

    if (tabName === 'pending') {
        pendingSec.style.display = 'block';
        confirmedSec.style.display = 'none';
        btnPending.classList.add('active-tab');
        btnConfirmed.classList.remove('active-tab');
    } else {
        pendingSec.style.display = 'none';
        confirmedSec.style.display = 'block';
        btnConfirmed.classList.add('active-tab');
        btnPending.classList.remove('active-tab');
    }
}
</script>
@endsection