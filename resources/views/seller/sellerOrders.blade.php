@extends('layouts.sellerDashboard')

@section('title')
    <title>Seller Dashboard | Orders Management</title>
@endsection

@section('style')
<style>
    header { margin-bottom: 30px; border-left: 4px solid #ffeaa7; padding-left: 15px; }

    /* Order Filter Navigation */
    .order-filters {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .filter-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: 0.3s;
        white-space: nowrap;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--accent-gradient);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(0, 206, 201, 0.3);
    }

    /* Table Adjustments */
    .table-wrapper {
        width: 100%;
        border-radius: 12px;
        border: 1px solid var(--glass-border);
        background: rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .seller-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px; /* Ensures table doesn't squash too much */
    }

    .seller-table th {
        background: var(--table-header-bg);
        padding: 18px 15px;
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .seller-table td {
        padding: 18px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Status Tags */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    .bg-shipped { background: #3498db; color: white; }
    .bg-delivered { background: #2ecc71; color: white; }
    .bg-returned { background: #e74c3c; color: white; }
    .bg-processing { background: #f39c12; color: white; }

    /* Tracking Number Styling */
    .tracking-code {
        font-family: 'Courier New', monospace;
        background: rgba(255, 255, 255, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .customer-info small {
        display: block;
        opacity: 0.6;
        font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
    <header>
        <h1><i class="fas fa-shipping-fast"></i> Order Shipments</h1>
        <p>Track and manage your outbound orders and returns</p>
    </header>

    <div class="order-filters">
        <button class="filter-btn active" data-status="all">All Orders</button>
        <button class="filter-btn" data-status="Pending">In Processing</button>
        <button class="filter-btn" data-status="Confirmed">Shipping</button>
        <button class="filter-btn" data-status="Delivered">Delivered</button>
        <button class="filter-btn" data-status="Cancelled">Returned/Disputed</button>
    </div>

   
    <div class="table-wrapper">
        <table class="seller-table">
            <thead>
                <tr>
                    <th>Order & Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Tracking No.</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="order-row">
                        <td>
                            <strong>Order Id: {{ $order->id }}</strong> <br>
                            <strong>Product Id: {{ $order->product_id }}</strong>
                            <small style="display:block; opacity: 0.6;">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M j, Y') : 'N/A' }}</small>
                        </td>
                        <td class="customer-info">
                            {{ $order->fullname}}
                            <small>{{ $order->address}}</small>
                        </td>
                        <td>{{ $order->quantity}} item(s)</td>
                        <td><span class="tracking-code">TRK9400122</span></td>
                        <td>₹{{ number_format($order->totalAmount, 2) }}</td>
                        <td><span class="badge bg-shipped">{{ $order->status }}</span></td>
                        <td>
                            @if($order->status === 'Pending')
                            <button title="Alter Status" 
                                    id="alter-btn" 
                                    onclick="openStatusChangeModal('{{ $order->id }}', '{{ $order->status }}')">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            @elseif($order->status === 'Confirmed')
                            <button title="Generate QR" 
                                    id="QR-btn" 
                                    onclick="generateQRCode('{{ $order->id }}')">
                                <i class="fas fa-qrcode"></i>
                            </button>
                            @endif

                            <button title="Print Label"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: rgba(255, 255, 255, 0.6);">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                            No orders found for your products
                        </td>
                    </tr>
                @endforelse
            </tbody>
            </tbody>
        </table>
    </div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
<script>

    function openStatusChangeModal(orderId, currentStatus) {
        Swal.fire({
            title: 'Change Order Status',
            input: 'select', 
            
            inputOptions: {
                'Confirmed': 'Confirm',
                'Cancelled': 'Cancel'
            },
            
            inputValue: currentStatus, 

            icon: 'question',
            inputAutoFocus: true,
            inputPlaceholder: 'Select status',
            showCancelButton: true,
            confirmButtonText: 'Confirm & Change Status',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            
            inputValidator: (value) => {
                if (!value) {
                    return 'You must select a new status!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const selectedStatus = result.value;
                console.log(orderId, selectedStatus);

                const formData = new FormData();

                formData.append('orderId', orderId);
                formData.append('status', selectedStatus);

                fetch('/seller/orders/updateStatus/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        orderId: orderId,
                        status: selectedStatus,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Updated!', data.message, 'success').then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An internal routing issue occurred.', 'error');
                });
            }
        });
    }


    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");
        const orderRows = document.querySelectorAll(".order-row");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                filterButtons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");

                const targetStatus = this.getAttribute("data-status");

                orderRows.forEach(row => {
                    const statusBadge = row.querySelector("td .badge");
                    
                    if (!statusBadge) return;
                    
                    const rowStatusText = statusBadge.textContent.trim();

                    if (targetStatus === "all" || rowStatusText === targetStatus) {
                        row.style.display = ""; 
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    });


    function generateQRCode(orderId) {
        Swal.fire({
            title: `QR Code for Order #${orderId}`,
            html: `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px;">
                    <div id="qrcode-target"></div>
                    <p style="margin-top: 15px; font-weight: 600; color: #555;">Scan to manage or verify order parameters</p>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Close',
            confirmButtonColor: '#3085d6',
            
            didOpen: () => {
                const container = document.getElementById('qrcode-target');
                
                new QRCode(container, {
                    text: String(orderId), // What information the QR code contains (your order ID)
                    width: 200,            // Output image width in pixels
                    height: 200,           // Output image height in pixels
                    colorDark : "#000000", // Foreground block hex color
                    colorLight : "#ffffff",// Background padding hex color
                    correctLevel : QRCode.CorrectLevel.H // High-density error correction level
                });
            }
        });
    }

</script>