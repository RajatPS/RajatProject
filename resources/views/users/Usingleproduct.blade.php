<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product - {{ $product->product_name }}</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            min-height: 100vh;
        }

        /* Glassmorphism Container */
        .glass-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 40px;
            margin: 50px auto;
            max-width: 1200px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        /* Image Section */
        .main-img-holder {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .main-img-holder:hover {
            border-color: #fd79a8;
            box-shadow: 0 15px 35px rgba(253, 121, 168, 0.3);
        }
        .main-img-holder img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Pricing & Details */
        .price-tag {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fdcb6e 0%, #fd79a8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Purple Gradient Button - Add to Cart */
        .btn-gradient {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
        }
        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.5);
            color: white;
        }

        /* Pink Gradient Button - Buy Now */
        .btn-pink-gradient {
            background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 121, 168, 0.3);
        }
        .btn-pink-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(253, 121, 168, 0.5);
            color: white;
        }

        /* Reviews Section */
        .review-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .review-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }
        .stars { color: #fdcb6e; }

        /* Related Products */
        .related-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            color: white;
            display: block;
        }
        .related-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-8px);
            border-color: #fd79a8;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .related-img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 10px;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
        }
        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        .breadcrumb-item a:hover {
            color: #fd79a8;
        }

        /* Review Form */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #a29bfe;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(162, 155, 254, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Custom Alert Styles */

        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Success specific (Greenish-Cyan accent) */
        .alert-glass-success {
            border-left: 5px solid #2ecc71;
        }

        /* Error specific (Pinkish-Red accent) */
        .alert-glass-error {
            border-left: 5px solid #fd79a8;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        .alert-glass-success .alert-icon { color: #2ecc71; }
        .alert-glass-error .alert-icon { color: #fd79a8; }

        .alert-content h6 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .alert-content p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @keyframes slideInRight {
            from { transform: translateX(120%); }
            to { transform: translateX(0); }
        }

        /* Rating Input Styles */
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            cursor: pointer;
            width: 30px;
            height: 30px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.4)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'%3E%3C/polygon%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
            transition: all 0.2s ease;
        }

        /* Color stars when checked or hovered */
        .rating-input input:checked ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23fdcb6e' stroke='%23fdcb6e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'%3E%3C/polygon%3E%3C/svg%3E");
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    @include('layouts.navbar')

    @if (session('success'))
    <div class="custom-alert alert-glass-success" id="auto-close-alert">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <h6>Success!</h6>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="custom-alert alert-glass-error" id="auto-close-alert">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <h6>Oops!</h6>
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

<div class="container">
    <div class="glass-container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="main-img-holder">
                    @if(isset($product->images) && $product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="{{ $product->product_name }}">
                    @else
                        <i class="fas fa-image" style="font-size: 5rem; color: rgba(255,255,255,0.2);"></i>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/Uproducts"><i class="fas fa-home me-1"></i> Shop</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $product->category ?? 'Products' }}</li>
                    </ol>
                </nav>
                
                <h1 class="display-5 fw-bold mb-2">{{ $product->product_name }}</h1>
                
                <div class="stars mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="text-white-50 ms-2">(4.5 / 5 Rating)</span>
                </div>

                <div class="price-tag mb-4">₹{{ number_format($product->price, 2) }}</div>
                
                <p class="text-white-50 mb-4" style="line-height: 1.8;">
                    {{ $product->description ?? 'High-quality product crafted with precision and care. Perfect for all your needs with superior performance and durability.' }}
                </p>

                <div class="d-flex gap-3 mb-5">
                    <button class="btn btn-gradient btn-lg rounded-pill" id="add-to-cart-btn" data-product-id="{{$product->id}}">
                        <i class="fas fa-cart-plus me-2"></i> Add to Cart
                    </button>


                    
                    <button type="submit" class="btn btn-pink-gradient btn-lg rounded-pill" onclick="document.getElementById('buyNow-btn').submit()">
                        <i class="fas fa-bag-shopping me-2"></i> Buy Now
                    </button>

                    <form action="{{ url('users/Ucheckout') }}" method="POST" id="buyNow-btn">
                        @csrf
                        <input type="hidden" name="products" value="{{ $product->id }}">
                    </form>


                    
                    {{-- <button class="btn btn-outline-light btn-lg rounded-pill">
                        <i class="far fa-heart"></i>
                    </button> --}}
                    
                </div>

                <hr class="opacity-25">
                <small class="text-white-50">Category: <strong>{{ $product->category ?? 'Stationery' }}</strong></small>
            </div>
        </div>

        <div class="mt-4 p-4 border border-secondary rounded-4">
            <h5 class="mb-3"><i class="fas fa-pen-to-square me-2"></i>Leave a Review</h5>
            <form method="POST" action="{{route('addReview')}}" id="addReview-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" class="form-control" name="user_name" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Your Review</label>
                    <textarea class="form-control" rows="3" name="user_review" placeholder="Share your experience..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Rate this Product</label>
                    <div class="rating-input" id="ratingInput">
                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"></label>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-paper-plane me-2"></i>Submit Review
                </button>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
            </form>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4"><i class="fas fa-comment-dots text-warning me-2"></i> Customer Reviews</h3>

                @foreach($Allreviews as $review)
                
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0"><strong>{{ $review->user_name }}</strong></h6>
                           <span class="text-white-50 small">
                                {{ \Carbon\Carbon::parse($review->time)->diffForHumans() }}
                            </span>
                        </div>
                        <div class="stars small my-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="fas fa-star"></i> @else
                                    <i class="far fa-star text-white-50"></i> @endif
                            @endfor
                        </div>
                        <p class="mb-0 text-white-50">{{ $review->review }}</p>
                    </div>
                
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-close alert after 5 seconds

    
    document.addEventListener('DOMContentLoaded', function() {
        function dismissAlert() {
            const alert = document.getElementById('auto-close-alert');
            if (alert) {
                alert.style.transition = "all 0.4s ease";
                alert.style.opacity = "0";
                alert.style.transform = "translateX(50px)";
                setTimeout(() => alert.remove(), 400);
            }
        }

            const alertExists = document.getElementById('auto-close-alert');
            if (alertExists) {
                setTimeout(dismissAlert, 10000);
            }

        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const token = document.querySelector('input[name="_token"]').value;

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomAlert('success', data.message);
            } else {
                showCustomAlert('error', data.message || 'Could not add to cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCustomAlert('error', 'Something went wrong.');
        });
    });

    function showCustomAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `custom-alert alert-glass-${type}`;
        alertDiv.id = "session-alert";
        alertDiv.innerHTML = `
            <div class="alert-icon"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i></div>
            <div class="alert-content">
                <h6>${type === 'success' ? 'Success!' : 'Oops!'}</h6>
                <p>${message}</p>
            </div>
        `;
        document.body.appendChild(alertDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.style.opacity = "0";
            setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
    }

    const form = document.getElementById('addReview-form');

    form.addEventListener('submit', function(e){

        const rating = document.querySelector('input[name="rating"]:checked');

        if(!rating){

            e.preventDefault();

            document.getElementById('rating-error').innerText =
                'Please rate the product before submitting review';

            return;
        }

    });
});
</script>
</body>
</html>