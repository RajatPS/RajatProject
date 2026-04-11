<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            /* Your provided colors */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-color: #ff6b9d;
            --success-color: #10b981;
            --reddish-pink-color: #f55b8e;
            --dark-reddish-pink: #ff4783;
            
            /* General site colors based on new theme */
            --dark-text-color: #333;
            --light-text-color: #ffffff;
            --table-header-bg: linear-gradient(90deg, #536fae, #624b89);
            --table-bg: #ffffff; 
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); 
            
            /* Status Colors */
            --processing-color: #f59e0b; 
            --shipped-color: #3b82f6; 
            --cancel-color: #ef4444; 
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--primary-gradient); 
            padding: 20px;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 95%;
            margin: 0 auto;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        /* Header Styling */
        header h1 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        header p {
            color: var(--light-text-color); 
            margin-bottom: 25px;
        }

        /* Search Section */
        .search-section {
            position: relative;
            margin-bottom: 25px;
            max-width: 600px;
        }

        #orderSearch {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            color: var(--dark-text-color);
            background: rgba(255, 255, 255, 0.95);
        }

        #orderSearch:focus {
            border-color: var(--accent-color); 
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.3);
            outline: none;
        }

        #orderSearch::placeholder {
            color: #999;
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #999;
        }

        /* Table Wrapper for Responsiveness */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
        }

        /* Table Styling */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px; 
        }

        .orders-table th {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            table-layout: fixed;
        }

        .orders-table td {                             /* table row design  */
            height: 10vh;
            padding-left: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .orders-table th {
            background: var(--table-header-bg); 
            color: var(--light-text-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .orders-table td {
            color: var(--light-text-color);
            height: 10vh;
            padding-left: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
        }

        /* Table Rows ///////////////////////////////////////////////////////*/
        .orders-table tbody tr {
            width: auto;
            transition: background-color 0.3s, transform 0.3s;
        }

        .orders-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.25); 
        }

                                                /* Product Info */
        .product-info {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: 100%;   /* match parent td */
            width: 100%;    /* match parent td */
            overflow: hidden; /* prevent overflow */
        }

        .product-thumb {
            max-height: 100%;   /* ensures it never exceeds td height */
            max-width: 10vh;    /* optional: limit width proportional to height */
            object-fit: contain; /* keeps proportions */
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.1);
        }


        /* User Info */
        .username {
            font-weight: 600;
        }

        .userid {
            color: rgba(255, 255, 255, 0.7);
        }

        .useremail {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        /* Address Styling */
        .address-city, .address-state {
            font-weight: 600;
            color: var(--light-text-color);
        }
        .address-pin {
            color: rgba(255, 255, 255, 0.7); 
            font-size: 0.85rem;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .status.pending { background-color: var(--reddish-pink-color); }
        .status.processing { background-color: var(--processing-color); }
        .status.shipped { background-color: var(--shipped-color); }
        .status.delivered { background-color: var(--success-color); }
        .status.cancelled { background-color: var(--cancel-color); }

        /* Actions Buttons */
        .actions-cell button {
            padding: 8px 12px;
            margin-right: 5px;
            margin-bottom: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.9rem;
            color: var(--light-text-color); 
        }

        .status-btn {
            background: var(--accent-gradient); 
            box-shadow: 0 4px 10px rgba(255, 107, 157, 0.3);
        }

        .status-btn:hover { 
            opacity: 0.9; 
            transform: translateY(-1px); 
            box-shadow: 0 6px 15px rgba(255, 107, 157, 0.4);
        }

        .delete-btn {
            background-color: var(--dark-reddish-pink); 
            box-shadow: 0 4px 10px rgba(255, 71, 131, 0.3);
        }

        .delete-btn:hover {
            background-color: var(--reddish-pink-color);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(255, 71, 131, 0.4);
        }

        .delete-btn i, .status-btn i { margin-right: 5px; }

        /* Modal Styling */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); 
            padding-top: 60px;
        }

        .modal-content {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            margin: 5% auto;
            padding: 30px;
            max-width: 400px;
            text-align: center;
            color: var(--light-text-color);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.5);
            position: relative; /* Added for close button positioning */
        }

        .modal-content h2 {
            color: var(--light-text-color);
            margin-bottom: 15px;
        }

        .modal-content p {
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            font-size: 1rem;
            color: var(--light-text-color);
            background: var(--glass-bg);
            backdrop-filter: blur(5px);
        }

        .modal-content select option {
            color: var(--dark-text-color);
            background: rgba(102, 126, 234, 0.95);
        }

        .close-btn {
            color: var(--light-text-color);
            position: absolute; /* Changed to absolute for better control */
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: var(--accent-color);
            text-decoration: none;
        }

        .save-status-btn {
            background: var(--accent-gradient);
            color: var(--light-text-color);
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(255, 107, 157, 0.3);
            transition: all 0.2s;
        }
        
        .save-status-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(255, 107, 157, 0.4);
        }
        
        .back-btn {
            width: 15%;
            display: block;
            text-align: center;
            padding: 10px;
            background-color: var(--dark-reddish-pink);
            color: var(--light-text-color);
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-btn:hover {
            background-color: var(--reddish-pink-color);
        }

        .header-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.header-container h1 {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2.2rem;
    margin-bottom: 5px;
}

.header-container p {
    color: var(--light-text-color); 
    margin-bottom: 0;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background-color: var(--dark-reddish-pink);
    color: var(--light-text-color);
    border-radius: 8px;
    text-decoration: none;
    transition: background-color 0.3s;
    white-space: nowrap;
    height: fit-content;
}

.back-btn:hover {
    background-color: var(--reddish-pink-color);
}
    </style>
</head>
<body>
    <div class="dashboard-container">
            <div class="header-container">
                <div>
                    <h1><i class="fas fa-box-open"></i> Manage Orders</h1>
                    <p>Overview of all current orders placed by users.</p>
                </div>
                
                <a href="javascript:history.back()" class="back-btn">
                    Back to Dashboard 
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="search-section">
                <input type="text" id="orderSearch" placeholder="Search orders by Username, ID, City, Status, etc..." onkeyup="filterTable()">
                <i class="fas fa-search search-icon"></i>
            </div>

        <div class="table-wrapper">
            <table class="orders-table" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Image</th>
                        <th>Qty</th>
                        <th>Total ($)</th>
                        <th>Payment</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr data-status="Pending">
                        <td data-label="Order ID">{{$order->id}}</td>
                        <td data-label="User">
                            <span class="username">{{$order->fullname}}</span> (<span class="userid">{{$order->user_id}}</span>)<br>
                            <span class="useremail">{{$order->email}}</span>
                        </td>
                        <td data-label="Product">
                            <div class="product-info">
                                @if($order->product && $order->product->images->count() > 0)
                                    @foreach($order->product->images as $image)
                                            <img src="{{ asset('storage/'.$image->image) }}" alt="Product Image"class="product-thumb">
                                      
                                    @endforeach
                                @else
                                    <span>No image</span>
                                @endif

                            </div>
                        </td>
                        <td data-label="Quantity">{{$order->quantity}}</td>
                        <td data-label="Total">{{$order->totalAmount}}</td>
                        <td data-label="Payment">{{$order->paymentMethod}}</td>
                        <td data-label="Address">
                            <div class="address-box">
                                <span class="address-city">{{$order->city}}</span>,
                                <span class="address-state">{{$order->state}}</span>
                                <br>
                                <span class="address-pin">{{$order->zip}}</span>
                            </div>
                        </td>
                        <td data-label="Contact">{{$order->contact_number}}</td>
                        <td data-label="Status">
                            <span class="status pending">{{$order->status}}</span>
                        </td>
                        <td data-label="Actions" class="actions-cell">
                            <button class="status-btn" onclick="openStatusModal({{$order->id}})"><i class="fas fa-edit"></i> Alter Status</button>
                            <button type="button" class="delete-btn" onclick="delfunc({{$order->id}})"><i class="fas fa-trash-alt"></i> Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="statusModal" class="modal">
      <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Update Order Status</h2>
        <p>Order ID: <span id="modalOrderId"></span></p>
        <select id="newStatus">
          <option value="Pending">Pending</option>
          <option value="Processing">Processing</option>
          <option value="Shipped">Shipped</option>
          <option value="Delivered">Delivered</option>
          <option value="Cancelled">Cancelled</option>
        </select>
        <button type="button" onclick="changestat()" class="save-status-btn">Save Changes</button>
      </div>
    </div>

    <script>
        // Select Modal elements
        var modal = document.getElementById("statusModal");
        var span = document.getElementsByClassName("close-btn")[0];

        // 1. Close modal when clicking the "X"
        span.onclick = function() {
            modal.style.display = "none";
        }

        // 2. Close modal when clicking anywhere outside of the modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // 3. (Optional) Close modal when pressing 'Escape' key
        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.keyCode == 27) {
                modal.style.display = "none";
            }
        };

        function delfunc(orderId) {
            if (!confirm("Are you sure?")) return;
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "/admin/deleteOrder";
                const csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = "{{ csrf_token() }}";
                const orderIdInput = document.createElement("input");
                orderIdInput.type = "hidden";
                orderIdInput.name = "orderId";
                orderIdInput.value = orderId;
                form.appendChild(csrf);
                form.appendChild(orderIdInput);
                document.body.appendChild(form);
                form.submit();
        }

        function openStatusModal($orderId){
            const orderIdDisplay = document.getElementById("modalOrderId");
            orderIdDisplay.textContent = $orderId;
            modal.style.display = "block";
        }

        function changestat(){
            const orderId = document.getElementById("modalOrderId").textContent;
            const orderStatus = document.getElementById("newStatus").value;
            if (!confirm("are you sure you want to change status ?")) return;

            const form = document.createElement("form");
            form.method="POST";
            form.action="{{ url('admin/orderStatus') }}";

            const csrf = document.createElement("input");
            csrf.type = "hidden";
            csrf.name = ("_token");
            csrf.value = "{{ csrf_token() }}";

            const idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "order_id";
            idInput.value = orderId;

            const statusInput = document.createElement("input");
            statusInput.type = "hidden";
            statusInput.name="status";
            statusInput.value=orderStatus;

            form.append(csrf,idInput,statusInput);
            document.body.appendChild(form);
            form.submit();
        }

        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("orderSearch");
            filter = input.value.toUpperCase(); 
            table = document.getElementById("ordersTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>