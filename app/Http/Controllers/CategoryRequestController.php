<?php

namespace App\Http\Controllers;

use App\Models\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryRequestController extends Controller
{
    /**
     * Show form to request a new category
     */
    public function create()
    {
        return view('seller.category-request.create');
    }

    /**
     * Store a new category request
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|max:50',
                'description' => 'nullable|max:500',
            ]);

            // Check for duplicates
            $existing = CategoryRequest::where('category_name', $request->category_name)
                ->where('status', 'pending')
                ->first();
            
            if ($existing) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'A request for this category already exists and is pending.'], 422);
                }
                return redirect('seller/products')->with('error', 'A request for this category already exists.');
            }

            CategoryRequest::create([
                'seller_id' => Auth::id(),
                'category_name' => $request->category_name,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Category request submitted! Admin will review it shortly.']);
            }

            return redirect('seller/products')->with('success', 'Category request submitted! Admin will review it shortly.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Show seller's category requests
     */
    public function myRequests()
    {
        $requests = CategoryRequest::where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('seller.category-request.my-requests', compact('requests'));
    }
}
