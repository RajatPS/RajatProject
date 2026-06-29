<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:50',
            'description' => 'nullable|max:500',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        return redirect('admin/categories')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(20);
        return view('admin.categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id . '|max:50',
            'description' => 'nullable|max:500',
            'active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'active' => $request->has('active') ? 1 : 0,
        ]);

        return redirect('admin/categories')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect('admin/categories')->with('error', 'Cannot delete category with existing products.');
        }

        $category->delete();
        return redirect('admin/categories')->with('success', 'Category deleted successfully!');
    }

    /**
     * Get all active categories (API endpoint for frontend)
     */
    public function getActive()
    {
        $categories = Category::where('active', true)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();
        
        return response()->json($categories);
    }

    /**
     * Display category requests from sellers
     */
    public function showRequests()
    {
        $requests = CategoryRequest::with('seller')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.categories.requests', compact('requests'));
    }

    /**
     * Approve a category request
     */
    public function approveRequest($id)
    {
        $request_record = CategoryRequest::findOrFail($id);

        // Check if category with same name exists
        $existingCategory = Category::where('name', $request_record->category_name)->first();

        if ($existingCategory) {
            // Link to existing category
            $request_record->category_id = $existingCategory->id;
            $request_record->status = 'approved';
            $request_record->save();
            return back()->with('success', 'Category request approved (linked to existing category)!');
        }

        // Create new category
        $category = Category::create([
            'name' => $request_record->category_name,
            'description' => $request_record->description,
            'slug' => Str::slug($request_record->category_name),
        ]);

        $request_record->category_id = $category->id;
        $request_record->status = 'approved';
        $request_record->save();

        return back()->with('success', 'Category request approved and category created!');
    }

    /**
     * Reject a category request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|max:500',
        ]);

        $request_record = CategoryRequest::findOrFail($id);
        $request_record->status = 'rejected';
        $request_record->admin_notes = $request->admin_notes;
        $request_record->save();

        return back()->with('success', 'Category request rejected!');
    }
}
