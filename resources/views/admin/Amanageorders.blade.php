<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-color: #ff6b9d;
            --success-color: #10b981;
            --reddish-pink-color: #f55b8e;
            --dark-reddish-pink: #ff4783;
            --dark-text-color: #333;
            --light-text-color: #ffffff;
            --table-header-bg: linear-gradient(90deg, #536fae, #624b89);
            --processing-color: #f59e0b; 
            --shipped-color: #3b82f6; 
            --cancel-color: #ef4444; 
            
            /* Sidebar Width variable */
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--primary-gradient); 
            min-height: 100vh;
        }

        /* LAYOUT WRAPPERS */
        .dashboard-wrapper {
            display: flex;
        }

        .main-content {
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width));
            padding: 40px 20px;
            transition: all 0.3s ease;
        }

        .dashboard-container {
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        /* HEADER & SEARCH */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .header-container h1 {
            color: white;
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

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
            background: rgba(255, 255, 255, 0.95);
            color: var(--dark-text-color);
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #999;
        }

        /* TABLE STYLING */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px; 
        }

        .orders-table th {
            padding: 12px 15px;
            background: var(--table-header-bg); 
            color: var(--light-text-color);
            text-transform: uppercase;
            font-size: 0.85rem;
            text-align: left;
        }

        .orders-table td {
            color: var(--light-text-color);
            padding: 15px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
        }

        .product-thumb {
            height: 60px;
            width: 60px;
            object-fit: contain;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* BUTTONS & STATUS */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }

        .status.pending { background-color: var(--reddish-pink-color); }
        .status-btn { background: var(--accent-gradient); color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .delete-btn { background-color: var(--dark-reddish-pink); color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--dark-reddish-pink);
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }

        /* MOBILE RESPONSIVENESS */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* MODAL STYLES */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
        .modal-content { background: #fff; margin: 10% auto; padding: 30px; width: 90%; max-width: 400px; border-radius: 12px; text-align: center; color: #333; }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        @include('layouts.adminSidebar')

        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-container">
                <div class="header-container">
                    <div>
                        <h1><i class="fas fa-box-open"></i> Manage Orders</h1>
                        <p>Overview of all current orders placed by users.</p>
                    </div>
                    
                </div>

                <div class="search-section">
                    <input type="text" id="orderSearch" placeholder="Search orders..." onkeyup="filterTable()">
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
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>
                                    <span style="font-weight: 600;">{{$order->fullname}}</span><br>
                                    <span style="font-size: 0.8rem; opacity: 0.7;">{{$order->email}}</span>
                                </td>
                                <td>
                                    @if($order->product && $order->product->images->first())
                                        <img src="{{ asset('storage/'.$order->product->images->first()->image) }}" class="product-thumb">
                                    @endif
                                </td>
                                <td>{{$order->quantity}}</td>
                                <td>₹{{$order->totalAmount}}</td>
                                <td>{{$order->paymentMethod}}</td>
                                <td>{{$order->city}}, {{$order->state}}</td>
                                <td>{{$order->contact_number}}</td>
                                <td><span class="status pending">{{$order->status}}</span></td>
                                <td>
                                    <button class="status-btn" onclick="openStatusModal({{$order->id}})">Alter</button>
                                    <button class="delete-btn" onclick="delfunc({{$order->id}})">Del</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       function openStatusModal(orderId, currentStatus) {
            Swal.fire({
                title: 'Update Status',
                html: `Best updating status for <strong>Order #${orderId}</strong>`,
                input: 'select',
                inputOptions: {
                    'Pending': 'Pending',
                    'Processing': 'Processing',
                    'Shipped': 'Shipped',
                    'Delivered': 'Delivered'
                },
                inputValue: currentStatus || 'Pending', 
                inputPlaceholder: 'Select status',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to choose a status!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let selectedStatus = result.value;
                    changestat(orderId, selectedStatus);
                }
            });
        }

        function changestat(orderId, newStatus) {
            fetch(`/admin/orders/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
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
                Swal.fire('Error', 'An error occurred while updating status.', 'error');
            });
        }

        function delfunc(orderId){
            Swal.fire({
                title:"Are you sure?",
                text:"Once deleted, you will not be able to recover this order!",
                icon:"warning",
                showCancelButton:true,
                confirmButtonColor:"#3085d6",   
                cancelButtonColor:"#d33",
                confirmButtonText:"Yes, delete it!",
                cancelButtonText:"No, cancel!",
            }).then((result)=>{
                if(result.isConfirmed){
                    fetch(`/admin/deleteOrder/`, {
                        method:'POST',
                        headers:{
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: orderId })
                    }).then(response=>response.json())
                    .then(data=>{
                        if(data.success){
                            Swal.fire("Deleted!", data.message, "success").then(()=>{
                                location.reload();
                            });
                        }else{
                            Swal.fire("Error", data.message, "error");
                        }   
                    }).catch(error=>{
                        console.error('Error:', error);
                        Swal.fire("Error", "An error occurred while deleting the order.", "error");
                    });
                }else{
                    Swal.fire("Cancelled", "Order couldn't be deleted.", "info");
                }
            })
        }

        function filterTable() {
            const input = document.getElementById('orderSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('ordersTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let rowText = '';
                for (let j = 0; j < cells.length; j++) {
                    rowText += cells[j].textContent.toLowerCase() + ' ';
                }
                if (rowText.includes(filter)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

    </script>
</body>
</html>