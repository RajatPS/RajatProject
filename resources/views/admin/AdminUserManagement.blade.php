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
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .users-table td {
            color: var(--light-text-color);
            padding: 15px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            vertical-align: middle;
        }

        .users-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.25); 
        }

        .username { font-weight: 600; }
        .userid { color: rgba(255, 255, 255, 0.7); }
        .useremail { color: rgba(255, 255, 255, 0.8); font-size: 0.85rem; }

        .actions-cell button {
            padding: 8px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            color: var(--light-text-color); 
        }

        .status-btn { background: var(--accent-gradient); }
        .delete-btn { background-color: var(--dark-reddish-pink); }

        /* Modal Styling */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); 
        }

        .modal-content {
            background: #fff; /* Solid background for readability */
            border-radius: 10px;
            margin: 10% auto;
            padding: 30px;
            max-width: 400px;
            text-align: center;
            color: var(--dark-text-color);
            position: relative;
        }

        .modal-content select {
            width: 100%;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .save-status-btn {
            background: var(--accent-gradient);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .close-btn {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
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
                <h1><i class="fas fa-users"></i> Manage Users</h1>
                <p>Administration panel for user accounts and permissions.</p>
            </div>

            <a href="javascript:history.back()" class="back-btn">
                Back to Dashboard 
                <i class="fas fa-arrow-left"></i>
            </a>
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
                            <span class="username">{{$user->name}}</span><br>
                            <span class="useremail">{{$user->email}}</span>
                        </td>
                        <td>{{$user->phone_number}}</td>
                        <td>
                            <div class="address-box">
                                <span class="address-city">{{$user->address}}</span><br>
                                <span class="address-pin">{{$user->zip}}</span>
                            </div>
                        </td>
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
    
    <div id="userModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Update User Status</h2>
        <p>Updating ID: <span id="displayUserId" style="font-weight: bold;"></span></p>
        <select id="newUserStatus">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
            <option value="blocked">Blocked</option>
            <option value="banned">Banned</option>
        </select>
        <button type="button" onclick="updateUserStatus()" class="save-status-btn">Save Changes</button>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById("userModal");

        function openUserModal(userId, currentStatus) {
            document.getElementById("displayUserId").textContent = userId;
            document.getElementById("newUserStatus").value = currentStatus;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }

        // Renamed and updated Delete function using SweetAlert2
        function deleteUser(userId) {
            Swal.fire({
                title: "Remove User?",
                text: "All data associated with this user will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff4783",
                cancelButtonColor: "#667eea",
                confirmButtonText: "Yes, delete user!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "/admin/deleteUser"; // Updated endpoint

                    const csrf = document.createElement("input");
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    const idInput = document.createElement("input");
                    idInput.type = "hidden";
                    idInput.name = "userId"; // Updated name
                    idInput.value = userId;

                    form.appendChild(csrf);
                    form.appendChild(idInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Updated Status Change function
        function updateUserStatus(){
            const userId = document.getElementById("displayUserId").textContent;
            const status = document.getElementById("newUserStatus").value;

            Swal.fire({
                title: "Update Status?",
                text: `Change user status to ${status}?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Confirm Update"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "{{ url('admin/updateUserStatus') }}"; // Updated endpoint

                    const csrf = document.createElement("input");
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    const idInput = document.createElement("input");
                    idInput.type = "hidden";
                    idInput.name = "user_id";
                    idInput.value = userId;

                    const statusInput = document.createElement("input");
                    statusInput.type = "hidden";
                    statusInput.name = "status";
                    statusInput.value = status;

                    form.append(csrf, idInput, statusInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function filterTable() {
            let input = document.getElementById("userSearch");
            let filter = input.value.toUpperCase(); 
            let table = document.getElementById("usersTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let rowText = tr[i].textContent || tr[i].innerText;
                tr[i].style.display = rowText.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    </script>
</body>
</html>