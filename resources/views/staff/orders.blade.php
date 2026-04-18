<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Delivery Panel</title>
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

        .dashboard-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 15px;
            max-width: 1100px;
            margin: 10px auto;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            min-height: 90vh;
        }

        .stat-box { background: rgba(0,0,0,0.2); padding: 5px 15px; border-radius: 30px; font-size: 14px; }

        .table-responsive { 
            border-radius: 12px; 
            background: rgba(255, 255, 255, 0.98); 
            overflow: visible !important;
        }
        .table { margin-bottom: 0; color: #333; width: 100%; }
        .table thead { background: #2d3436; color: white; }
        .table td { padding: 8px 12px; vertical-align: middle; border-bottom: 1px solid #eee; transition: 0.3s; line-height: 1.1; }

        .btn-scan { background: #0984e3; color: white; border: none; padding: 6px 15px; border-radius: 5px; font-weight: 600; font-size: 13px; transition: 0.2s; }
        .btn-scan:hover { background: #0773c5; }

        .row-completed { background: #f8f9fa !important; opacity: 0.6; }
        .delivery-sign { color: #0288d1; font-weight: 800; font-size: 11px; }

        /* Mobile optimization */
        @media (max-width: 768px) {
            .dashboard-container { margin: 5px; padding: 10px; }
            .table thead { display: none; }
            .table tr { display: flex; flex-direction: row; flex-wrap: wrap; padding: 10px; border-bottom: 1px solid #eee; align-items: center; justify-content: space-between; }
            .table td { display: inline-block; padding: 2px 0; border: none; width: auto; }
            .td-main-info { flex: 1; min-width: 150px; }
            .action-cell { width: 100%; display: flex; justify-content: center; margin-top: 10px; }
        }

        /* PC Centering */
        @media (min-width: 769px) {
            .table th, .table td { text-align: center; vertical-align: middle; }
            .action-group { display: flex; justify-content: center; width: 100%; }
        }

        #scanner-modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9); z-index: 10000; justify-content: center; align-items: center; flex-direction: column;
        }
        #reader { width: 300px; background: white; border-radius: 10px; overflow: hidden; }
    </style>
</head>
<body>
    @include('layouts/staffnavbar')

    <div class="dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold">Active Deliveries</h6>
            <div class="d-flex gap-2">
                <div class="stat-box">Pending: <span id="pending-count">0</span></div>
                <div class="stat-box text-info">Done: <span id="completed-count">0</span></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="orderBody">
                    @foreach($deliveries as $delivery)
                        <tr data-id="{{ $delivery->id }}">
                            <td class="td-main-info"><strong>#{{ $delivery->id }}</strong></td>
                            <td><strong>{{ $delivery->fullname }}</strong></td>
                            <td><small class="text-muted">{{ $delivery->address }}</small></td>
                            <td><span class="badge bg-warning text-dark status-label">Ready</span></td>
                            <td>₹{{ $delivery->totalAmount }}</td>
                            <td class="action-cell">
                                <div class="action-group">
                                    <button class="btn-scan" onclick="startScanner('{{ $delivery->id }}')">
                                        <i class="fas fa-qrcode me-1"></i> Scan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="scanner-modal">
        <div id="reader"></div>
        <button class="btn btn-light mt-4 px-4 fw-bold" onclick="stopScanner()">CANCEL SCAN</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        let html5QrCode;
        let targetOrderId = "";

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
        });

        function startScanner(id) {
            targetOrderId = id;
            document.getElementById('scanner-modal').style.display = 'flex';
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: 250 }, 
                (decodedText) => {
                    stopScanner();
                    showActionMenu(targetOrderId);
                }
            ).catch(err => {
                Swal.fire('Error', 'Camera initialization failed', 'error');
                stopScanner();
            });
        }

        function showActionMenu(id) {
            // After scanning, the staff chooses what happened with the order
            Swal.fire({
                title: `Order #${id}`,
                text: 'Select current delivery status:',
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Delivered',
                denyButtonText: 'Unavailable',
                cancelButtonText: 'Cancelled',
                confirmButtonColor: '#27ae60',
                denyButtonColor: '#f39c12',
                cancelButtonColor: '#e74c3c'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateRowStatus(id, 'Delivered', 'bg-success');
                } else if (result.isDenied) {
                    updateRowStatus(id, 'Unavailable', 'bg-secondary');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    updateRowStatus(id, 'Cancelled', 'bg-danger');
                }
            });
        }

        function updateRowStatus(id, status, badgeClass) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if(!row) return;

            // Update Status Badge
            const label = row.querySelector('.status-label');
            label.className = `badge ${badgeClass} status-label`;
            label.innerText = status;

            // Update Action Cell to show completion
            row.querySelector('.action-cell').innerHTML = `<div class="delivery-sign"><i class="fas fa-check-circle"></i> ${status.toUpperCase()}</div>`;
            row.classList.add('row-completed');

            Toast.fire({ icon: 'success', title: `Status Updated: ${status}` });
            updateCount();
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

        function updateCount() {
            const pending = document.querySelectorAll('#orderBody tr:not(.row-completed)').length;
            const done = document.querySelectorAll('.row-completed').length;
            document.getElementById('pending-count').innerText = pending;
            document.getElementById('completed-count').innerText = done;
        }

        window.onload = updateCount;
    </script>
</body>
</html>