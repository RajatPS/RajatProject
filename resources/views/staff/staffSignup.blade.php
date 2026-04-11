<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Welcome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            /* Same colors as your staff Signup */
            --primary-gradient: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            --secondary-gradient: linear-gradient(135deg, #00cec9 0%, #00b894 100%);
            --glass-bg: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.3);
            --accent-color: #ffeaa7; /* The same yellow highlight from signup */
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animated Background Elements */
        .animated-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) { width: 150px; height: 150px; top: 10%; left: 5%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 100px; height: 100px; top: 70%; left: 85%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 120px; height: 120px; top: 80%; left: 10%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(90deg); }
        }

        /* Login Card */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
            max-width: 420px;
            width: 100%;
            color: white;
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
        }

        .login-header .icon-box {
            width: 60px; height: 60px;
            background: var(--accent-color);
            color: #0984e3;
            border-radius: 15px;
            display: flex;
            align-items: center; justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .login-header h2 { font-weight: 700; font-size: 1.8rem; margin: 0; }
        .login-header p { opacity: 0.8; font-size: 0.9rem; margin-top: 5px; }

        .login-body { padding: 0 2.5rem 2.5rem; }

        .form-floating { margin-bottom: 1.2rem; }
        
        .form-control {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid transparent;
            border-radius: 12px;
            color: #2d3436;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 15px rgba(255, 234, 167, 0.4);
            background: #fff;
        }

        .form-floating > label { color: #636e72; }

        /* Login Button */
        .btn-login {
            background: var(--accent-color);
            color: #2d3436;
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            color: #0984e3;
        }

        .divider {
            text-align: center; margin: 1.5rem 0;
            position: relative; font-size: 0.8rem;
            opacity: 0.7;
        }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.2); }
        .divider span { background: #079992; padding: 0 10px; position: relative; }

        .social-login { display: flex; gap: 15px; justify-content: center; }
        .social-btn {
            width: 45px; height: 45px;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s;
        }
        .social-btn:hover { background: rgba(255,255,255,0.2); transform: scale(1.1); color: var(--accent-color); }

        .forgot-password { text-align: center; margin-top: 15px; }
        .forgot-password a { color: var(--accent-color); text-decoration: none; font-size: 0.85rem; }

        .signup-link {
            text-align: center; margin-top: 2rem;
            padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);
        }
        .signup-link a { color: var(--accent-color); text-decoration: none; font-weight: 700; }

        /* Alerts */
        .alert-custom {
            position: fixed; top: 20px; right: 20px; z-index: 1000;
            background: #fff; color: #0984e3; border: none; border-radius: 10px;
        }

    </style>
</head>
<body>

    @if ($errors->any())
    <div class="alert alert-light alert-dismissible fade show alert-custom shadow" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="animated-bg">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-card">
        <div class="login-header">
            <div class="icon-box"><i class="fas fa-lock"></i></div>
            <h2>Welcome</h2>
            <p>Please signup to create your store</p>
        </div>
        
        <div class="login-body">
            <form method="POST" action="/staff/sendOtp">
                @csrf
                {{-- <div class="form-floating">
                    <input type="email" class="form-control" name="login_email" id="Inputfield" placeholder="name@example.com" required>
                    <label for="Inputfield"><i class="fas fa-envelope me-2"></i>Email Address</label>
                </div> --}}
                <div class="form-floating">
                    <input type="staff_phone" class="form-control" name="staff_phone" id="Inputfield" placeholder="Phone Number" required>
                    <label for="Inputfield"><i class="fas fa-phone me-2"></i>Phone Number</label>
                </div>
                
                <div class="continueBtn" id="continueBtn">
                    <button type="submit" class="btn btn-login w-100 btn-otp">
                        continue <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
                
            <div class="divider">
                <span>OR LOGIN WITH</span>
            </div>
            
            <div class="social-login">
                <a href="{{ url('/auth/googlesignup') }}" class="social-btn"><i class="fab fa-google"></i></a>
                <a href="{{ url('/auth/facebook') }}" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                {{-- <a href="#" class="social-btn"><i class="fab fa-apple"></i></a> --}}
            </div>
            
            <div class="signup-link">
                <span style="opacity: 0.8;">Already have an account?</span><br>
                <a href="{{url('seller/sellerLogin/')}}">Login here</a>     ////////////////////////////////////////////////////////////////
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>

        // Auto-dismiss alert
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 4000);
    </script>
</body>
</html> 