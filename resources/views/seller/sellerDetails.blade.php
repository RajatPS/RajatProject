<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            --glass-bg: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.3);
            --accent-color: #ffeaa7; 
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        .shape {
            position: fixed;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            z-index: -1;
            animation: float 8s ease-in-out infinite;
        }
        .shape:nth-child(1) { width: 200px; height: 200px; top: -50px; left: -50px; }
        .shape:nth-child(2) { width: 150px; height: 150px; bottom: 10%; right: 5%; animation-delay: 2s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            max-width: 500px; /* Narrower for vertical flow */
            width: 95%;
            color: white;
            padding: 2.5rem;
        }

        .icon-box {
            width: 50px; height: 50px;
            background: var(--accent-color);
            color: #0984e3;
            border-radius: 12px;
            display: flex;
            align-items: center; justify-content: center;
            margin-bottom: 1rem;
            font-size: 24px;
        }

        .form-label { font-size: 0.85rem; font-weight: 600; opacity: 0.9; margin-bottom: 5px; }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 0.7rem;
            margin-bottom: 1.2rem; /* Spacing between vertical fields */
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 15px rgba(255, 234, 167, 0.4);
        }

        .btn-accent {
            background: var(--accent-color);
            color: #2d3436;
            font-weight: 700;
            border-radius: 10px;
            transition: 0.3s;
            border: none;
            margin-top: 10px;
        }

        .btn-accent:hover {
            background: white;
            color: #0984e3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="shape"></div>
    <div class="shape"></div>
    @if ($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="login-card">
        <div class="icon-box"><i class="fas fa-user-tie"></i></div>
        <h2 class="mb-1">Seller Registration</h2>
        <p class="mb-4 opacity-75">Create your merchant account to start selling.</p>

        <form action="/seller/sellerSubmitDetails" method="POST">
            @csrf
            <div class="d-flex flex-column">

                <label class="form-label">Full Legal Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter your full name">

                <label class="form-label">Business Email</label>
                <input type="email" name="email" class="form-control" required placeholder="email@business.com">

                <label class="form-label">Create Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">

                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">

                <button type="submit" class="btn btn-accent w-100 py-3 shadow mt-3">
                    REGISTER AS SELLER <i class="fas fa-chevron-right ms-2"></i>
                </button>

            </div>
        </form>
    </div>

</body>
</html>