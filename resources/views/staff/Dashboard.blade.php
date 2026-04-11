<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard | Staff</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            margin: auto;
            width: 100%;
            max-width: 1100px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .staff-info h2 { margin: 0; font-weight: 700; }
        .staff-info p { margin: 0; color: #ffeaa7; font-size: 14px; }

        /* Table Styling */
        .table-responsive {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            color: #333;
        }

        .table { margin-bottom: 0; vertical-align: middle; }
        .table thead { background: #2d3436; color: white; }
        .table th { padding: 15px; font-size: 14px; text-transform: uppercase; border: none; }
        .table td { padding: 15px; border-color: #eee; }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }
        .bg-out { background: #fdcb6e; color: #000; }
        .bg-delivered { background: #55efc4; color: #000; }
        .bg-cancelled { background: #ff7675; color: #fff; }

        /* Action Buttons */
        .btn-deliver {
            background: #0984e3;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-deliver:hover { background: #074b81; color: white; transform: translateY(-2px); }

        .dropdown-toggle::after { display: none; }
        .action-more {
            background: #f1f2f6;
            color: #2d3436;
            border: 1px solid #dfe6e9;
            padding: 8px 12px;
            border-radius: 8px;
        }

        /* Scanner Overlay */
        #scanner-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            display: none;
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .scanner-box {
            width: 280px; height: 280px;
            border: 4px solid #00cec9;
            border-radius: 20px;
            position: relative;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .scanner-line {
            width: 100%; height: 2px; background: #ff7675;
            position: absolute; top: 0;
            animation: scan 2s infinite linear;
        }
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="header-flex">
            <div class="staff-info">
                <h2>My Deliveries</h2>
                <p><i class="fas fa-user-circle"></i> Welcome, Pranay (ID: #DB-99)</p>
            </div>
            <div class="stats d-flex gap-3">
                <div class="text-center">
                    <h4 class="mb-0">12</h4>
                    <small>Pending</small>
                </div>
                <div class="text-center">
                    <h4 class="mb-0" style="color: #55efc4;">08</h4>
                    <small>Completed</small>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer / Address</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#ORD-7721</strong></td>
                        <td>
                            <strong>John Doe</strong><br>
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Falakata, Ward 5, House 12</small>
                        </td>
                        <td><span class="badge-status bg-out">Out for Delivery</span></td>
                        <td><span class="text-success fw-bold">Paid</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-deliver" onclick="openScanner()">
                                    <i class="fas fa-qrcode"></i> Deliver
                                </button>
                                <div class="dropdown">
                                    <button class="action-more dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-clock text-warning me-2"></i> Out for Delivery</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-times-circle text-danger me-2"></i> Not Delivered</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-ban text-secondary me-2"></i> Cancelled</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="tel:0000000000"><i class="fas fa-phone me-2"></i> Call Customer</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><strong>#ORD-8845</strong></td>
                        <td>
                            <strong>Amit Sutradhar</strong><br>
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Birpara, Main Road</small>
                        </td>
                        <td><span class="badge-status bg-delivered">Delivered</span></td>
                        <td><span class="text-danger fw-bold">COD: ₹450</span></td>
                        <td class="text-center">
                            <span class="text-muted"><i class="fas fa-check-double"></i> Completed</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="scanner-overlay">
        <div class="scanner-box">
            <div class="scanner-line"></div>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Example" style="width: 100%; opacity: 0.3; filter: grayscale(1);">
        </div>
        <h3 class="text-white">Scanning Barcode...</h3>
        <p class="text-white-50">Align the code inside the box</p>
        <button class="btn btn-outline-light mt-4" onclick="closeScanner()">Close Scanner</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function openScanner() {
            document.getElementById('scanner-overlay').style.display = 'flex';
        }
        function closeScanner() {
            document.getElementById('scanner-overlay').style.display = 'none';
        }
    </script>
</body>
</html>