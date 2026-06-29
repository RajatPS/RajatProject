@extends('layouts.sellerDashboard')

@section('title')
    <title>Edit Product Details</title>
@endsection

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        .ep-wrapper {
            font-family: 'DM Sans', sans-serif;
            padding: 24px;
            min-height: 100vh;
        }

        /* ── TOP ACTION BAR ───────────────────────────────── */
        .ep-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

        .ep-topbar-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ep-topbar-title .icon-box {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #00cec9, #0984e3);
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .ep-topbar-title h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--light-text-color, #fff);
            letter-spacing: -0.3px;
        }

        .ep-topbar-title p {
            margin: 0;
            font-size: 0.78rem;
            color: #ffffff; /* Stark white text for structural meta-info */
            font-family: 'Space Mono', monospace;
        }

        .ep-btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ep-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            letter-spacing: 0.3px;
        }

        .ep-btn-save {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: #fff;
            box-shadow: 0 4px 14px rgba(0,184,148,0.35);
        }
        .ep-btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,184,148,0.5);
            color: #fff;
        }

        .ep-btn-cancel {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid rgba(231,76,60,0.4);
            color: #fff;
        }
        .ep-btn-cancel:hover {
            background: rgba(231,76,60,0.3);
            transform: translateY(-2px);
            color: #fff;
        }

        .ep-btn-ghost {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.8);
        }
        .ep-btn-ghost:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        /* ── MAIN CARD (Reverted to original background style) ── */
        .ep-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            overflow: hidden;
            backdrop-filter: blur(12px);
        }

        .ep-card-header {
            padding: 20px 28px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ep-card-header .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
        }
        .dot-red   { background: #ff5f56; }
        .dot-yel   { background: #ffbd2e; }
        .dot-grn   { background: #27c93f; }

        .ep-card-header span {
            font-size: 0.82rem;
            color: #ffffff; /* Swapped file route text to white */
            font-family: 'Space Mono', monospace;
            margin-left: 6px;
            font-weight: 600;
        }

        /* ── FORM GRID BODY ───────────────────────────────── */
        .ep-form-body {
            padding: 28px;
        }

        /* 2-column grid */
        .ep-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 28px;
        }

        /* Full-width span */
        .ep-col-full { grid-column: 1 / -1; }

        /* ── FIELD BLOCK ──────────────────────────────────── */
        .ep-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Field Name texts updated to solid white */
        .ep-label {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #ffffff !important; 
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .ep-label i {
            color: #00cec9;
            font-size: 0.75rem;
        }

        /* Inputs kept in original design states */
        .ep-input,
        .ep-select {
            width: 100%;
            background: rgba(255,255,255,0.07) !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            border-radius: 10px;
            padding: 11px 14px;
            color: #fff !important;
            font-size: 0.93rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        }

        .ep-input:focus,
        .ep-select:focus {
            background: rgba(255,255,255,0.12) !important;
            border-color: #00cec9 !important;
            box-shadow: 0 0 0 3px rgba(0,206,201,0.18);
        }

        .ep-input::placeholder { color: rgba(255,255,255,0.3); }

        /* Select arrow */
        .ep-select-wrap {
            position: relative;
        }
        .ep-select-wrap::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #ffffff; /* Arrow dynamic indicator changed to white */
            pointer-events: none;
            font-size: 0.85rem;
        }

        /* Price prefix */
        .ep-input-prefix {
            display: flex;
            align-items: stretch;
        }
        .ep-prefix-tag {
            background: rgba(0,206,201,0.15);
            border: 1px solid rgba(0,206,201,0.3);
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 11px 14px;
            color: #00cec9;
            font-weight: 700;
            font-size: 0.9rem;
            display: grid;
            place-items: center;
        }
        .ep-input-prefix .ep-input {
            border-radius: 0 10px 10px 0 !important;
        }

        /* ── DIVIDER ──────────────────────────────────────── */
        .ep-divider {
            grid-column: 1 / -1;
            height: 1px;
            background: rgba(255,255,255,0.15);
            margin: 4px 0;
        }

        /* ── SECTION LABEL (Instructions dividers updated to solid White) ── */
        .ep-section-label {
            grid-column: 1 / -1;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #ffffff !important;
            font-family: 'Space Mono', monospace;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ep-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.15);
        }

        /* ── STATUS BADGE PREVIEW ─────────────────────────── */
        .status-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
        }

        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .status-dot.active   { background: #00b894; box-shadow: 0 0 6px #00b894; }
        .status-dot.inactive { background: #e17055; box-shadow: 0 0 6px #e17055; }
        .status-dot.draft    { background: #fdcb6e; box-shadow: 0 0 6px #fdcb6e; }

        /* ── FOOTER NOTE (Instructions summary updated to solid White) ── */
        .ep-footer-note {
            margin-top: 24px;
            padding: 14px 18px;
            background: rgba(0,206,201,0.07);
            border: 1px solid rgba(0,206,201,0.2);
            border-radius: 10px;
            font-size: 0.84rem;
            color: #ffffff !important;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-weight: 500;
        }
        .ep-footer-note i {
            color: #00cec9;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Dropdown selection options backdrop configuration styling */
        option {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }

        /* ── RESPONSIVE ───────────────────────────────────── */
        @media (max-width: 680px) {
            .ep-grid { grid-template-columns: 1fr; }
            .ep-col-full { grid-column: 1; }
            .ep-form-body { padding: 20px 16px; }
        }
    </style>
@endsection

@section('content')
<div class="ep-wrapper">

    <div class="ep-topbar">
        <div class="ep-topbar-title">
            <div class="icon-box"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <h2>Edit Product</h2>
                <p>ID #Product-{{ $products->id }} &nbsp;·&nbsp; Last saved automatically</p>
            </div>
        </div>
    </div>

    <form id="productForm" method="POST" action="{{ url('/admin/AeditProducts') }}">
        {{-- <form id="productForm" method="POST" action="{{ url('/seller/AeditProducts') }}"> --}}
        @csrf
        <div class="ep-card">
            <input type="hidden" name="productId" value="{{ $products->id }}">
            <div class="ep-card-header">
                <div class="dot dot-red"></div>
                <div class="dot dot-yel"></div>
                <div class="dot dot-grn"></div>
                <span>product-details.edit</span>

                <div class="ep-btn-group" style="margin-left: auto;">
                    <a href="{{ url()->previous() }}" class="ep-btn ep-btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="ep-btn ep-btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>

            <div class="ep-form-body">
                <div class="ep-grid">

                    <div class="ep-section-label">Basic Information</div>

                    <div class="ep-field ep-col-full">
                        <label class="ep-label"><i class="fas fa-tag"></i> Product Name</label>
                        <input type="text" class="ep-input" name="productName" id="productName"
                               value="{{ $products->product_name }}"
                               placeholder="Enter product name...">
                    </div>

                    <div class="ep-field">
                        <label class="ep-label"><i class="fas fa-list"></i> Category</label>
                        <input type="text" class="ep-input" name="category" id="category"
                               value="{{ $products->category }}"
                               placeholder="Enter product category...">
                    </div>

                    <div class="ep-field">
                        <label class="ep-label"><i class="fas fa-info-circle"></i> Status</label>
                        <div class="ep-select-wrap">
                            <select class="ep-select ep-input" name="productStatus" id="productStatus"
                                    onchange="updateStatusDot(this.value)">
                                <option value="active"   {{ $products->status == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $products->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft"    {{ $products->status == 'draft'    ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="status-preview">
                            <div class="status-dot {{ $products->status }}" id="statusDot"></div>
                            <span style="font-size:0.78rem; color:#ffffff;" id="statusText">
                                {{ ucfirst($products->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="ep-divider"></div>

                    <div class="ep-section-label">Pricing &amp; Inventory</div>

                    <div class="ep-field">
                        <label class="ep-label"><i class="fas fa-dollar-sign"></i> Price</label>
                        <div class="ep-input-prefix">
                            <div class="ep-prefix-tag">$</div>
                            <input type="number" step="0.01" class="ep-input" name="productPrice"
                                   id="productPrice" value="{{ $products->price }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="ep-field">
                        <label class="ep-label"><i class="fas fa-warehouse"></i> Stock Quantity</label>
                        <input type="number" class="ep-input" name="productStock"
                               id="productStock" value="{{ $products->stock }}" placeholder="0">
                    </div>

                    <div class="ep-field">
                        <label class="ep-label"><i class="fas fa-balance-scale"></i> Weight (kg)</label>
                        <input type="number" step="0.01" class="ep-input" name="productWeight"
                               id="productWeight" value="{{ $products->weight }}" placeholder="0.00">
                    </div>

                    <div class="ep-divider"></div>

                    <div class="ep-section-label">Additional Details</div>

                    <div class="ep-field ep-col-full">
                        <label class="ep-label"><i class="fas fa-align-left"></i> Description</label>
                        <input type="text" class="ep-input" name="description"
                               id="description" value="{{ $products->description }}"
                               placeholder="Short product description...">
                    </div>

                </div>

                <div class="ep-footer-note">
                    <i class="fas fa-circle-info"></i>
                    <span>Ensure all fields are correct before saving. Changes take effect immediately across the storefront.</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // function updateStatusDot(val) {
    //     const dot  = document.getElementById('statusDot');
    //     const text = document.getElementById('statusText');
    //     dot.className = 'status-dot ' + val;
    //     text.textContent = val.charAt(0).toUpperCase() + val.slice(1);
    // }
</script>
@endsection