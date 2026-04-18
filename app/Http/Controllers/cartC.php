<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class cartC extends Controller
{

    public function removeFromCart(Request $request) {
        $userId = Auth::id();
        $productsData = json_decode($request->input('products'), true);

        if (!$productsData || !is_array($productsData)) {
            return back()->with('error', 'No products selected.');
        }
        $productIds = array_column($productsData, 'id');

        Cart::where('user_id', $userId)->whereIn('product_id', $productIds)->delete();
        return back()->with('success', 'Selected products removed from cart successfully.');
    }

    public function cart(){
        $userId = Auth::id();
        $cartItems = Cart::where('user_id',$userId)->get();
        $productId=$cartItems->pluck("product_id");
        $products= Product::whereIn('id',$productId)->get();
        return view('users.Ucart',compact('cartItems','products'));

    }



    /////////////////////////////add to cart functionallity/////////////////////////////

    public function addToCart(Request $request){
        
        $userId=Auth::id();
        $productId=$request->product_id;
        $quantity=1;
        $existingItem=Cart::where('user_id',$userId)->where('product_id',$productId)->first();

        if (!$userId){
            return response()->json([
            'success'=>false,
            'message'=>'you are not logged in.',
        ]);
            
        }
        else{
            if ($existingItem){
                $existingItem->update([
                    'quantity' => $existingItem->quantity + 1
                ]);

                return response()->json([
                'success'=>true,
                'message'=>'product added to cart successfully.',
                ]);
            }
            else{
                $cart=Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);

                return response()->json([
                'success'=>true,
                'message'=>'Product added to cart',
                'cart'=>$cart->quantity,
                ]);
            }
           
        }
    }
}
