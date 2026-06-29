<?php

namespace App\Http\Controllers;
use App\Models\Productimg;
use App\Models\Product;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Productimgs extends Controller
{
    // View all product images (for admin/seller)
    public function viewimg()
    {
        $imgs = Productimg::all();
        return view('users.Uproducts',compact('imgs'));
    }
    
    // Get images for a specific product
    public function getProductImages($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $images = Productimg::where('product_id', $productId)->get();
        return response()->json(['images' => $images]);
    }
    
    // Add product image (seller/admin only)
    public function addProductImage(Request $request)
    {
        // Authorization: Verify user is seller or admin
        if (!Auth::check() || !in_array(Auth::user()->role, ['seller', 'admin'])) {
            return back()->with('error', 'Unauthorized access.');
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url' => 'required|url',
        ]);
        
        // Verify seller owns the product
        if (Auth::user()->role === 'seller') {
            $product = Product::find($request->product_id);
            if (!$product || $product->seller_id !== Auth::id()) {
                return back()->with('error', 'You do not have permission to add images to this product.');
            }
        }
        
        Productimg::create([
            'product_id' => $request->product_id,
            'image_url' => $request->image_url,
        ]);
        
        return back()->with('success', 'Product image added successfully.');
    }
    
    // Delete product image (seller/admin only)
    public function deleteProductImage($imageId)
    {
        // Authorization: Verify user is seller or admin
        if (!Auth::check() || !in_array(Auth::user()->role, ['seller', 'admin'])) {
            return back()->with('error', 'Unauthorized access.');
        }
        
        $image = Productimg::find($imageId);
        
        if (!$image) {
            return back()->with('error', 'Image not found.');
        }
        
        // Verify seller owns the product
        if (Auth::user()->role === 'seller') {
            $product = Product::find($image->product_id);
            if (!$product || $product->seller_id !== Auth::id()) {
                return back()->with('error', 'You do not have permission to delete this image.');
            }
        }
        
        $image->delete();
        return back()->with('success', 'Product image deleted successfully.');
    }
}
