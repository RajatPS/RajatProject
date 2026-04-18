<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Navigation Bar</title>
    
    <style>
        .header-wrapper {
            padding: 20px 0;
            position: relative;
        }

        .nav-glass {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            margin: 0 auto; 
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white; 
        }

        .navbar-nav .nav-link {
            color: white !important; 
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px !important; 
            border-radius: 10px;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-2px);
        }

        .sign-in-btn {
            background: linear-gradient(135deg, #ff6b9d, #ff8a80); 
            color: white;
            border: none;
            padding: 10px 20px; 
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block; 
        }

        .sign-in-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 157, 0.4);
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.7); 
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .dropdown-item {
            font-size: 1rem;
        }

        .dropdown-item-form {
            padding: 0;
        }

        .navbar {
            position: relative;
            z-index: 9999 !important;
        }

        .dropdown-menu {
            z-index: 99999 !important;
        }

        .search-wrapper {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2px 10px;
            width: 300px; /* Adjust width as needed */
            transition: all 0.3s ease;
        }

        .search-wrapper:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .search-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

    </style>
</head>
<body>

<div class="container header-wrapper">
    @auth()
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark nav-glass">
                <div class="container-fluid p-0">
                    <a class="navbar-brand logo me-auto" href="#">PenCart</a>
                    
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAuth" aria-controls="navbarNavAuth" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars text-white"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNavAuth">
                        <form method="POST" action="{{ url('users/search') }}" id="searchForm">
                            @csrf
                            <input type="hidden" name="query" id="searchBarForm">
                        </form>

                        <div class="d-none d-lg-flex mx-auto search-wrapper">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-white opacity-50"><i class="fas fa-search"></i></span>
                                <input class="form-control bg-transparent border-0 text-white shadow-none" 
                                    type="text" id="productSearch" placeholder="Search stationery...">
                            </div>
                        </div>

                        <div id="searchBtndiv" class="ms-2">
                            <button type="button" id="searchBtn" class="btn btn-sm btn-outline-light rounded-pill px-3">search</button>
                        </div>

                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center"> 
                            <li class="nav-item"><a class="nav-link" href="{{url('users/Ucart')}}"><i class="fas fa-shopping-cart me-2"></i>Cart</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{url('users/Uview_Orders')}}"><i class="fas fa-box me-2"></i>Orders</a></li>
                            <li class="nav-item"><a class="nav-link me-4" href="{{url('users/help')}}"><i class="fas fa-question-circle me-2"></i>Help</a></li>

                            <li class="nav-item dropdown">
                                <a class="nav-link p-0 dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://placehold.co/40x40/667eea/ffffff?text=U" alt="ProfilePic" class="profile-pic">
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="profileDropdown">
                                    <li><a href="{{ url('users/Udetails') }}" class="dropdown-item py-2 text-dark"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                                    <li><a href="{{ url('users/Ucart') }}" class="dropdown-item py-2 text-dark"><i class="fas fa-shopping-cart me-2"></i>View Cart</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-item-form px-3">
                                        <form action="{{ url('users/Ulogout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger border-0 bg-transparent p-0 w-100 text-start">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    @else()
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark nav-glass">
                <div class="container-fluid p-0">
                    <a class="navbar-brand logo me-auto" href="#">PenCart</a>
                    <div class="d-flex ms-auto">
                        <a href="{{ url('users/Ulogin') }}" class="sign-in-btn">Login / Sign Up</a>
                    </div>
                </div>
            </nav>
        </header>
    @endauth() 
</div>

<script>

const searchBtn = document.getElementById('searchBtn');

searchBtn.addEventListener('click',function(){
    const searchInput = document.getElementById('productSearch').value;
    const searchForm = document.getElementById('searchForm');   
    const searchvalue = document.getElementById('searchBarForm');
    searchvalue.value = searchInput;
    searchForm.submit();
})

</script>

</body>
</html>

{{-- @livewireScripts --}}