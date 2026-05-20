<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Management</title>
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
            
            /* Match these to your Sidebar variables */
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

        /* NEW: Wrapper to hold sidebar and content */
        .dashboard-wrapper {
            display: flex;
        }

        /* NEW: Main content area shifted to the right */
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

        /* ... Rest of your existing styles ... */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; 
            margin-bottom: 30px;
        }

        .header-container h1 {
            color: white; /* Changed from transparent to show better on purple */
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        .header-container p {
            color: var(--light-text-color); 
            margin-bottom: 0;
        }

        .search-section {
            position: relative;
            margin-bottom: 25px;
            max-width: 600px;
        }

        #userSearch {
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

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #999;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px; 
        }

        .users-table th {
            padding: 12px 15px;
            text-align: left;
            background: var(--table-header-bg); 
            color: var(--light-text-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .users-table td {
            color: var(--light-text-color);
            padding: 15px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .actions-cell button {
            padding: 8px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
        }

        .status-btn { background: var(--accent-gradient); }
        .delete-btn { background-color: var(--dark-reddish-pink); 
            margin-top: 5px;    
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
        }

        /* Responsive Fix */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <div class="dashboard-wrapper">
        <!-- Sidebar included here -->
        @include('layouts.adminSidebar')

        <!-- Main Content wrapped in the shifting container -->
        <main class="main-content">
            <div class="dashboard-container">
                <div class="header-container">
                    <div>
                        <h1><i class="fas fa-users"></i> Manage Users</h1>
                        <p>Administration panel for user accounts and permissions.</p>
                    </div>
                </div>

                <div class="search-section">
                    <input type="text" id="userSearch" placeholder="Search by name, email, address..." onkeyup="filterTable()">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <div class="table-wrapper">
                    <table class="users-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>User Details</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Last Seen</th>
                                <th>Account Created</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>
                                    <span class="username" style="font-weight: bold;">{{$user->name}}</span><br>
                                    <span class="useremail" style="opacity: 0.8; font-size: 0.85rem;">{{$user->email}}</span>
                                </td>
                                <td>{{$user->phone_number}}</td>
                                <td>{{$user->address}}</td>
                                <td>{{$user->DOB}}</td>
                                <td>{{$user->gender}}</td>
                                <td>{{$user->LastSeen}}</td>
                                <td>{{$user->created_at}}</td>
                                <td>{{$user->account_status}}</td>
                                <td class="actions-cell">
                                    <button class="status-btn" onclick="openUserModal('{{$user->id}}', '{{$user->account_status}}')">
                                        <i class="fas fa-user-shield"></i> Status
                                    </button>
                                    <button type="button" class="delete-btn" onclick="deleteUser('{{$user->id}}')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
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

        function openUserModal(userId, currentStatus) {
            Swal.fire({
                title: 'Change User Status',
                text: `Current status: ${currentStatus}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: currentStatus === 'active' ? 'Deactivate User' : 'Activate User',
                cancelButtonText: 'Cancel',
                background: '#fff',
                color: '#333',
                customClass: {
                    popup: 'swal2-border-radius'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/users/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success', data.message, 'success').then(() => {
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
            });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
                background: '#fff',
                color: '#333',
                customClass: {
                    popup: 'swal2-border-radius'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/admin/deleteUser', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'An error occurred while deleting the user.', 'error');
                    });
                }
            });
        }

        function filterTable() {
            const input = document.getElementById('userSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length - 1; j++) { 
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }

        
    </script>
</body>
</html>