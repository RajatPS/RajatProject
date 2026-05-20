<!-- Sidebar Styles -->
<style>
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        background: linear-gradient(135deg, #4c5e9f 0%, #583c7d 100%); 
        color: var(--light-text-color);
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .logo {
        text-align: center;
        padding: 10px 0 30px;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--accent-color);
        text-shadow: 0 0 5px rgba(255, 107, 157, 0.5);
    }

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

    .nav-item a:hover {
        background-color: var(--sidebar-hover);
        transform: translateX(3px);
    }

    .nav-item.active a {
        background: var(--accent-gradient);
        box-shadow: 0 4px 8px rgba(255, 107, 157, 0.3);
        font-weight: 600;
    }

    .submenu {
        list-style: none;
        padding-left: 10px;
        margin-top: 5px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
        border-radius: 0 0 8px 8px;
    }

    .submenu.open {
        max-height: 200px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
    }

    .submenu a {
        padding: 8px 15px 8px 45px;
        font-size: 0.95rem;
    }

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

    /* Handle mobile state within the sidebar file */
    @media (max-width: 768px) {
        .sidebar {
            width: 0;
            transform: translateX(-100%);
        }
    }
</style>

<aside class="sidebar">
    <div class="logo">
        <span class="logo-text">Pencart Admin</span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item {{ Request::is('admin/adminDashboard') ? 'active' : '' }}">
                <a href="{{ url('admin/adminDashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item {{ Request::is('admin/AdminUserManagement*') ? 'active' : '' }}">
                <a href="{{ url('admin/AdminUserManagement') }}"><i class="fas fa-users"></i> User Management</a>
            </li>
            <li class="nav-item {{ Request::is('admin/Amanageorders*') ? 'active' : '' }}">
                <a href="{{ url('admin/Amanageorders') }}"><i class="fas fa-box"></i> Orders</a>
            </li>
            <li class="nav-item {{ Request::is('admin/Aproducts*') ? 'active' : '' }}">
                <a href="{{ url('admin/Aproducts') }}"><i class="fas fa-th-large"></i> Products</a>
            </li>
            {{-- <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle"><i class="fas fa-chart-line"></i> Reports <i class="fas fa-caret-down"></i></a>
                <ul class="submenu">
                    <li><a href="{{ url('admin/salesSummary') }}">Sales Summary</a></li>
                    <li><a href="#">Traffic Analytics</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-cog"></i> Settings</a>
            </li> --}}
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

<script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'No, stay logged in!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>