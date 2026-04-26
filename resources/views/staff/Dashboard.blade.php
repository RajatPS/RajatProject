<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Action Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .dashboard-wrapper { max-width: 1400px; margin: 0 auto; padding: 15px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .stat-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .stat-card h3 { font-size: 1.8rem; margin: 0; font-weight: 800; }
        .stat-card p { font-size: 0.8rem; text-transform: uppercase; margin: 0; opacity: 0.9; }

        /* Tabs Styling */
        .nav-pills .nav-link { color: white; background: rgba(0,0,0,0.1); margin-right: 5px; border-radius: 10px; }
        .nav-pills .nav-link.active { background: #f1c40f; color: #2d3436; font-weight: bold; }

        /* Product List Grid */
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            margin-bottom: 20px;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 5px solid #f1c40f;
            min-height: 220px;
        }

        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .payment-status { font-size: 0.75rem; padding: 3px 8px; border-radius: 20px; font-weight: bold; }
        .paid { background: #e1f7e7; color: #27ae60; }
        .due { background: #ffeaa7; color: #d35400; }

        .customer-info h6 { margin: 0; color: #2d3436; font-weight: 700; }
        .customer-info p { margin: 2px 0; font-size: 0.85rem; color: #636e72; }
        
        .phone-link {
            display: inline-flex;
            align-items: center;
            color: #0984e3;
            text-decoration: none;
            font-weight: 600;
            background: #e1f0ff;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 0.85rem;
        }

        .card-actions { display: flex; gap: 10px; margin-top: 12px; }
        .btn-card-scan {
            flex: 1;
            background: #2d3436;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Mobile Adjustments */
        @media (max-width: 576px) {
            .order-list { grid-template-columns: 1fr; }
            .order-card { min-height: auto; }
        }

        /* Scanner Modal - Centered on Top */
        #scanner-modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9); z-index: 10002; flex-direction: column;
            align-items: center; justify-content: flex-start; padding-top: 50px;
        }
        #reader { width: 300px; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 0 20px rgba(255,255,255,0.2); }

        body , html {
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin-top: 70px; 
            padding: 20px;
            flex: 1;
        }

        /* add products to deliverylist btn */
        .action-bar {
            display: flex;
            justify-content: center;
            margin: 10px 0 20px 0;
            width: 100%;
        }

        /* Small, Styled Button */
        .btn-scan-sm {
            background: rgba(45, 52, 54, 0.9); /* Darker semi-transparent */
            backdrop-filter: blur(5px);
            color: #f1c40f;
            border: 1px solid rgba(241, 196, 15, 0.5);
            padding: 10px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
        }

        .btn-scan-sm:hover {
            background: #2d3436;
            transform: translateY(-2px);
            border-color: #f1c40f;
        }

        .btn-scan-sm i {
            font-size: 1.2rem;
        }

        .btn-scan-sm span {
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Small plus badge on the button */
        .badge-plus {
            font-size: 0.7rem !important;
            position: absolute;
            top: 5px;
            right: 8px;
            color: #fff;
        }

        /* Ensure it stays small on mobile */
        @media (max-width: 576px) {
            .btn-scan-sm {
                width: 80%; /* Takes up most of the width but stays centered */
                justify-content: center;
            }
        }

        
    </style>
</head>
<body>

    @include('layouts/staffnavbar')

    <div class="dashboard-wrapper">
        <div class="stats-grid">
            <div class="stat-card">
                <p>To Pick</p>
                <h3 id="pickup-count">{{ $pickups->count() }}</h3>
            </div>
            <div class="stat-card">
                <p>To Deliver</p>
                <h3 id="delivery-count">{{ $deliveries->count() }}</h3>
            </div>
        </div>
        <div class="action-bar">
    <button class="btn-scan-sm" onclick="startScanner(null, 'assign')">
        <i class="fas fa-barcode"></i>
        <span>SCAN TO ASSIGN</span>
        <i class="fas fa-plus-circle badge-plus"></i>
    </button>
</div>

        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pickup-list">Pickup List</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#delivery-list">Delivery List</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            {{-- PICKUP LIST --}}

            @foreach($pickups as $pickup)
            <div class="tab-pane fade show active" id="pickup-list">
                <div class="order-list">
                    <div class="order-card" data-id="9901">
                        <div class="order-header">
                            <span class="fw-bold text-muted">#ORD-{{$pickup->id}}</span>
                            {{-- <span class="payment-status paid">PAID</span> --}}
                        </div>
                        <div class="customer-info">
                            <h6>{{ $pickup->fullname }}</h6>
                            <p><i class="fas fa-location-dot me-2"></i>{{ $pickup->address }}, {{ $pickup->city }}, {{ $pickup->zip }}</p>
                            <a href="tel:+919876543210" class="phone-link mt-2">
                                <i class="fas fa-phone-alt me-2"></i> +91 {{ $pickup->contact_number }}
                            </a>
                        </div>
                        <div class="card-actions">
                            <button class="btn-card-scan" onclick="startScanner('{{ $pickup->id }}', 'pickup')">
                                <i class="fas fa-barcode me-2"></i> SCAN PRODUCT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- delivery list --}}
            
            @foreach($deliveries as $delivery)
                <div class="tab-pane fade" id="delivery-list">
                    <div class="order-list">
                        <div class="order-card" style="border-left-color: #27ae60;" data-id="{{ $delivery->id }}">
                            <div class="order-header">
                                <span class="fw-bold text-muted">#ORD-{{ $delivery->id }}</span>
                                <span class="payment-status due">₹1,250 DUE</span>
                            </div>
                            <div class="customer-info">
                                <h6>{{ $delivery->fullname }}</h6>
                                <p><i class="fas fa-location-dot me-2"></i>{{ $delivery->address }}, {{ $delivery->city }}, {{ $delivery->zip }}</p>
                                <a href="tel:+919988776655" class="phone-link mt-2">
                                    <i class="fas fa-phone-alt me-2"></i> +91 {{ $delivery->contact_number }}
                                </a>
                            </div>
                            <div class="card-actions">
                                <button class="btn-card-scan" style="background:#27ae60" onclick="startScanner('{{ $delivery->id }}', 'deliver')">
                                    <i class="fas fa-qrcode me-2"></i> SCAN PRODUCT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="scanner-modal">
        <div id="reader"></div>
        <div class="text-center mt-3 text-white">
            <p id="scanner-label" class="fw-bold mb-1">Scanning for Order #9901</p>
            <small class="opacity-75">Align the code within the box</small>
        </div>
        <button class="btn btn-danger mt-4 px-5 rounded-pill fw-bold" onclick="stopScanner()">
            CANCEL SCAN
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        let html5QrCode;
        let activeTargetId = "";
        let activeMode = "";

        async function startScanner(id, mode) {
            activeTargetId = id;
            activeMode = mode;
            const label = document.getElementById('scanner-label');
            label.innerText = id ? `Scanning for Order #${id}` : "Scanning to Assign New Product";
            
            document.getElementById('scanner-modal').style.display = 'flex';
            
            if (html5QrCode) {
                try { await html5QrCode.clear(); } catch(e) {}
            }

            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 220, height: 220 } };

            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).catch(err => {
                console.error("Camera Error:", err);
                Swal.fire({
                    title: 'Camera Error',
                    text: 'Could not access camera. Please ensure you are on a mobile device or HTTPS.',
                    icon: 'error'
                });
                stopScanner();
            });
        }

        function onScanSuccess(decodedText) {
            stopScanner();
            
            if (activeMode === 'assign') {
                processAssignment(decodedText);
            } else {
                Swal.fire({
                    title: `Order #${activeTargetId}`,
                    text: `Set status for ${activeMode}:`,
                    icon: 'question',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Completed',
                    denyButtonText: 'Failed',
                    confirmButtonColor: '#27ae60'
                }).then((result) => {
                    const status = result.isConfirmed ? 'Success' : (result.isDenied ? 'Failed' : 'Hold');
                    updateDatabase(activeTargetId, status);
                });
            }
        }

        async function processAssignment(barcode) {
            Swal.fire({ title: 'Assigning...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            try {
                const response = await fetch('/staff/assign-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ barcode: barcode })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire('Success', `Order #${data.order_id} added to your list!`, 'success')
                        .then(() => location.reload()); 
                } else {
                    Swal.fire('Error', data.message || 'Product not found or already assigned.', 'error');
                }
            } catch (e) {
                Swal.fire('some error occurred.', 'product could not be assigned.', 'info');
            }
        }
        

        async function updateDatabase(id, status) {
            try {
                const response = await fetch('/staff/deliverOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order_id: id, status: status, type: activeMode })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire('Updated!', `Order marked as ${status}`, 'success').then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Success', `Demo: Order #${id} updated to ${status}`, 'success');
            }
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    document.getElementById('scanner-modal').style.display = 'none';
                    html5QrCode.clear();
                });
            } else {
                document.getElementById('scanner-modal').style.display = 'none';
            }
        }

    </script>
</body>
</html>