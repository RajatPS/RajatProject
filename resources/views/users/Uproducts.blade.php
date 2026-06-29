<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PenCart Products</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        
        #page-wrapper {
            background: var(--primary-gradient); /* Applied here now */
            min-height: 100vh;
            color: white; /* Applied here now */
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 107, 157, 0.5);
            border-radius: 50%;
            animation: move-bubble 15s infinite alternate;
            z-index: 0;
            filter: blur(80px);
        }

        @keyframes move-bubble {
            0% { transform: translate(0, 0); }
            50% { transform: translate(200px, 100px); }
            100% { transform: translate(0, 0); }
        }

        /* ----------------------------------------------------------------- */
        /* Hero Section */
        /* ----------------------------------------------------------------- */
        .hero-section {
            position: relative;
            padding: 80px 0 60px;
            text-align: center;
            z-index: 5;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto;
        }

        /* ----------------------------------------------------------------- */
        /* Filter Section */
        /* ----------------------------------------------------------------- */
        .filter-section {
            padding-bottom: 40px;
            z-index: 5;
            position: relative;
        }

        .filter-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            padding: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            color: white; 
        }
        
        .filter-btn {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 8px 18px;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: var(--accent-gradient);
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(255, 107, 157, 0.5);
            color: white;
        }

        /* ----------------------------------------------------------------- */
        /* Products Section & Card Styling (Redesigned) */
        /* ----------------------------------------------------------------- */
        .products-section {
            position: relative;
            z-index: 2;
            padding-bottom: 80px;
        }

        .card.product-card {
            background: var(--glass-bg); 
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            color: white; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); 
        }

        .product-image {
            /* ADJUSTMENT HERE: Increased height from 180px to 220px */
            height: 220px; 
            background: var(--accent-gradient); 
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            /* ADJUSTMENT HERE: Changed to 'contain' to show the full image */
            object-fit: contain; 
            transition: transform 0.5s ease;
            cursor: pointer;
        }
        
        .product-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4.5rem; 
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        .card-body {
            padding: 25px;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: white;
        }

        .card-text {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .price-tag {
            background: var(--accent-gradient);
            color: white;
            padding: 6px 14px;
            border-radius: 50px; 
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 2px 10px rgba(255, 107, 157, 0.4);
            font-size: 1.1rem;
        }

        .rating {
            color: #ffd700;
            font-size: 1.1rem; 
        }
        
        .btn.btn-product {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-product:hover {
            background: var(--accent-gradient);
            border-color: transparent;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.5);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 5px 12px;
            border-radius: 50px; 
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }

        /* // Modal Styling */

        .modal-content.product-modal-content {
            background: var(--primary-gradient); 
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border); 
            color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .btn-close.btn-close-white {
            filter: invert(1); 
        }

        /* Image Div Styling */
        .product-image-large {
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.05); 
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .product-image-large img {
            border-radius: 12px;
            object-fit: contain;
            width: 100%;
            max-height: 400px;
        }

        .modal-button1 {
            background: var(--accent-gradient);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 12px;
            transition: transform 0.2s;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.5);
        }

        .modal-button1:hover {
            background: #ff8a80;
            transform: translateY(-2px);
            opacity: 0.9;
            color: white;
        }

        .modal-button2 {
            background: rgb(36, 146, 248);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            transition: background 0.2s, transform 0.2s;
        }

        .modal-button2:hover {
            background: #0056b3;
            transform: translateY(-1px);
            color: white;
        }

        .modal-button3 {
            background: yellow;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: black;
            font-weight: 500;
            border-radius: 12px;
        }

        .modal-button3:hover {
            background: rgb(144, 245, 21);
            color: black;
            transform: translateY(-1px);
        }

        .category-badge-modal {
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 12px;
            border-radius: 50px; 
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }

    </style>
</head>
<body>

<body>
    @if(!Auth::check())
    <div id="page-wrapper">
        @include('layouts.navbar')
        
        <section class="hero-section">
            <div class="container">
                <h5 class="hero-title">Our Premium Products</h5>
            </div>
        </section>

        <section class="products-section">
            <div class="container">
                <div class="row g-4" id="productsContainer">
                    @foreach ($productsfornewUsers as $product)
                        <div class="col-xl-4 col-lg-6 col-sm-6" data-category="{{ $product->category }}">
                            <div class="card product-card">
                                <div class="product-image" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#productDetailModal"
                                    data-id="{{ $product->id }}" 
                                    data-product-name="{{ $product->product_name }}"
                                    data-product-price="₹ {{ $product->price }}"
                                    data-product-description="{{ $product->description }}"
                                    data-product-category="{{ $product->category }}"
                                    data-product-image="{{ asset('storage/'.optional($product->images->first())->image) }}">
                                    
                                    <img src="{{ asset('storage/'.optional($product->images->first())->image) }}" alt="{{$product->product_name}}" style="object-fit: contain;">
                                    <span class="category-badge">{{$product->category}}</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{$product->product_name}}</h5> 
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="rating">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                            <span class="text-white-50 ms-1 small">(4.5)</span>
                                        </div>
                                        <span class="price-tag">₹ {{$product->price}}</span> 
                                    </div>
                                    <p class="card-text text-truncate">{{$product->description}}</p>
                                    <button type="button" class="btn btn-product mt-auto" onclick="window.location.href='{{ url('users/Ulogin') }}'">
                                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        <section class="hero-section">
            <div class="container">
                <h5 class="hero-title">Login to Access More Products !</h5>
            </div>
        </section>
    </div>

    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content product-modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div id="modalImageCarousel" class="carousel slide product-image-large">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="" id="modal-image-main" class="d-block w-100" alt="Product Image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h2 class="modal-title-text" id="modal-product-name"></h2>
                            <span class="category-badge-modal" id="modal-product-category"></span>
                            
                            <hr class="my-3 modal-divider">
                            <h3 class="price-tag-modal" id="modal-product-price"></h3>
                            
                            <div class="rating my-3">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                <span class="text-white-50 ms-1 small">(4.5)</span>
                            </div>

                            <p class="modal-product-description" id="modal-product-description"></p>
                            <hr class="my-3 modal-divider">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary modal-button1 w-100" onclick="window.location.href='{{ url('users/Ulogin') }}'">
                                    <i class="bi bi-bag-fill me-2"></i> Buy Now
                                </button>

                                <button type="submit" class="btn btn-primary modal-button1 w-100" onclick="window.location.href='{{ url('users/Ulogin') }}'">
                                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                </button>
                                
                                <button type="submit" class="btn modal-button3 w-100" onclick="window.location.href='{{ url('users/Ulogin') }}'">
                                    <i class="fas fa-eye me-2"></i> view product
                                </button>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        
        

    @include('layouts.footer')
        
    @else

    @include('layouts.messages')
    @include('layouts.ajaxMsg')
    
    <div id="page-wrapper" class="pb-4">
        @include('layouts.navbar')






        @if(request()->get('page', 1) == 1 && $Fproducts->isNotEmpty())
        <section class="products-section">
            <section class="hero-section">
                <div class="container">
                    <h5 class="hero-title">Featured Products</h5>
                </div>
            </section>
            <div class="container">
                <div class="row g-4" id="productsContainer">
                    @foreach ($Fproducts as $product)
                        <div class="col-xl-4 col-lg-6 col-sm-6" data-category="{{ $product->category }}">
                            <div class="card product-card">
                                <div class="product-image" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#productDetailModal"
                                    data-id="{{ $product->id }}" 
                                    data-product-name="{{ $product->product_name }}"
                                    data-product-price="₹ {{ $product->price }}"
                                    data-product-description="{{ $product->description }}"
                                    data-product-category="{{ $product->category }}"
                                    data-product-image="{{ asset('storage/'.optional($product->images->first())->image) }}">
                                    
                                    <img src="{{ asset('storage/'.optional($product->images->first())->image) }}" alt="{{$product->product_name}}" style="object-fit: contain;">
                                    <span class="category-badge">{{$product->category}}</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{$product->product_name}}</h5> 
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="rating">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                            <span class="text-white-50 ms-1 small">(4.5)</span>
                                        </div>
                                        <span class="price-tag">₹ {{$product->price}}</span> 
                                    </div>
                                    <p class="card-text text-truncate">{{$product->description}}</p>
                                    <button type="button" class="btn btn-product mt-auto" onclick="addToCart({{ $product->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif



            <section class="hero-section">
                <div class="container">
                    <h5 class="hero-title">All Products</h5>
                </div>
            </section>


            <div class="container">
                <div class="row g-4" id="productsContainer">
                    @foreach ($products as $product)
                        <div class="col-xl-4 col-lg-6 col-sm-6" data-category="{{ $product->category }}">
                            <div class="card product-card">
                                <div class="product-image" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#productDetailModal"
                                    data-id="{{ $product->id }}" 
                                    data-product-name="{{ $product->product_name }}"
                                    data-product-price="₹ {{ $product->price }}"
                                    data-product-description="{{ $product->description }}"
                                    data-product-category="{{ $product->category }}"
                                    data-product-image="{{ asset('storage/'.optional($product->images->first())->image) }}">
                                    
                                    <img src="{{ asset('storage/'.optional($product->images->first())->image) }}" alt="{{$product->product_name}}" style="object-fit: contain;">
                                    <span class="category-badge">{{$product->category}}</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{$product->product_name}}</h5> 
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="rating">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                            <span class="text-white-50 ms-1 small">(4.5)</span>
                                        </div>
                                        <span class="price-tag">₹ {{$product->price}}</span> 
                                    </div>
                                    <p class="card-text text-truncate">{{$product->description}}</p>
                                    <button type="button" class="btn btn-product mt-auto" onclick="addToCart({{ $product->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </section>
    </div>



<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content product-modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div id="modalImageCarousel" class="carousel slide product-image-large">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="" id="modal-image-main" class="d-block w-100" alt="Product Image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h2 class="modal-title-text" id="modal-product-name"></h2>
                            <span class="category-badge-modal" id="modal-product-category"></span>
                            
                            <hr class="my-3 modal-divider">
                            <h3 class="price-tag-modal" id="modal-product-price"></h3>
                            
                            <div class="rating my-3">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                <span class="text-white-50 ms-1 small">(4.5)</span>
                            </div>

                            <p class="modal-product-description" id="modal-product-description"></p>
                            <hr class="my-3 modal-divider">

                            <div class="d-grid gap-2">
                                <form id="buyNowForm" method="POST" action="{{ url('users/Ucheckout') }}">
                                    @csrf
                                    <input type="hidden" name="products" id="modal-input-id-buy">
                                    <button type="submit" class="btn btn-primary modal-button1 w-100">
                                        <i class="bi bi-bag-fill me-2"></i> Buy Now
                                    </button>
                                </form>

                                <button class="btn modal-button2" id="modal-atc-btn">
                                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                </button>
                                
                                <form id="viewProductForm" method="GET" action="{{ url('users/UsingleProduct') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" id="modal-input-id-view">
                                    <button type="submit" class="btn modal-button3 w-100">
                                        <i class="fas fa-eye me-2"></i> view product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /*
     * AJAX Function for adding products to cart
     * Stays outside DOMContentLoaded to be globally accessible
     */
    function addToCart(productId) {
        // Ensure the CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        fetch("{{ route('cart.add') }}", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "X-CSRF-TOKEN": csrfToken ? csrfToken.content : '',
                "Content-Type": "application/json",
            },    
            body: JSON.stringify({ product_id: productId }),          
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Ensure showNotification function exists in your layouts
                if (typeof showNotification === "function") {
                    showNotification('success', data.message);
                } else {
                    alert(data.message);
                }
            } else {
                if (typeof showNotification === "function") {
                    showNotification('error', data.message);
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error("Cart AJAX Error:", error);
        });
    }

   
    document.addEventListener('DOMContentLoaded', () => {
        // ---  DROPDOWN INITIALIZATION ---
        // Manually force dropdowns to work to bypass previous JSON "static" errors
        const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // --- MODAL POPULATION LOGIC ---
        const modalElement = document.getElementById('productDetailModal'); 
        
        if (modalElement) {
            // Modal Text/Image Elements
            const modalName = document.getElementById('modal-product-name');
            const modalCategory = document.getElementById('modal-product-category');
            const modalPrice = document.getElementById('modal-product-price');
            const modalDescription = document.getElementById('modal-product-description');
            const modalImageMain = document.getElementById('modal-image-main');
            
            // Modal Form Inputs & Buttons
            const buyInput = document.getElementById('modal-input-id-buy');
            const viewInput = document.getElementById('modal-input-id-view');
            const atcBtn = document.getElementById('modal-atc-btn');

            modalElement.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget; 
                
                // IMPORTANT: Use 'data-id' here to match the HTML attribute
                const id = trigger.getAttribute('data-id');
                
                // Update text content
                if (modalName) modalName.textContent = trigger.getAttribute('data-product-name');
                if (modalCategory) modalCategory.textContent = trigger.getAttribute('data-product-category');
                if (modalPrice) modalPrice.textContent = trigger.getAttribute('data-product-price');
                if (modalDescription) modalDescription.textContent = trigger.getAttribute('data-product-description');
                if (modalImageMain) modalImageMain.src = trigger.getAttribute('data-product-image');

                // Update hidden inputs for forms
                if (buyInput) buyInput.value = id;
                if (viewInput) viewInput.value = id;

                // Update the Add to Cart button in the modal
                if (atcBtn) {
                    atcBtn.onclick = function() {
                        addToCart(id);
                    };
                }
            });
        }

        // ---  CATEGORY FILTERING LOGIC ---
        // const filterButtons = document.querySelectorAll('.filter-btn');
        // const productCards = document.querySelectorAll('.col-xl-4');
        
        // filterButtons.forEach(button => {
        //     button.addEventListener('click', () => {
        //         const filterValue = button.getAttribute('data-filter').toLowerCase();
                
        //         filterButtons.forEach(btn => btn.classList.remove('active'));
        //         button.classList.add('active');
                
        //         productCards.forEach(card => {
        //             const cardCategory = card.getAttribute('data-category').toLowerCase();
                    
        //             if (filterValue === 'all' || cardCategory.includes(filterValue)) {
        //                 card.style.display = 'block';
        //             } else {
        //                 card.style.display = 'none';
        //             }
        //         });
        //     });
        // });

        // ---  SEARCH BUTTON LOGIC (From your Navbar) ---
        const searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                const searchInput = document.getElementById('productSearch').value;
                const searchForm = document.getElementById('searchForm');   
                const searchvalue = document.getElementById('searchBarForm');
                
                if (searchvalue) searchvalue.value = searchInput;
                if (searchForm) searchForm.submit();
            });
        }
    });
</script>

</body>
</html>