<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration | Delivery Team</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0984e3 0%, #00cec9 100%);
            min-height: 100vh;
            display: flex;
            padding: 60px 0;
        }

        .signup-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(25px);
            border-radius: 24px;
            padding: 40px;
            margin: auto;
            width: 95%;
            max-width: 800px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            position: relative;
        }

        /* Top Right Back Button */
        .top-back-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #ffeaa7;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 10px;
            transition: 0.3s;
        }
        .top-back-link:hover { background: rgba(255,255,255,0.2); color: white; }

        .logo-section { text-align: center; margin-bottom: 35px; }
        .logo {
            width: 60px; height: 60px;
            background: #fff;
            border-radius: 15px;
            display: inline-flex;
            align-items: center; justify-content: center;
            margin-bottom: 15px;
            color: #0984e3; font-size: 28px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .form-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 25px 0 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 8px;
            color: #ffeaa7;
            font-weight: 800;
        }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: rgba(255,255,255,0.9); }

        input, select {
            width: 100%; padding: 12px 15px 12px 45px;
            border: 2px solid transparent;
            border-radius: 12px; font-size: 15px;
            background: rgba(255, 255, 255, 0.98);
            color: #2d3436;
            transition: 0.3s;
        }

        select { padding-left: 45px; cursor: pointer; appearance: none; }

        input:focus, select:focus { 
            outline: none; 
            border-color: #ffeaa7; 
            box-shadow: 0 0 15px rgba(255, 234, 167, 0.4); 
        }

        .input-container { position: relative; }
        .input-icon { 
            position: absolute; 
            left: 16px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #0984e3; 
            z-index: 10;
        }

        .signup-btn {
            width: 100%;
            background: #ffeaa7;
            color: #2d3436; 
            padding: 16px; 
            border: none; 
            border-radius: 14px;
            font-size: 16px; 
            font-weight: 700; 
            cursor: pointer;
            text-transform: uppercase; 
            margin-top: 30px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .signup-btn:hover { 
            background: #fff; 
            transform: translateY(-3px); 
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
        }

        .address-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <a href="{{ url('staff/login') }}" class="top-back-link">
            <i class="fas fa-arrow-left me-2"></i> Back to Login
        </a>

        <div class="logo-section">
            <div class="logo"><i class="fas fa-shipping-fast"></i></div>
            <h1>Staff Registration</h1>
            <p>Join our delivery fleet and start earning today.</p>
        </div>

        <form action="{{ url('staff/submitSignupDetails') }}" method="POST">
            @csrf
            
            <div class="form-section-title">Personal & Account Info</div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Full Name</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" name="name" placeholder="John Doe" required>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Last Name</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" name="last_name" placeholder="Doe" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Email Address</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="john@example.com" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Create Password</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Min. 8 characters" required>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Confirm Password</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Current Residential Address</label>
                <div class="mb-2">
                    <input type="text" name="address" placeholder="Street Address / Apartment" required style="padding-left: 15px;">
                </div>
                <div class="address-grid">
                    <input type="text" name="city" placeholder="City" required style="padding-left: 15px;">
                    <input type="text" name="state" placeholder="State" required style="padding-left: 15px;">
                    <input type="text" name="zip" placeholder="Zip" required style="padding-left: 15px;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Date of Birth</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-calendar-alt"></i>
                        <input type="date" name="dob" required>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Gender</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-venus-mars"></i>
                        <select name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section-title">Delivery & Vehicle Details</div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Assigned Service Area</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-map-marker-alt"></i>
                        <select name="assigned_area" required>
                            <option value="" disabled selected>Select Area</option>
                            <option value="Area 1">Madarihat</option>
                            <option value="Area 2">Falakata</option>
                            <option value="Area 3">Birpara</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Vehicle Type</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-motorcycle"></i>
                        <select name="vehicle_type" required>
                            <option value="Bicycle">Bicycle</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Car">Car</option>
                            <option value="Truck">Truck</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Vehicle Number Plate</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-id-card"></i>
                        <input type="text" name="vehicle_no" placeholder="WB 00 XX 0000">
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Driving License No.</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-file-signature"></i>
                        <input type="text" name="license_no" placeholder="Enter License Number" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="signup-btn">Complete Registration</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: rgba(255,255,255,0.8);">
            Already have an account? <a href="{{ url('staff/login') }}" style="color: #ffeaa7; font-weight: bold; text-decoration: none;">Login here</a>
        </div>
    </div>

</body>
</html>