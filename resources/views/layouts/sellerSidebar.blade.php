<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
        --glass-bg: rgba(255, 255, 255, 0.12);
        --glass-border: rgba(255, 255, 255, 0.3);
        --sidebar-width: 260px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }

    body {
        background: var(--primary-gradient);
        min-height: 100vh;
        color: #ffffff;
        display: flex;
    }

    /* --- SIDEBAR STYLE ONLY --- */
    .sidebar {
        width: var(--sidebar-width);
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
        border-right: 1px solid var(--glass-border);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        padding: 30px 0;
        z-index: 100;
    }

    .sidebar-brand {
        padding: 0 25px 30px;
        font-size: 1.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-menu {
        list-style: none;
        flex-grow: 1;
    }

    .menu-item {
        padding: 15px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: 0.3s;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }

    .menu-item:hover, .menu-item.active {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-left: 4px solid #ffeaa7;
    }

    .menu-item i {
        width: 20px;
        font-size: 1.1rem;
        text-align: center;
    }

    .sidebar-footer {
        padding-top: 20px;
        border-top: 1px solid var(--glass-border);
    }

    /* --- CONTENT AREA SPACING --- */
    .main-content {
        margin-left: var(--sidebar-width); /* Prevents content from hiding behind sidebar */
        flex-grow: 1;
        padding: 40px;
        width: calc(100% - var(--sidebar-width));
    }

    /* Responsive: Sidebar turns into icons on small screens */
    @media (max-width: 992px) {
        :root { --sidebar-width: 70px; }
        .sidebar-brand span, .menu-item span { display: none; }
        .sidebar { align-items: center; }
        .menu-item { justify-content: center; padding: 20px 0; }
    }

    .menu-item {
        border-left: 4px solid transparent; /* Prevents layout shifting when active */
        transition: all 0.3s ease-in-out;
    }
</style>

    <nav class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-store"></i> <span>PEN CART</span>
        </div>
        
        <div class="sidebar-menu">

        <a href="/seller/sellerDashboard" class="menu-item {{ Request::is('seller/sellerDashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>

        <a href="/seller/products" class="menu-item {{ Request::is('seller/products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>My Products</span>
        </a>

        <a href="/seller/orders" class="menu-item {{ Request::is('seller/orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> <span>Orders</span>
        </a>

        <a href="/seller/analytics" class="menu-item {{ Request::is('seller/analytics*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> <span>Analytics</span>
        </a>

        <a href="/seller/review" class="menu-item {{ Request::is('seller/review*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> <span>Reviews</span>
        </a>

        <a href="/seller/payment" class="menu-item {{ Request::is('seller/payment*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> <span>Payments</span>
        </a>

        <a href="/seller/settings" class="menu-item {{ Request::is('seller/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> <span>Settings</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="#" class="menu-item" onclick="confirmLogout(event)">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
        <form method="POST" action="{{url('seller/logout')}}" id="logout-form">
            @csrf
        </form>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout(e) {
            e.preventDefault();

            Swal.fire({
                title: "Logout?",
                text: "You will be logged out of the  Seller Dashboard.",
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
</script>
