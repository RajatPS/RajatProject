<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8B5FBF 0%, #6366F1 50%, #8B5FBF 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Floating spheres animation */
        .sphere {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: float 20s infinite linear;
            z-index: 0;
        }

        .sphere:nth-child(1) {
            width: 80px;
            height: 80px;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 25s;
        }

        .sphere:nth-child(2) {
            width: 120px;
            height: 120px;
            left: 20%;
            animation-delay: 5s;
            animation-duration: 30s;
        }

        .sphere:nth-child(3) {
            width: 60px;
            height: 60px;
            left: 70%;
            animation-delay: 10s;
            animation-duration: 20s;
        }

        .sphere:nth-child(4) {
            width: 100px;
            height: 100px;
            left: 80%;
            animation-delay: 15s;
            animation-duration: 35s;
        }

        .sphere:nth-child(5) {
            width: 90px;
            height: 90px;
            left: 50%;
            animation-delay: 8s;
            animation-duration: 28s;
        }

        .sphere:nth-child(6) {
            width: 70px;
            height: 70px;
            left: 30%;
            animation-delay: 12s;
            animation-duration: 22s;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        .container {
            position: relative;
            z-index: 10;
        }
        
        .product-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .card-header {
            background: white;
            color: #4B5563;
            padding: 2rem;
            border: none;
            border-bottom: 1px solid #f3f4f6;
        }

        .card-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #374151;
        }
        
        .form-label {
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #E5E7EB;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #8B5FBF;
            box-shadow: 0 0 0 0.2rem rgba(139, 95, 191, 0.15);
        }
        
        .view-mode {
            background-color: #F9FAFB;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 2px solid #E5E7EB;
            color: #374151;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background-color: #10B981;
            color: white;
        }
        
        .status-inactive {
            background-color: #EF4444;
            color: white;
        }
        
        .status-draft {
            background-color: #F59E0B;
            color: white;
        }
        
        .btn-sma {
            background: #8B5FBF;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-sma:hover {
            background: #b38fe9f5;
            transform: translateY(-2px);
        }

        .btn-smo {
            background: #8B5FBF;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-smo:hover {
            background: #6457edf5;
            transform: translateY(-2px);
        }
        
        .btn-save {
            background: #10B981;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: red;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #c96e2c;
            transform: translateY(-2px);
        }
        
        .field-icon {
            color: #8B5FBF;
            margin-right: 0.5rem;
        }
        
        .alert-info {
            background: rgba(139, 95, 191, 0.1);
            border: 2px solid rgba(139, 95, 191, 0.2);
            border-radius: 15px;
            color: #8B5FBF;
            font-weight: 500;
        }

        .input-group-text {
            background: #F9FAFB;
            border: 2px solid #E5E7EB;
            border-right: none;
            color: #6B7280;
            font-weight: 600;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #8B5FBF;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #8B5FBF;
        }

        .stats-label {
            color: #9CA3AF;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <!-- Floating spheres -->
    <div class="sphere"></div>
    <div class="sphere"></div>
    <div class="sphere"></div>
    <div class="sphere"></div>
    <div class="sphere"></div>
    <div class="sphere"></div>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="product-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Product Management</h3>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sma" style=" border-radius: 10px; padding: 0.5rem 1rem;">
                                <i class="fas fa-plus me-1"></i> ADD PRODUCT
                            </button>
                            <button class="btn btn-smo" style=" border-radius: 10px; padding: 0.5rem 1rem;">
                                <i class="fas fa-chart-bar me-1"></i> ANALYTICS
                            </button>
                            <button class="btn btn-sm" style="background: #8B5FBF; color: white; border-radius: 10px; padding: 0.5rem 1rem;">
                                <i class="fas fa-cog me-1"></i> <b> SETTINGS </b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Product Form -->
        <form id="productForm" method="post" action="{{url('admin/AeditProducts/'.$products->id)}}">
            @csrf
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="product-card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="mb-0">Edit Product Details</h1>
                            <div id="editButtons">
                                <button type="submit" class="btn btn-save me-2">
                                    <i class="fas fa-save me-2"></i>SAVE
                                </button>
                                <button type="submit" class="btn btn-cancel mt-1">
                                    <i class="fas fa-times me-2"></i>CANCEL
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div class="card-body p-4">
                            <!-- Product Name -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-tag field-icon"></i>Product Name
                                </label>
                                <input type="text" class="form-control" id="productName" name="productName" value="{{$products->product_name}}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-list field-icon"></i>Category
                                </label>
                                <select class="form-select" id="productCategory" name="category">
                                    <option value="">NULL</option>
                                    <option value="text book" {{ $products->category=="text book" ? 'selected' : ''}}>Text Book</option>
                                    <option value="note book" {{$products->category=="notebook" ? 'selected' : ''}}>Note Book</option>
                                    <option value="story book" {{$products->category=="story book" ?'selected' : ''}}>Story Book</option>
                                    <option value="pens"{{$products->category=="pens" ? 'selected':''}}>Pens</option>
                                    <option value="pencils"{{$products->category=="pencils" ? 'selected':''}}>Pencils</option>
                                    <option value="erasers"{{$products->category=="erasers" ? 'selected':''}}>Pens</option>
                                    <option value="sharpners"{{$products->category=="sharpners" ? 'selected':''}}>Sharpners</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign field-icon"></i>Price
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productPrice" name="productPrice" value="{{$products->price}}" >
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-warehouse field-icon"></i>Stock Quantity
                                </label>
                                <input type="number" class="form-control" id="productStock" name="productStock" value="{{$products->stock}}" >
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-warehouse field-icon"></i>Product Weight(Kg)
                                </label>
                                <input type="number" class="form-control" id="productWeight" name="productWeight" value="{{$products->weight}}" >
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-warehouse field-icon"></i>Description
                                </label>
                                <input type="text" class="form-control" id="description" name="description" value="{{$products->description}}" >
                            </div>
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-info-circle field-icon"></i>Status
                                </label>
                                <select class="form-select" id="productStatus" name="productStatus" value="{{$products->status}}">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </form>
                        <div class="alert alert-info" id="editAlert"><i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Make sure all fields are filled correctly before saving. Changes will be updated immediately.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>