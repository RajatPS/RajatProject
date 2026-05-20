@extends('layouts.sellerDashboard')

@section('title')
    <title>Seller Dashboard | My Products</title>
@endsection

@section('style')
<style>
    /* Header with Action Button */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-left: 4px solid #ffeaa7;
        padding-left: 15px;
    }

    .btn-add {
        background: rgba(63, 229, 241, 0.864);
        color: rgb(255, 255, 255);
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
        border: none;
        transition: transform 0.2s;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 184, 148, 0.4);
    }

    /* Product Table Styling */
    .table-wrapper {
        width: 100%;
        border-radius: 12px;
        border: 1px solid var(--glass-border);
        overflow: hidden;
        background: rgba(0, 0, 0, 0.05);
    }

    .seller-table {
        width: 100%;
        border-collapse: collapse;
    }

    .seller-table th {
        background: var(--table-header-bg);
        padding: 18px;
        text-align: left;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .seller-table td {
        padding: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        vertical-align: middle;
    }

    /* Stock Indicators */
    .stock-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .in-stock { background: rgba(2, 90, 38, 0.2); color: #2ecc71; border: 1px solid #2ecc71; }
    .low-stock { background: rgba(180, 112, 3, 0.2); color: #f39c12; border: 1px solid #f39c12; }
    .out-stock { background: rgba(192, 44, 27, 0.2); color: #e74c3c; border: 1px solid #e74c3c; }

    .action-btns {
        display: flex;
        gap: 10px;
    }

    .btn-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: white;
        transition: 0.3s;
        cursor: pointer;
    }
    .edit-btn { background: rgba(52, 152, 219, 0.2); border: 1px solid #3498db; color: #3498db; }
    .delete-btn { background: rgba(231, 76, 60, 0.2); border: 1px solid #e74c3c; color: #e74c3c; }
    
    .edit-btn:hover { background: #3498db; color: white; }
    .delete-btn:hover { background: #e74c3c; color: white; }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1><i class="fas fa-box"></i> My Products</h1>
            <p>You have <strong>{{ $products->count() }} active products</strong> in your shop.</p>
        </div>
        <a href="/seller/selleraddProduct" class="btn-add">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="table-wrapper">
        <table class="seller-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Sales</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="product-cell">
                                @if($product->images && $product->images->count())
                                    <img src="{{ asset('storage/' . optional($product->images->first())->image) }}" 
                                         alt="{{ $product->product_name }}" width="50" style="border-radius:6px; object-fit:cover;">
                                @else
                                    <img src="{{ asset('images/placeholder.png') }}" 
                                         alt="No Image" width="50" style="border-radius:6px;">
                                @endif
                                <div>
                                    <p style="font-size: 0.75rem; opacity: 0.6; margin: 0;">ID: {{ $product->id }}</p>
                                </div>
                            </div>
                        </td>   
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="stock-badge in-stock">{{ $product->stock }} In Stock</span>
                            @elseif($product->stock > 0)
                                <span class="stock-badge low-stock">{{ $product->stock }} Low Stock</span>
                            @else
                                <span class="stock-badge out-stock">Out of Stock</span>
                            @endif
                        </td>
                        <td>{{ $product->orders ? $product->orders->sum('quantity') : 0 }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon edit-btn" onclick="editProduct('{{ $product->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ url('seller/sellerEditProducts') }}" style="display: none;" id="editProductForm-{{ $product->id }}">
                                    @csrf
                                    <input type="hidden" name="productId" value="{{ $product->id }}">
                                </form>


                                <button class="btn-icon delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form method="POST" action="{{ url('seller/sellerDeleteProduct') }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="productId" value="{{ $product->id }}">
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; opacity: 0.6;">
                            No products found. Start adding your products now!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            const associatedForm = this.nextElementSibling;

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    associatedForm.submit();
                }
            });
        });
    });

    function editProduct(productId) {
        const dynamicForm = document.getElementById('editProductForm-' + productId);
        if(dynamicForm) {
            dynamicForm.submit();
        }
    }
</script>


@endsection

