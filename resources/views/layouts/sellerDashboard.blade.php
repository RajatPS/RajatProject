<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('title')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            --glass-bg: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.3);
            --light-text-color: #ffffff;
            --table-header-bg: linear-gradient(90deg, #0771c1, #00b0ab);
            --sidebar-width: 260px; /* Matching your sidebar exactly */
            
            --pending: #f39c12;
            --shipped: #3498db;
            --delivered: #2ecc71;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }

        body {
            background: var(--primary-gradient);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--light-text-color);
            display: flex; /* Flex helps align the navbar and container */
        }

        /* --- Sidebar Wrapper --- */
        .sidebar-wrapper {
            width: var(--sidebar-width);
            flex-shrink: 0;
            z-index: 1000;
        }

        /* --- THE FIX: Dashboard Container --- */
        .dashboard-container {
            flex-grow: 1;
            margin-left: 0; 
            min-height: 100vh;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-left: 1px solid var(--glass-border);
            padding: 40px;
            width: 100%;
        }

        /* If your sidebar is fixed, we use margin-left to push the content */
        .main-wrapper {
            display: flex;
            width: 100%;
        }

        /* Auto-Styling for Headings */
        h1, h2, h3 { margin-bottom: 15px; letter-spacing: -0.5px; }

        /* Auto-Styling for Inputs/Selects */
        select, input, button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid var(--glass-border);
            padding: 10px;
            border-radius: 8px;
            outline: none;
        }
        option { color: #333; }

        /* Responsive */
        @media (max-width: 992px) {
            :root { --sidebar-width: 70px; }
        }

    </style>
    @yield('style')
</head>
<body>

    @include('layouts.sellerSidebar')
    @include('layouts.messages')


    <div class="dashboard-container" style="margin-left: var(--sidebar-width);">
        @yield('content')
    </div>
</body>
</html>










{{-- 

    @if(session('success') || session('error'))
        <div id="toast-notification" class="toast {{ session('success') ? 'toast-success' : 'toast-error' }}">
            <div class="toast-icon">
                <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
            </div>
            <div class="toast-body">
                <strong>{{ session('success') ? 'Success!' : 'Error!' }}</strong>
                <p>{{ session('success') ?? session('error') }}</p>
            </div>
            <div class="toast-progress"></div>
        </div>
    @endif












<script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast-notification');
            
            if (toast) {
                // After 5 seconds, start the fade out animation
                setTimeout(() => {
                    toast.style.animation = "fadeOut 0.5s ease forwards";
                    
                    // Remove completely from DOM after animation finishes
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>




.toast {
    position: fixed;
    top: 30px;
    right: 30px;

    width: 350px;
    max-width: 90vw;

    padding: 15px 20px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    gap: 15px;

    z-index: 9999;

    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);

    animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);

    /* VERY IMPORTANT FIX */
    max-height: 2vh;
}

.toast-success { 
    background: rgba(39, 174, 96, 0.95); 
    border-left: 6px solid #1f8b4c;
    max-height: fit-content;
    border-radius: 12px;
}

.toast-error { 
    background: rgba(231, 76, 60, 0.95); 
    border-left: 6px solid #c0392b;
    max-height: fit-content;
    border-radius: 12px;
}

/* Ensure the body of the toast stays small */
.toast-body {
    justify-content: center;
    padding: 20px;
}

.toast-body strong {
    display: block;
    color: #ffffff;
    font-size: 1rem;
    margin: 0;
    line-height: 1.2;
}

.toast-body p {
    color: rgba(255, 255, 255, 0.9);
    margin: 4px 0 0 0;
    font-size: 0.85rem;
    line-height: 1.2;
} --}}