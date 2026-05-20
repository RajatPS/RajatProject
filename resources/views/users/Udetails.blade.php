<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Scoped styles for user details page only */
        .user-details-page {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Removed min-height so content dictates full height, but ensure scrolling works */
            min-height: 100vh; 
            padding: 40px 20px;
            position: relative;
            /* FIX: Removed overflow: hidden; to allow scrolling */
        }

        /* Animated Background Spheres (Position FIXED so they don't scroll with content) */
        .user-page-sphere {
            position: fixed; /* Keep spheres fixed in the background viewport */
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: floatSphere 20s infinite ease-in-out;
            z-index: 1;
            pointer-events: none;
        }

        .user-page-sphere:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
            animation-duration: 25s;
        }

        .user-page-sphere:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 50%;
            right: -100px;
            animation-delay: 2s;
            animation-duration: 20s;
        }

        .user-page-sphere:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: -75px;
            left: 20%;
            animation-delay: 4s;
            animation-duration: 22s;
        }

        .user-page-sphere:nth-child(4) {
            width: 250px;
            height: 250px;
            top: 20%;
            right: 15%;
            animation-delay: 1s;
            animation-duration: 28s;
        }

        .user-page-sphere:nth-child(5) {
            width: 180px;
            height: 180px;
            bottom: 20%;
            right: -90px;
            animation-delay: 3s;
            animation-duration: 24s;
        }

        @keyframes floatSphere {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(30px, -30px) rotate(90deg);
            }
            50% {
                transform: translate(-20px, 20px) rotate(180deg);
            }
            75% {
                transform: translate(40px, 10px) rotate(270deg);
            }
        }
        
        /* Stencil CSS removed as requested */

        .user-details-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .user-details-header {
            background: linear-gradient(135deg, #8B5FBF 0%, #6366F1 50%, #8B5FBF 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .user-details-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            object-fit: cover;
            margin-bottom: 20px;
        }

        .user-details-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .user-details-email {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .user-details-body {
            padding: 40px;
        }

        .user-detail-item {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .user-detail-item:last-child {
            border-bottom: none;
        }

        .user-detail-item:hover {
            background: rgba(139, 95, 191, 0.05);
        }

        .user-detail-label {
            font-weight: 700;
            color: #8B5FBF;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .user-detail-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }

        .user-detail-icon {
            color: #8B5FBF;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .user-edit-btn {
            background: linear-gradient(135deg, #8B5FBF, #6366F1);
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(139, 95, 191, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-edit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(139, 95, 191, 0.5);
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            color: white;
        }

        .user-back-btn {
            background: #ff8a80;
            border: 2px solid #8B5FBF;
            color: #8B5FBF;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-back-btn:hover {
            background: #8B5FBF;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(139, 95, 191, 0.3);
        }
    </style>
</head>
<body class="user-details-page">
    <div class="user-page-sphere"></div>
    <div class="user-page-sphere"></div>
    <div class="user-page-sphere"></div>
    <div class="user-page-sphere"></div>
    <div class="user-page-sphere"></div>

@include('layouts.navbar')

    <div class="container" style="position: relative; z-index: 10;">
        <!-- Back Button -->
        {{-- <div class="mb-4">
            <a href="{{ url('Uproducts') }}" class="btn user-back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div> --}}

        <!-- User Details Card -->
        <div class="user-details-card">
            <!-- Header Section -->
            <div class="user-details-header">
                <img src="https://ui-avatars.com/api/?name={{ $user_details->name }}&background=8B5FBF&color=fff&size=250" 
                    alt="User Avatar" 
                    class="user-details-avatar">
                <h1 class="user-details-name">{{ $user_details->name }}</h1>
                <p class="user-details-email">
                    <i class="fas fa-envelope me-2"></i>{{ $user_details->email }}
                </p>
            </div>

            <!-- Details Body -->
            <div class="user-details-body">
                <div class="row g-0">
                    <div class="col-12">
                        <!-- Full Name -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Full Name</div>
                                    <div class="user-detail-value">{{$user_details->name}}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Email Address</div>
                                    <div class="user-detail-value">{{ $user_details->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Phone Number</div>
                                    <div class="user-detail-value">{{ $user_details->phone_number }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Address</div>
                                    <div class="user-detail-value">{{ $user_details->address }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Date of Birth</div>
                                    <div class="user-detail-value">{{ $user_details->DOB }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-venus-mars user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Gender</div>
                                    <div class="user-detail-value">{{ $user_details->gender }}  </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Member Since</div>
                                    <div class="user-detail-value">{{ \Carbon\Carbon::parse($user_details->created_at)->format('Y-m-d') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="user-detail-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle user-detail-icon"></i>
                                <div class="flex-grow-1">
                                    <div class="user-detail-label">Account Status</div>
                                    <div class="user-detail-value">
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="text-center mt-5">
                    <button class="btn user-edit-btn" onclick="window.location.href='/users/UeditDetails';">
                        <i class="fas fa-edit me-2"></i>Edit Details
                    </button>
                </div>
            </div>
        </div>
        <!-- Adding some extra space to ensure scrolling is clearly visible if needed -->
        <div style="height: 100px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Parallax effect for spheres and stencils on mouse movement
        const spheres = document.querySelectorAll('.user-page-sphere');
        // const stencils = document.querySelectorAll('.user-page-stencil'); // Stencils removed

        // Mouse movement effect
        document.addEventListener('mousemove', (e) => {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            spheres.forEach((sphere, index) => {
                const speed = (index + 1) * 20;
                const x = (mouseX - 0.5) * speed;
                const y = (mouseY - 0.5) * speed;
                sphere.style.transform = `translate(${x}px, ${y}px)`; 
            });
        });
    </script>
</body>
</html>
