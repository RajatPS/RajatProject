@extends('layouts.sellerDashboard')

@section('title')
    <title>Seller Dashboard | Add New Product</title>
@endsection

@section('style')
<style>
    header { margin-bottom: 25px; border-left: 4px solid #ffeaa7; padding-left: 15px; }

    /* Glass Form Container */
    .form-container {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .full-width { grid-column: 1 / -1; }

    /* Inputs & Labels */
    label {
        display: block;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        color: #ffeaa7;
        font-weight: 600;
    }

    input[type="text"], input[type="number"], select, textarea {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: white;
        font-size: 1rem;
        transition: 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.1);
        border-color: #ffeaa7;
        box-shadow: 0 0 10px rgba(255, 234, 167, 0.2);
    }

    option { background: #2d3436; color: white; }

    .help-text { font-size: 0.75rem; opacity: 0.6; margin-top: 5px; }

    /* Custom File Upload */
    .file-upload {
        border: 2px dashed var(--glass-border);
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: 0.3s;
        position: relative;
    }

    .file-upload:hover, .file-upload.dragover {
        border-color: #ffeaa7;
        background: rgba(255, 255, 255, 0.05);
    }

    .file-upload input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0; left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Image Preview */
    .image-preview { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px; }
    .preview-item {
        position: relative;
        width: 80px; height: 80px;
        border-radius: 8px; overflow: hidden;
        border: 2px solid var(--glass-border);
    }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .remove-image {
        position: absolute; top: 2px; right: 2px;
        background: #ff7675; border: none; border-radius: 50%;
        color: white; width: 18px; height: 18px; cursor: pointer; font-size: 12px;
    }

    /* Status & Toggles */
    .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
    .checkbox-group label { text-transform: none; color: white; margin: 0; cursor: pointer; }

    .btn-submit {
        background: linear-gradient(135deg, #ffeaa7, #fab1a0);
        color: #2d3436; /* Dark text for better contrast on light gold */
        border: none;
        padding: 15px 40px;
        border-radius: 10px;
        font-weight: 800;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 20px;
    }

    .btn-submit:hover {
        background: #ffeaa7;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 234, 167, 0.4);
    }

    .required { color: #ff7675; }
</style>
@endsection

@section('content')

<header>
    <h1><i class="fas fa-plus-circle"></i> Add New Product</h1>
    <p>List a new item in your stationery shop</p>
</header>

<div class="form-container">
    <form id="addProductForm" method="POST" action="{{url('/seller/addproduct')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Product Name <span class="required">*</span></label>
                <input type="text" name="productName" id="productName" placeholder="e.g. Premium Fountain Pen" required>
                <div class="help-text">Visible to customers in search results</div>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" name="brand" id="brand" placeholder="e.g. Reynolds, Cello, Faber-Castell">
                <div class="help-text">Product brand or manufacturer</div>
            </div>

            <div class="form-group">
                <label for="category">Category <span class="required">*</span></label>
                <input type="text" name="category" id="category" placeholder="e.g. Stationery, Electronics, Groceries" required>
                <div class="help-text">Enter any product category</div>
            </div>

            <div class="form-group">
                <label for="price">Price ($) <span class="required">*</span></label>
                <input type="number" name="price" placeholder="0.00" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" name="stock" placeholder="0" min="0">
            </div>

            {{-- <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" name="stock" placeholder="0" min="0">
            </div> --}}

            <div class="form-group">
                <label for="weight">Weight (in grams) <span class="required">*</span></label>
                <input type="number" name="weight" placeholder="e.g. 500" step="0.01" min="0" required>
                <div class="help-text">Used to calculate shipping costs</div>
            </div>

            <div class="form-group full-width">
                <label for="description">Description <span class="required">*</span></label>
                <textarea name="description" rows="4" placeholder="Describe the quality, ink type, or material..."></textarea>
            </div>

            <div class="form-group full-width">
                <label for="productImages">Product Images</label>
                <div class="file-upload" id="dropZone">
                    <input type="file" id="productImages" name="productImages[]" multiple accept="image/*">
                    <div class="upload-content">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #ffeaa7;"></i>
                        <p style="margin-top: 10px;">Drag & Drop or Click to Browse</p>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview"></div>
            </div>

            <div class="form-group">
                <label for="status">Initial Status</label>
                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <div class="checkbox-group">
                        <input type="radio" id="active" name="status" value="1" checked>
                        <label for="active">Active</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="radio" id="inactive" name="status" value="0">
                        <label for="inactive">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tags">Product Tags</label>
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="checkbox-group">
                        <input type="checkbox" id="featured" name="type[]" value="featured">
                        <label for="featured">Featured</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="new" name="type[]" value="new">
                        <label for="newProduct">New Product</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="onsale" name="type[]" value="onsale">
                        <label for="onsale">On Sale</label>
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: right;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Launch Product
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Image Preview Logic
    const imageInput = document.getElementById('productImages');
    const imagePreview = document.getElementById('imagePreview');
    const dropZone = document.getElementById('dropZone');

    imageInput.addEventListener('change', handleFiles);

    function handleFiles() {
        imagePreview.innerHTML = '';
        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}">
                    <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        const dt = new DataTransfer();
        const { files } = imageInput;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) dt.items.add(files[i]);
        }
        imageInput.files = dt.files;
        handleFiles.call(imageInput);
    }

    // Drag and Drop Visuals
    ['dragenter', 'dragover'].forEach(name => {
        dropZone.addEventListener(name, () => dropZone.classList.add('dragover'));
    });
    ['dragleave', 'drop'].forEach(name => {
        dropZone.addEventListener(name, () => dropZone.classList.remove('dragover'));
    });

</script>
@endsection