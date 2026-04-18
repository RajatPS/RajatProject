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
            document.getElementById('scanner-label').innerText = `Scanning for Order #${id}`;
            document.getElementById('scanner-modal').style.display = 'flex';
            
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 220, height: 220 } };

            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).catch(err => {
                Swal.fire('Error', 'Camera access denied', 'error');
                stopScanner();
            });
        }

        function onScanSuccess(decodedText) {
            stopScanner();
            
            // Logic for showing status options
            Swal.fire({
                title: `Order #${activeTargetId}`,
                text: `Status for ${activeMode === 'pickup' ? 'Pickup' : 'Delivery'}:`,
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Completed',
                denyButtonText: 'Issue/Refused',
                cancelButtonText: 'Hold',
                confirmButtonColor: '#27ae60'
            }).then((result) => {
                const status = result.isConfirmed ? 'Success' : (result.isDenied ? 'Failed' : 'Hold');
                updateDatabase(activeTargetId, status);
            });
        }

        async function updateDatabase(id, status) {
            try {
                const response = await fetch('/staff/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order_id: id, status: status, type: activeMode })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire('Updated!', `Order ${status}`, 'success');
                    // Refresh counts or remove card here
                }
            } catch (e) {
                // For demo purposes, we will just show a toast
                Swal.fire('Success', `Demo: Order #${id} set to ${status}`, 'success');
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