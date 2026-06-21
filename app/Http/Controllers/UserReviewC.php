<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productimg;
use App\Models\User_review;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserReviewC extends Controller
{

    //add reviews function
    public function addReview(Request $request){

    if(!Auth::check()){
        return redirect('users/Ulogin')->with('error', 'You must be logged in to submit a review.');
    }
        $user_id = Auth::id();
        $validate=$request->validate([
        'user_name' => 'required|string|max:255',
        'user_review' => 'required|string|max:1000',
        'product_id' => 'required|integer|exists:products,id',
        'rating' => 'required|integer|between:1,5',
        
        ]);

        // Fixed: Verify product exists and is active
        $product = Product::find($validate['product_id']);
        if (!$product || !$product->status) {
            return back()->withErrors('This product is not available.');
        }
        
        // Fixed: Check if user already reviewed this product
        $existingReview = User_review::where('user_id', $user_id)
                                      ->where('product_id', $validate['product_id'])
                                      ->first();
        
        if ($existingReview) {
            return back()->withErrors('You have already reviewed this product.');
        }

        User_review::create([
            'user_id' => $user_id,
            'user_email' => Auth::user()->email,
            'user_name' => $request->user_name,
            'review' => $request->user_review,
            'product_id' => $validate['product_id'],
            'date' => now()->toDateString(),
            'time' => now()->toTimeString(),
            'rating' => $request->rating,
        ]);
        return back()->with('success', 'Review submitted successfully!');

    }


    //single Product page function
    public function singleProductPage(Request $request){
        $id = $request->product_id;
        $product = Product::with('images')->findOrFail($id);
        $image=Productimg::where('product_id',$id)->first();
        $Allreviews = User_review::where('product_id', $id)->limit(5)->get(); 
        

        return view('users.UsingleProduct', compact('product', 'Allreviews','image'));

    }

    
}
