<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            /* These variables must remain here as they are used by BOTH files */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-color: #ff6b9d;
            --success-color: #10b981;
            --reddish-pink-color: #f55b8e;
            --dark-reddish-pink: #ff4783;
            
            --sidebar-width: 250px;
            --header-height: 70px;
            --light-text-color: #f0f0f0;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        
        body { 
            background: var(--primary-gradient); 
            min-height: 100vh; 
        }

        .dashboard-wrapper { display: flex; }

        /* Main Content Section - Only layout logic for the content area */
        .main-content {
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

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
            color: white;
        }

        .avatar { 
            width: 40px; height: 40px; border-radius: 50%; 
            object-fit: cover; border: 2px solid var(--accent-color); 
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
            color: white; 
            position: relative;
        }

        .widget p { font-size: 2rem; font-weight: 700; }
        .widget i { position: absolute; right: 20px; bottom: 10px; font-size: 3rem; opacity: 0.15; }
        
        .accent-widget { border-left: 5px solid var(--accent-color); }
        .primary-widget { border-left: 5px solid #667eea; }
        .success-widget { border-left: 5px solid var(--success-color); }
        .large-widget { grid-column: 1 / -1; min-height: 300px; }

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
        
        <!-- SIDEBAR -->
        @include('layouts.adminSidebar')

        <main class="main-content">
            <header class="main-header">
                <h2>Welcome, Admin!</h2>
                <div class="user-profile">
                     <a href="{{ url('admin/profile') }}"><img src="avatar.jpg" alt="User Avatar" class="avatar"></a>
                </div>
            </header>

            <section class="dashboard-grid">
                <!-- Total Sales Widget -->
                <div class="widget accent-widget">
                    <h3>Total Sales</h3>
                    <p>₹ {{ number_format($sales, 2) }}</p>
                    <i class="fas fa-rupee-sign"></i>
                </div>

                <!-- New Orders Widget -->
                <div class="widget primary-widget">
                    <h3>New Orders</h3>
                    <p>{{ $newOrders }}</p>
                    <i class="fas fa-shopping-cart"></i>
                </div>

                <!-- Users Online Widget -->
                <div class="widget success-widget">
                    <h3>Users Online</h3>
                    <p>{{ $onlineUsers }}</p>
                    <i class="fas fa-users"></i>
                </div>
                
                {{-- <div class="widget large-widget">
                    <h3>Recent Activity</h3>
                    <hr style="opacity: 0.2; margin: 15px 0;">
                    <p style="font-size: 1rem;">Recent activity logs would be displayed here...</p>
                </div> --}}
            </section>
        </main>
    </div>

    <!-- Scripts stay in main because they handle forms/interactivity on the page -->
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

        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                item.nextElementSibling.classList.toggle('open');
            });
        });
    </script>
</body>
</html>