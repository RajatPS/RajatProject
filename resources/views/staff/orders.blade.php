<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Delivery Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .stat-box { background: rgba(0,0,0,0.2); padding: 5px 15px; border-radius: 30px; font-size: 14px; }

        /* Filter Styling */
        .filter-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .form-select-custom {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 14px;
        }

        .table-responsive { 
            border-radius: 12px; 
            background: rgba(255, 255, 255, 0.95); 
            overflow: hidden;
        }
        .table { margin-bottom: 0; color: #333; width: 100%; }
        .table thead { background: #074a7d; color: white; }
        
        /* Blue Row Styling */
        .table tbody tr { background-color: #e3f2fd; transition: 0.3s; }
        .table tbody tr:nth-child(even) { background-color: #bbdefb; }
        .table td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #dee2e6; }

        .btn-action { background: #0984e3; color: white; border: none; padding: 6px 12px; border-radius: 5px; font-size: 13px; }

        /* Monitor / Desktop Styles (Default) */
        @media (min-width: 769px) {
            .table th, .table td { text-align: center; }
            .action-group { display: flex; justify-content: center; gap: 5px; }
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .dashboard-container { margin: 10px; padding: 10px; }
            .table thead { display: none; }
            .table tr { 
                display: block; 
                margin-bottom: 10px; 
                border: 1px solid #ddd; 
                border-radius: 10px; 
                padding: 10px;
                background: #fff !important; 
            }
            .table td { 
                display: flex; 
                justify-content: space-between; 
                padding: 5px 10px; 
                border: none; 
                text-align: right;
            }
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                float: left;
                color: #0984e3;
            }
            .action-cell { border-top: 1px solid #eee !important; margin-top: 5px; justify-content: center !important; }
        }

        .row-completed { opacity: 0.7; background-color: #cfd8dc !important; }
    </style>
</head>
<body>
    @include('layouts/staffnavbar')

    <div class="dashboard-container">
        <div class="filter-section d-flex align-items-center gap-3">
            <label class="small fw-bold">Filter By Date:</label>
            <select class="form-select-custom" id="dateFilter" onchange="filterOrders()">
                <option value="all">All Records</option>
                <option value="0">Today</option>
                <option value="1">Yesterday</option>
                <option value="2">2 Days Ago</option>
                <option value="3">3 Days Ago</option>
                <option value="7">1 Week Ago</option>
                <option value="30">1 Month Ago</option>
                <option value="180">6 Months Ago</option>
                <option value="365">1 Year Ago</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="orderBody">
                    @foreach($deliveries as $delivery)
                        <tr data-id="{{ $delivery->id }}" 
                            data-date="{{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('Y-m-d') : '' }}">
                            
                            <td data-label="Order ID"><strong>#{{ $delivery->id }}</strong></td>
                            <td data-label="Customer"><strong>{{ $delivery->fullname }}</strong></td>
                            <td data-label="Address"><small>{{ $delivery->address }}</small></td>
                            <td data-label="Amount">₹{{ $delivery->totalAmount }}</td>
                            
                            <td data-label="Date">
                                {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('d M, Y') : 'Pending' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterOrders() {
    const filterValue = document.getElementById('dateFilter').value;
    const rows = document.querySelectorAll('#orderBody tr');
    const now = new Date();
    now.setHours(0, 0, 0, 0);

    rows.forEach(row => {
        const dateStr = row.getAttribute('data-date');
        if (filterValue === "all") { row.style.display = ""; return; }
        if (!dateStr) { row.style.display = "none"; return; }

        const deliveryDate = new Date(dateStr);
        deliveryDate.setHours(0, 0, 0, 0);
        
        // Calculate difference in days
        const diffTime = now - deliveryDate;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        // Logic: Show if the delivery happened within the last X days
        if (diffDays <= parseInt(filterValue) && diffDays >= 0) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
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