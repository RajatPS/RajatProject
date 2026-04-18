<style>
    /* Desktop Sidebar/TopNav Style */
    .staff-nav {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .nav-brand {
        font-weight: 800;
        color: white;
        text-decoration: none;
        font-size: 1.4rem;
    }

    .nav-menu {
        display: flex;
        gap: 20px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-link-item {
        color: white;
        text-decoration: none;
        font-weight: 500;
        opacity: 0.8;
        transition: 0.3s;
    }

    .nav-link-item:hover, .nav-link-item.active {
        opacity: 1;
        color: #ffeaa7;
    }

    /* MOBILE VERSION: Floating Bottom Navbar */
    @media (max-width: 768px) {
        .staff-nav {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            top: auto;
            margin-bottom: 0;
            z-index: 1000;
            justify-content: space-around;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .nav-brand { display: none; } /* Hide brand on mobile nav to save space */
        
        .nav-menu {
            width: 100%;
            justify-content: space-around;
        }

        .nav-link-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
        }

        .nav-link-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }
    }
</style>

<nav class="staff-nav">
    <a href="#" class="nav-brand"><i class="fas fa-truck-loading"></i> Staff Panel</a>
    
    <ul class="nav-menu">
        <li><a href="{{ url('staff/Dashboard') }}" class="nav-link-item active"><i class="fas fa-home"></i> <span>Home</span></a></li>
        <li><a href="{{ url('staff/orders') }}" class="nav-link-item"><i class="fas fa-boxes"></i> <span>Orders</span></a></li>
        <li><a href="#" class="nav-link-item"><i class="fas fa-history"></i> <span>History</span></a></li>
        <li><a href="#" class="nav-link-item"><i class="fas fa-user"></i> <span>Profile</span></a></li>
    </ul>
</nav>