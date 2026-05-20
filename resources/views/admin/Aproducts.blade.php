<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            /* Variables needed for Sidebar and Content consistency */
            --primary-gradient: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-color: #ff6b9d;
            --sidebar-width: 250px; 
            --light-text-color: #ffffff;
            --dark-reddish-pink: #ff4783;
            --reddish-pink-color: #f55b8e;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
        }

        /* --- LAYOUT STRUCTURE --- */
        .dashboard-wrapper {
            display: flex;
        }

        .main-content {
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            transition: all 0.3s ease;
        }

        /* --- YOUR STYLES --- */
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

        .btn-primary { background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%); color: white; }
        .btn-secondary { background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); }

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

        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15); }
        .stat-number { font-size: 36px; font-weight: 700; color: #6c5ce7; margin-bottom: 8px; }
        .stat-label { color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }

        .products-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .section-title { color: #333; font-size: 24px; font-weight: 700; }
        .search-filter { display: flex; gap: 15px; align-items: center; }
        .search-box { position: relative; }

        .search-input {
            padding: 12px 20px 12px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .search-input:focus { outline: none; border-color: #6c5ce7; background: white; box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1); }
        .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6c5ce7; font-size: 16px; }

        .filter-select { padding: 12px 20px; border: 2px solid #e1e5e9; border-radius: 12px; font-size: 14px; background: #f8f9fa; cursor: pointer; }

        .products-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .products-table th {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        .products-table th:first-child { border-radius: 12px 0 0 0; }
        .products-table th:last-child { border-radius: 0 12px 0 0; }
        .products-table td { padding: 20px 15px; border-bottom: 1px solid #e1e5e9; color: #555; }
        .products-table tr { transition: all 0.3s ease; }
        .products-table tr:hover { background: rgba(108, 92, 231, 0.05); transform: scale(1.01); }

        .product-image { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
        .product-name { font-weight: 600; color: #333; margin-bottom: 5px; }
        .product-category { font-size: 12px; color: #666; background: #f1f3f4; padding: 4px 8px; border-radius: 6px; display: inline-block; }

        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-active { background: linear-gradient(135deg, #00b894, #00cec9); color: white; }
        .status-inactive { background: linear-gradient(135deg, #e17055, #fd79a8); color: white; }

        .price { font-size: 18px; font-weight: 700; color: #6c5ce7; }

        .action-buttons { display: flex; gap: 8px; }
        .btn-sm { display: inline-flex; align-items: center; justify-content: center; gap: 5px; line-height: 1; padding: 8px 12px; vertical-align: middle; }

        .btn-edit { background: linear-gradient(135deg, #74b9ff, #0984e3); color: white; }
        .demo-btn { background: linear-gradient(135deg, #eb0656, #e84393); color: white; }

        .floating-elements { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: -1; }
        .floating-circle { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.1); animation: float 8s ease-in-out infinite; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.6; }
        }

        /* Modal styling */
        .delete-modal .modal-content { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); background: white; overflow: hidden; }
        .delete-modal .modal-header { background: linear-gradient(135deg, #8B5FBF 0%, #6366F1 50%, #8B5FBF 100%); color: white; border: none; padding: 1.5rem 2rem; }
        .warning-icon { width: 90px; height: 90px; background: linear-gradient(135deg, #8B5FBF, #6366F1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem; color: white; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        
        .delete-message { font-size: 1.2rem; color: #4B5563; margin-bottom: 1rem; font-weight: 600; }
        .product-image img { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; margin-bottom: 15px }
        .product-name-highlight { font-weight: 700; color: #8B5FBF; font-size: 1.3rem; padding: 0.5rem 1rem; background: rgba(139, 95, 191, 0.1); border-radius: 10px; display: inline-block; }

        .btn-delete-confirm { background: linear-gradient(135deg, #EF4444, #DC2626); border: none; color: white; padding: 14px 35px; border-radius: 50px; font-weight: 700; }
        .btn-cancel { background: linear-gradient(135deg, #8B5FBF, #6366F1); border: none; color: white; padding: 14px 35px; border-radius: 50px; font-weight: 700; }

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

        @media (max-width: 768px) {
            .main-content { margin-left: 0; width: 100%; }
            .header-top { flex-direction: column; gap: 15px; }
            .search-filter { flex-direction: column; align-items: stretch; }
            .search-input { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        
        @include('layouts.adminSidebar')

        <main class="main-content">

            @if ($totalProducts < 1)
                <div class="container mt-5 mb-5">
                    <div class="alert alert-info text-center" role="alert" style="border-radius: 12px; background: rgba(255, 255, 255, 0.1); color: white;">
                        <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem;"></i>
                        <h4 class="alert-heading mt-2">Product Table is Empty</h4>
                        <p class="mb-0">No products have been added yet. Click below to add your first product!</p>
                        <hr class="border-light">
                        <h3>only sellers can add products from the seller dashboard</h3>
                    </div>
                </div>
            @else

                <div class="floating-elements">
                    <div class="floating-circle" style="top: 10%; left: 5%;"></div>
                    <div class="floating-circle" style="top: 70%; right: 10%; animation-delay: 3s;"></div>
                    <div class="floating-circle" style="bottom: 10%; left: 15%; animation-delay: 6s;"></div>
                </div>

                <div class="admin-container">
                    <div class="header">
                        <div class="header-top">
                            <h1 class="admin-title">Product Management</h1>
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
                                        <div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;"> 
                                            <img src="{{ asset('storage/' . ($product->images->first()?->image ?? 'default.png')) }}"
                                                alt="{{ $product->product_name }}" 
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="product-name">{{ $product->product_name }}</div>
                                            <div class="product-category">{{ $product->category }}</div>
                                        </div>
                                    </td>
                                    <td>{{$product->category}}</td>
                                    <td><span class="price">₹{{$product->price}}</span></td>
                                    <td>{{$product->stock}}</td>
                                    <td><span class="status-badge {{ $product->status == 1 ? 'status-active' : 'status-inactive' }}">
                                        {{$product->status==1?'active':'inactive'}}
                                        </span>
                                    </td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="action-buttons">                                
                                            <button type="button" class="btn demo-btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" 
                                            data-id="{{$product->id}}" data-name="{{$product->product_name}}" 
                                            data-img="{{ asset('storage/'.$product->images->first()?->image??'default.png') }}">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                            
                                            <button type="button" class="btn btn-sm btn-activation {{ $product->status ? 'btn-success' : 'btn-danger' }}"
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
            @endif
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade delete-modal" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="warning-icon"><i class="fas fa-trash-alt"></i></div>
                    <div class="delete-message">Are you sure you want to delete this product?</div>
                    <div class="product-image"><img id="product_img" src="" alt="Product Image" ></div>
                    <div class="product-name-highlight"><strong id="product_name"></strong></div>
                    <p class="text-muted mt-3 mb-0"><small><i class="fas fa-info-circle me-1"></i>This action cannot be undone.</small></p>
                </div>

                <form id="deleteForm" method="POST" action="">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">No, Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Dropdown
            document.querySelectorAll('.dropdown-toggle').forEach(item => {
                item.addEventListener('click', event => {
                    event.preventDefault();
                    item.nextElementSibling.classList.toggle('open');
                });
            });

            // Activation Toggle
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
                            this.dataset.status = data.status;
                            this.textContent = data.status == 1 ? 'Deactivate' : 'Activate';
                            this.classList.toggle('btn-success', data.status == 1);
                            this.classList.toggle('btn-danger', data.status == 0);
                            let statusSpan = this.closest('tr').querySelector('.status-badge');
                            statusSpan.textContent = data.status == 1 ? 'active' : 'inactive';
                            statusSpan.classList.toggle('status-active', data.status == 1);
                            statusSpan.classList.toggle('status-inactive', data.status == 0);
                        }
                    });
                });
            });

            // Modal handling
            const deleteModal = document.getElementById('deleteConfirmModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productImg = button.getAttribute('data-img');
                deleteModal.querySelector('#product_name').textContent = productName;
                deleteModal.querySelector('#product_img').src = productImg;
                deleteModal.querySelector('#deleteForm').action = `/admin/AdeleteProducts/${productId}`;
            });

            // Filter logic
            const filterSelect = document.querySelector('.filter-select');
            const searchInput = document.querySelector('.search-input');
            const tableRows = document.querySelectorAll('tbody tr');

            function applyFilters() {
                const filterValue = filterSelect.value.toLowerCase().trim();
                const searchValue = searchInput.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    const category = row.cells[3].textContent.toLowerCase().trim(); 
                    const rowText = row.textContent.toLowerCase();
                    const categoryMatch = (filterValue === 'all categories' || category.includes(filterValue));
                    const searchMatch = (searchValue === '' || rowText.includes(searchValue));
                    row.style.display = (categoryMatch && searchMatch) ? '' : 'none';
                });
            }

            filterSelect.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
    </script>
</body>
</html>