@extends('layouts.adminDashboard')

@section('title')
    <title>Manage Categories | Admin Dashboard</title>
@endsection

@section('style')
<style>
    .category-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    .category-table th, .category-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
    .category-table th { background: #f8f9fa; font-weight: 600; }
    .category-table tbody tr:hover { background: #f8f9fa; }
    .btn { padding: 8px 12px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.85rem; }
    .btn-primary { background: #ffeaa7; color: #2d3436; font-weight: 600; }
    .btn-danger { background: #ff7675; color: white; }
    .btn-primary:hover { background: #fab1a0; }
    .btn-danger:hover { background: #d63031; }
    .btn-group { display: flex; gap: 5px; }
    header { margin-bottom: 25px; border-left: 4px solid #ffeaa7; padding-left: 15px; }
    .add-btn { margin-bottom: 20px; display: inline-block; }
</style>
@endsection

@section('content')
<header>
    <h1><i class="fas fa-list"></i> Manage Categories</h1>
    <p>Create and manage product categories for sellers</p>
</header>

@if($message = Session::get('success'))
    <div style="padding: 15px; background: #00b894; color: white; border-radius: 5px; margin-bottom: 20px;">
        {{ $message }}
    </div>
@endif

@if($message = Session::get('error'))
    <div style="padding: 15px; background: #ff7675; color: white; border-radius: 5px; margin-bottom: 20px;">
        {{ $message }}
    </div>
@endif

<a href="{{ url('admin/categories/create') }}" class="btn btn-primary add-btn">
    <i class="fas fa-plus"></i> Add New Category
</a>

<table class="category-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td><strong>{{ $category->name }}</strong></td>
            <td>{{ \Str::limit($category->description, 50) }}</td>
            <td>
                @if($category->active)
                    <span style="background: #00b894; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem;">Active</span>
                @else
                    <span style="background: #fdcb6e; color: #2d3436; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem;">Inactive</span>
                @endif
            </td>
            <td>{{ $category->products()->count() }}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ url('admin/categories/' . $category->id . '/edit') }}" class="btn btn-primary">Edit</a>
                    @if($category->products()->count() == 0)
                        <form method="POST" action="{{ url('admin/categories/' . $category->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-danger" disabled title="Cannot delete category with products">Delete</button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">No categories found. <a href="{{ url('admin/categories/create') }}">Create one now!</a></td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $categories->links() }}
</div>
@endsection
