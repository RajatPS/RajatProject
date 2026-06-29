@extends('layouts.adminDashboard')

@section('title')
    <title>Create Category | Admin Dashboard</title>
@endsection

@section('style')
<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        max-width: 600px;
        margin: 0 auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    header { margin-bottom: 25px; border-left: 4px solid #ffeaa7; padding-left: 15px; }
    label { display: block; font-weight: 600; margin-bottom: 8px; color: #2d3436; }
    input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; margin-bottom: 20px; }
    input[type="text"]:focus, textarea:focus { outline: none; border-color: #ffeaa7; box-shadow: 0 0 5px rgba(255, 234, 167, 0.3); }
    .btn-submit { background: #ffeaa7; color: #2d3436; padding: 12px 30px; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #fab1a0; }
    .btn-cancel { background: #ddd; color: #2d3436; padding: 12px 30px; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; margin-left: 10px; }
    .btn-cancel:hover { background: #bbb; }
    .error-list { color: #d63031; font-size: 0.9rem; margin-top: -15px; margin-bottom: 15px; }
</style>
@endsection

@section('content')
<header>
    <h1><i class="fas fa-plus-circle"></i> Create New Category</h1>
    <p>Add a new product category</p>
</header>

<div class="form-container">
    @if($errors->any())
        <div style="background: #ffe5e5; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Validation Errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('admin/categories') }}">
        @csrf

        <label for="name">Category Name <span style="color: #d63031;">*</span></label>
        <input type="text" name="name" id="name" placeholder="e.g., Stationery, Electronics" value="{{ old('name') }}" required>
        @error('name')
            <div class="error-list">{{ $message }}</div>
        @enderror

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" placeholder="Brief description of the category...">{{ old('description') }}</textarea>
        @error('description')
            <div class="error-list">{{ $message }}</div>
        @enderror

        <div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Create Category
            </button>
            <a href="{{ url('admin/categories') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection
