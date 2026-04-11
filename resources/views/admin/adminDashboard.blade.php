<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> 
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
        
        /* General Dashboard Colors */
        --sidebar-width: 250px;
        --header-height: 70px;
        --light-text-color: #f0f0f0; /* Primary text color for contrast */
        --dark-text-color: #333; /* For elements on light/white background */
        --sidebar-hover: rgba(255, 255, 255, 0.1);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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

        .dashboard-wrapper {
            display: flex;
        }

        /* ---------------------------------- */
        /* 1. FIXED SIDEBAR STYLING */
        /* ---------------------------------- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed; /* KEY: Fixed sidebar */
            top: 0;
            left: 0;
            z-index: 100;
            /* Use a darker version of the primary gradient for depth */
            background: linear-gradient(135deg, #4c5e9f 0%, #583c7d 100%); 
            color: var(--light-text-color);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        /* Logo */
        .logo {
            text-align: center;
            padding: 10px 0 30px;
            font-size: 1.8rem;
            font-weight: 700;
            /* Use the pink accent color for logo text */
            color: var(--accent-color); 
            text-shadow: 0 0 5px rgba(255, 107, 157, 0.5);
        }

        /* Navigation */
        .sidebar-nav ul {
            list-style: none;
            padding: 0 15px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-item a {
            display: block;
            padding: 12px 15px;
            color: var(--light-text-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-item a i {
            margin-right: 10px;
            width: 20px;
        }

        /* Active and Hover States */
        .nav-item a:hover {
            background-color: var(--sidebar-hover);
            transform: translateX(3px);
        }

        .nav-item.active a {
            background: var(--accent-gradient); /* Highlight active item with pink gradient */
            box-shadow: 0 4px 8px rgba(255, 107, 157, 0.3);
            font-weight: 600;
        }

        /* Submenu Styling (Minimal Dropdown) */
        .submenu {
            list-style: none;
            padding-left: 10px;
            margin-top: 5px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 0 0 8px 8px;
        }
        .submenu.open {
            max-height: 200px;
            background: var(--glass-bg);
            backdrop-filter: blur(5px);
            border: 1px solid var(--glass-border);
        }

        .submenu a {
            padding: 8px 15px 8px 45px;
            font-size: 0.95rem;
        }
        .dropdown-toggle i.fa-caret-down {
            float: right;
            transition: transform 0.3s;
        }

        /* Footer (Logout Button) */
        .sidebar-footer {
            margin-top: auto;
            padding: 20px 15px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .logout-btn {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: var(--dark-reddish-pink);
            color: var(--light-text-color);
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: var(--reddish-pink-color);
        }


        /* 2. MAIN CONTENT STYLING */
        .main-content {
            /* Offset by sidebar width for fixed sidebar */
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            min-height: 100vh;
        }

        /* Header */
        .main-header {
            background: var(--glass-bg); 
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            height: var(--header-height);
            margin-bottom: 20px;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
        }

        .main-header h2 {
            color: var(--light-text-color);
            font-weight: 400;
        }

        .user-profile {
            display: flex;
            align-items: center;
            color: var(--light-text-color);
        }

        .user-profile i {
            font-size: 1.2rem;
            margin-right: 15px;
            cursor: pointer;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .widget {
            background: var(--glass-bg); 
            backdrop-filter: blur(5px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            color: var(--light-text-color);
            position: relative;
            overflow: hidden;
        }

        .widget h3 {
            font-weight: 500;
            margin-bottom: 5px;
        }
        .widget p {
            font-size: 2rem;
            font-weight: 700;
        }
        .widget i {
            position: absolute;
            right: 20px;
            bottom: 10px;
            font-size: 3rem;
            opacity: 0.15;
        }

        /* Widget Color Variations */
        .accent-widget {
            border-left: 5px solid var(--accent-color);
        }
        .primary-widget {
            border-left: 5px solid #667eea;
        }
        .success-widget {
            border-left: 5px solid var(--success-color);
        }

        .large-widget {
            grid-column: 1 / -1;
            min-height: 300px;
        }


        /* 3. RESPONSIVENESS (For Smaller Screens) */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 220px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0px; 
            }

            .sidebar {
                width: 0; 
                padding: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .main-header h2 {
                font-size: 1.2rem;
            }
            .main-header {
                height: 60px;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }    
        
        </style>

</head>
<body>
    <div class="dashboard-wrapper">
        
        <aside class="sidebar">
            <div class="logo">
                <span class="logo-text">Pencart Admin</span>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active">
                        <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('admin/AdminUserManagement')}}"><i class="fas fa-users"></i> User Management</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('admin/Amanageorders')}}"><i class="fas fa-box"></i> Orders (Active)</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('admin/Aproducts')}}"><i class="fas fa-th-large"></i> Products & Inventory</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="dropdown-toggle"><i class="fas fa-chart-line"></i> Reports <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                            <li><a href="{{url('admin/salesSummary')}}">Sales Summary</a></li>
                            <li><a href="#">Traffic Analytics</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="#" class="logout-btn" onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h2>Welcome, Admin!</h2>
                <div class="user-profile">
                    {{-- <i class="fas fa-bell"></i> --}}
                     <a href="{{ url('admin/profile') }}"><img src="avatar.jpg" alt="User Avatar" class="avatar"></a>  {{--add a link to the profile page if needed --}}
                </div>
            </header>

            <section class="dashboard-grid">
                <div class="widget accent-widget">
                    <h3>Total Sales</h3>
                    <p>{{ number_format($sales, 2) }}</p>
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="widget primary-widget">
                    <h3>New Orders</h3>
                    <p>{{ $newOrders }}</p>
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="widget success-widget">
                    <h3>Users Online</h3>
                    <p>{{ $onlineUsers }}</p>
                    <i class="fas fa-users"></i>
                </div>
                
                <div class="large-widget chart-widget">
                    <h3>Recent Activity</h3>
                    <p>Detailed sales chart or log goes here.</p>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(e) {
            e.preventDefault();

            Swal.fire({
                title: "Logout?",
                text: "You will be logged out of the admin panel.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff4783",
                cancelButtonColor: "#667eea",
                confirmButtonText: "Yes, logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // script to toggle dropdown submenu
        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const submenu = item.nextElementSibling;
                submenu.classList.toggle('open');
            });
        });
    </script>
</body>
</html>