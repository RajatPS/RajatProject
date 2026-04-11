<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Details</title>
    <!-- filepath: e:\herd_server\shopee\resources\views\admin\Aproducts.blade.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6c5ce7, #a29bfe, #fd79a8);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .admin-title {
            color: #333;
            font-size: 32px;
            font-weight: 700;
        }

        /* .admin-actions {
            display: flex;
            gap: 15px;
        } */

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #6c5ce7;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .products-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            color: #333;
            font-size: 24px;
            font-weight: 700;
        }

        .search-filter {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            padding: 12px 20px 12px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #6c5ce7;
            background: white;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c5ce7;
            font-size: 16px;
        }

        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 14px;
            background: #f8f9fa;
            cursor: pointer;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .products-table th {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        .products-table th:first-child {
            border-radius: 12px 0 0 0;
        }

        .products-table th:last-child {
            border-radius: 0 12px 0 0;
        }

        .products-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #e1e5e9;
            color: #555;
        }

        .products-table tr {
            transition: all 0.3s ease;
        }

        .products-table tr:hover {
            background: rgba(108, 92, 231, 0.05);
            transform: scale(1.01);
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .product-category {
            font-size: 12px;
            color: #666;
            background: #f1f3f4;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #e17055, #fd79a8);
            color: white;
        }

        .status-draft {
            background: linear-gradient(135deg, #fdcb6e, #fd79a8);
            color: white;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: #6c5ce7;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            display: inline-flex;       /* ensures flex alignment for text + icons */
            align-items: center;        /* vertically centers content */
            justify-content: center;    /* horizontally centers content */
            gap: 5px;                   /* spacing between icon and text */
            line-height: 1;             /* avoid extra height from text */
            padding: 8px 12px;          /* keep uniform padding */
            vertical-align: middle; 
        }

        .btn-edit {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #fd79a8, #e84393);
            color: white;
        }

        .demo-btn{
            background: linear-gradient(135deg, #eb0656, #e84393);
            color: white;
        }
    
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            gap: 10px;
        }

        .pagination button {
            padding: 10px 15px;
            border: 2px solid #e1e5e9;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button.active {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border-color: #6c5ce7;
        }

        .pagination button:hover:not(.active) {
            border-color: #6c5ce7;
            background: rgba(108, 92, 231, 0.1);
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
            }

 

            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                width: 100%;
            }

            .products-table {
                font-size: 14px;
            }

            .products-table th,
            .products-table td {
                padding: 10px 8px;
            }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 8s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            right: 10%;
            animation-delay: 3s;
        }

        .floating-circle:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            left: 15%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.6;
            }
        }

        /* Modal styles */


        .delete-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            background: white;
            overflow: hidden;
        }

        .delete-modal .modal-header {
            background: linear-gradient(135deg, #8B5FBF 0%, #6366F1 50%, #8B5FBF 100%);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .delete-modal .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
        }

        .delete-modal .modal-title i {
            margin-right: 10px;
            font-size: 1.5rem;
            color: #FFD700;
        }

        .delete-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .delete-modal .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .delete-modal .modal-body {
            padding: 2.5rem 2rem;
            text-align: center;
            background: #F9FAFB;
        }

        .warning-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #8B5FBF, #6366F1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            animation: pulse 2s infinite;
            box-shadow: 0 10px 30px rgba(139, 95, 191, 0.3);
        }

        @keyframes pulse {
            0% { 
                transform: scale(1);
                box-shadow: 0 10px 30px rgba(139, 95, 191, 0.3);
            }
            50% { 
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(139, 95, 191, 0.4);
            }
            100% { 
                transform: scale(1);
                box-shadow: 0 10px 30px rgba(139, 95, 191, 0.3);
            }
        }

        .delete-message {
            font-size: 1.2rem;
            color: #4B5563;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .product-image img {
            width: 100px; 
            height: 100px; 
            object-fit: cover; 
            border-radius: 10px; 
            margin-bottom: 15px
        }

        .product-name-highlight {
            font-weight: 700;
            color: #8B5FBF;
            font-size: 1.3rem;
            padding: 0.5rem 1rem;
            background: rgba(139, 95, 191, 0.1);
            border-radius: 10px;
            border: 2px solid rgba(139, 95, 191, 0.2);
            display: inline-block;
            margin: 0.5rem 0;
        }

        .delete-modal .modal-footer {
            border: none;
            padding: 1rem 2rem 2rem;
            justify-content: center;
            gap: 1.5rem;
            background: white;
        }

        .btn-delete-confirm {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            border: none;
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-width: 140px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-delete-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.5);
            color: white;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #8B5FBF, #6366F1);
            border: none;
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-width: 140px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(139, 95, 191, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(139, 95, 191, 0.5);
            color: white;
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
        }

        /* Modal backdrop blur effect */
        .modal-backdrop {
            backdrop-filter: blur(5px);
        }

        .back-btn {
            text-decoration: none;
            background: linear-gradient(135deg, #eb0656 0%, #e84393 100%); 
            color: white !important; 
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(235, 6, 86, 0.2);
            border: none;
            
        }
       .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 6, 86, 0.4);
            background: linear-gradient(135deg, #ff1f66 0%, #eb0656 100%);
            color: white !important;
        }

        

    </style>
</head>
<body>
    {{-- no error for empty table --}}
    @if ($totalProducts < 1)
        <div class="container mt-5 mb-5">
            <div class="alert alert-info text-center" role="alert" style="border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1);">
                <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem;"></i>
                <h4 class="alert-heading text-white mt-2">Product Table is Empty</h4>
                <p class="text-white mb-0">No products have been added yet. Click below to add your first product!</p>
                <hr class="border-light">
                <h3 class="text-white">only sellers can add products from the seller dashboard</h3>
            </div>
        </div>
    @else

    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="admin-container">
        <div class="header">
            <div class="header-top">
                <h1 class="admin-title">Product Management</h1>
                 <a href="javascript:history.back()" class="back-btn">
                    Back to Dashboard 
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{$totalProducts}}</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{$activeProducts}}</div>
                <div class="stat-label">Active Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{$lowStock}}</div>
                <div class="stat-label">Low Stock</div>
            </div>
        </div>

        <div class="products-section">
            <div class="section-header">
                <h2 class="section-title">Product Details</h2>
                <div class="search-filter">
                    <div class="search-box">
                        <input type="text" class="search-input" name="Admin_search_product" placeholder="Search products...">
                        <div class="search-icon">🔍</div>
                    </div>
                    <select class="filter-select" name="category_filter" id="category_filter">
                        <option value="all categories">All Categories</option>
                        <option value="text book">Text Book</option>
                        <option value="note book">Note Book</option>
                        <option value="story book">Story Book</option>
                        <option value="pens">Pens</option>
                        <option value="pencils">Pencils</option>
                        <option value="sharpner">Sharpner</option>
                        <option value="eraser">Eraser</option>
                    </select>
                </div>
            </div>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>Sl.no</th>
                        <th>image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prod as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;"> 
                                    <img src="{{ asset('storage/' . ($product->images->first()?->image ?? 'default.png')) }}"
                                        alt="{{ $product->product_name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        <td>
                            <div>
                                <div class="product-name">{{ $product->product_name }}</div>
                                <div class="product-category">{{ $product->category }}</div>
                            </div>
                        </td>

                        </td>
                        <td>{{$product->category}}</td>
                        <td><span class="price">${{$product->price}}</span></td>
                        <td>{{$product->stock}}</td>
                        <td><span class="status-badge {{ $product->status == 1 ? 'status-active' : 'status-inactive' }}">
                            {{$product->status==1?'active':'inactive'}}
                            </span>
                        </td>
                        <td>2024-01-15</td>
                        <td>
                            <div class="action-buttons">                                
                                {{-- <a href="{{url("admin/AeditProducts/". $product->id)}}" class="btn btn-edit btn-sm">✏️ Edit</a> --}}
                                
                                <button type="button" class="btn demo-btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" 
                                data-id="{{$product->id}}" data-name="{{$product->product_name}}" 
                                data-img="{{ asset('storage/'.$product->images->first()?->image??'default.png') }}">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete
                                </button>
                                
                                {{-- <a href="#" class="btn btn-activation btn-sm">{{$product->status? 'deactivate' : 'activate'}}</a> --}}
                                
                                <button type="button" class="btn btn-sm  btn-activation {{ $product->status ? 'btn-success' : 'btn-danger' }}"
                                    data-id="{{ $product->id }}" data-status="{{ $product->status }}">
                                    {{ $product->status ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$prod->links('pagination::bootstrap-5')}}
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
<div class="modal fade delete-modal" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="warning-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="delete-message">
                        Are you sure you want to delete this product?
                    </div>
                    <div class="product-image">
                        <img id="product_img" src="" alt="Product Image" >
                    </div>
                    <div class="product-name-highlight">
                        <strong id="product_name"></strong>
                    </div>
                    
                    <p class="text-muted mt-3 mb-0">
                        <small><i class="fas fa-info-circle me-1"></i>This action cannot be undone and will permanently remove the 
                            product from your inventory.</small>
                    </p>
                </div>

                <form id="deleteForm" method="POST" action="{{ url('/admin/AdeleteProducts/' . $product->id) }}">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            No, Cancel
                        </button>
                        <button type="submit" id="deleteConfirmBtn" class="btn btn-delete-confirm">
                            <i class="fas fa-trash me-2"></i>
                            Yes, Delete
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endif


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
    //product activation toggle///////////////////////
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-activation').forEach(button => {
            button.addEventListener('click', function () {
                let productId = this.dataset.id;
                let currentStatus = parseInt(this.dataset.status);
                let newStatus = currentStatus === 1 ? 0 : 1;
                fetch(`/admin/Aproducts/${productId}/toggle`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        // Update button
                        this.dataset.status = data.status;
                        this.textContent = data.status == 1 ? 'Deactivate' : 'Activate';
                        this.classList.toggle('btn-success', data.status == 1);
                        this.classList.toggle('btn-danger', data.status == 0);

                        // Update status badge
                        let statusSpan = this.closest('tr').querySelector('.status-badge');
                        statusSpan.textContent = data.status == 1 ? 'active' : 'inactive';
                        statusSpan.classList.toggle('status-active', data.status == 1);
                        statusSpan.classList.toggle('status-inactive', data.status == 0);
                    }
                })
                .catch(err => console.log(err));
            });
        });
    });

   // Modal dynamic content handling///////////////////////////
        
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (!deleteModal) return;
                deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productImg = button.getAttribute('data-img');
                const nameEl = deleteModal.querySelector('#product_name');
                if (nameEl) nameEl.textContent = productName;
                const imgHolder = deleteModal.querySelector('#product_img');
                if (imgHolder) {
                    imgHolder.src = productImg;
                    imgHolder.alt = productName;
                }
                const confirmForm = deleteModal.querySelector('#deleteForm');
                if (confirmForm) {
                    confirmForm.action = `/admin/AdeleteProducts/${encodeURIComponent(productId)}`;
                }
            });
        });


        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const tableRows = document.querySelectorAll('.products-table tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            
            tableRows.forEach(row => {
                const productName = row.querySelector('.product-name').textContent.toLowerCase();
                const category = row.cells[1].textContent.toLowerCase();
                
                if (productName.includes(searchTerm) || category.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });




        // Filter functionality
        document.addEventListener("DOMContentLoaded", () => {
            const filterSelect = document.querySelector('.filter-select');
            const searchInput = document.querySelector('.search-input');
            const tableRows   = document.querySelectorAll('tbody tr');

            function applyFilters() {
                const filterValue = filterSelect.value.toLowerCase().trim();
                const searchValue = searchInput.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    const cells    = row.querySelectorAll('td');
                    const category = cells[2].textContent.toLowerCase().trim(); // adjust index if needed
                    const rowText  = row.textContent.toLowerCase();

                    // check category (always true if "all categories")
                    const categoryMatch = (filterValue === 'all categories' || category.includes(filterValue));
                    // check search text (always true if search empty)
                    const searchMatch   = (searchValue === '' || rowText.includes(searchValue));

                    if (categoryMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            filterSelect.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
            

        // Action buttons functionality
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const productName = row.querySelector('.product-name').textContent;
                alert(`Edit product: ${productName}`);
            });
        });

        document.querySelectorAll('.deleteConfirmModal').forEach(btn => { //.btn-delete
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const productName = row.querySelector('.product-name').textContent;
                if (confirm(`Are you sure you want to delete: ${productName}?`)) {
                    row.remove();
                }
            });
        });

        // Add hover effects to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(0.98)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
</script>
</body>
</html>