<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Platform</title>
    
    <!-- External Links (Required for the Navbar) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Global Reset and Base Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif; /* Changed to Inter for consistency */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background decorative elements */
        body::before {
            content: '';
            position: absolute;
            top: 10%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: 10%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* ----------------------------------------------------------------- */
        /* NAVBAR STYLING (From previous working file) */
        /* ----------------------------------------------------------------- */
        .header-wrapper {
            padding-top: 20px;
            position: relative;
            z-index: 10;
        }

        .nav-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }
        
        .dropdown-menu {
            z-index: 1000 !important;
        }

        .navbar-brand.logo,
        .navbar-nav .nav-link,
        .navbar-toggler i {
            color: white !important; 
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link {
            padding: 8px 12px !important;
            border-radius: 8px;
            margin: 0 5px;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-2px);
        }
        
        /* FIX: Profile Picture Styling */
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%; /* KEY FIX: Makes the image round */
            object-fit: cover;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.7); 
            transition: border-color 0.2s ease;
        }
        
        /* Ensure dropdown text is readable */
        .dropdown-item {
            color: #333; 
        }

        /* ----------------------------------------------------------------- */
        /* Hero Section */
        /* ----------------------------------------------------------------- */
        .hero {
            text-align: center;
            padding: 80px 0;
            color: white;
            position: relative; /* Ensure it's above background elements */
            z-index: 5;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b9d, #ff8a80);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 107, 157, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 16px 32px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.1);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            margin: 60px 20px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .features h2 {
            text-align: center;
            color: white;
            font-size: 2.5rem;
            margin-bottom: 60px;
            font-weight: 700;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 40px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            color: white;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ff6b9d, #ff8a80);
            border-radius: 15px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .feature-card p {
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 40px 0;
            color: rgba(255, 255, 255, 0.8);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 60px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                gap: 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features {
                margin: 40px 10px;
            }

            .features-grid {
                padding: 0 20px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }

            .nav {
                padding: 15px 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Flash Message Styling */
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: #10b981;
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 400px;
            animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-out 9.5s forwards;
        }

        .flash-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
        }

        .flash-text {
            flex: 1;
            font-size: 14px;
            line-height: 1.5;
        }

        .flash-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
        }

        .flash-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            to { transform: translateX(400px); opacity: 0; }
        }
    </style>
</head>
<body>
@include('layouts.navbar')

@if (session('status'))
    <div class="flash-message success">
        <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flash-text">{{ session('status') }}</div>
        <a href="#" class="flash-close" onclick="this.parentElement.style.display='none'; return false;">×</a>
    </div>
@endif

    <!-- Hero Section -->
    <section class="hero">
        <h1>Welcome to the Future</h1>
        <p>Experience the next generation of digital solutions designed to transform the way you work, connect, and achieve your goals.</p>
        <div class="cta-buttons">
            <a href="Uproducts" class="btn-primary">Get Started/products</a>
            <a href="#learn" class="btn-secondary">Learn More</a>
        </div>
    </section>

    <section class="hero">
        <h1>Our Featured Products</h1>
        
        
        </div>
    </section>
    

    <!-- Features Section -->
    <section class="features">
        <h2>Why Choose Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Lightning Fast</h3>
                <p>Experience blazing fast performance with our optimized infrastructure designed for speed and reliability.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Secure & Private</h3>
                <p>Your data is protected with enterprise-grade security measures and end-to-end encryption.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💡</div>
                <h3>Smart Solutions</h3>
                <p>Intelligent features powered by cutting-edge technology to simplify your workflow and boost productivity.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Global Reach</h3>
                <p>Connect with users worldwide through our robust global network and multi-language support.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Mobile First</h3>
                <p>Seamless experience across all devices with our responsive design and native mobile applications.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛠️</div>
                <h3>Easy Integration</h3>
                <p>Integrate with your existing tools and workflows effortlessly with our comprehensive API and plugins.</p>
            </div>
        </div>
    </section>

    <!-- Footer (Replaces @include('layouts.footer')) -->
    <div class="container">
        <footer class="footer">
            &copy; 2024 Your Platform. All rights reserved.
        </footer>
    </div>
    
    <!-- Bootstrap JS Bundle (Required for Navbar Toggler and Dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
