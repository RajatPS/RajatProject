<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;


class OrderC extends Controller
{
    public function submitreturnReason(Request $Request){
        $order_id= $Request->order_id;
        $reason = $Request->reason;
        $order = Order::where([
                                'id'=>$order_id,
                                'user_id'=>Auth::id()
                                ])->first();

        if(!$order){
            return redirect('users/Uview_Orders')->with('error', 'Order not found or inaccessible.');
        }
        else{
            if($order->status == 'delivered'){
                $order->status = 'returned';
                $order->reason = $reason;
                $order->save();
                return redirect('users/Uview_Orders')->with('success', 'Order returned successfully!');
            } else {
                return redirect('users/Uview_Orders')->with('error', 'Only delivered orders can be returned.');
            }
        }

    }

    public function returnOrder(Request $Request){
        $order_id= $Request->order_id;
        $order = Order::where([
                                'id'=>$order_id,
                                'user_id'=>Auth::id()
                                ])->first();

        if(!$order){
            return back()->with('error', 'Order not found or inaccessible.');
        }
        else{
            if($order->status == 'delivered'){
                return view('/users/UreturnOrderReason', compact('order'));
            } else {
                return back()->with('error', 'Only delivered orders can be returned.');
            }
        }

    }

    public function cancelOrder(Request $Request){
        $order_id= $Request->order_id;
        // dd(Auth::id());
        $check_order = Order::where([
                                'id'=>$order_id,
                                'user_id'=>Auth::id()
                                ])->first();

        if(!$check_order){
            return back()->with('error', 'Order not found or inaccessible.');
        }
        else{
            $check_order->status = 'Cancelled';
            
            $check_order->save();
            return back()->with('success', 'Order cancelled successfully!');
        }

    }

    public function userOrders(){
        $orders = Order::with(['product.images'])->where('user_id', Auth::id())->get();
            ///////check if can be cancelled or not
            foreach ($orders as $order) {
                $order->can_cancel = Carbon::parse($order->order_date)->addHours(24)->isFuture();
            }
            //check if can be returned or not
            foreach ($orders as $order) {
                if($order->status != 'delivered'){
                    $order->can_return = false;
                    continue;
                }
                $order->can_return = Carbon::parse($order->delivered_at)->addHours(24)->isFuture();
            }
        return view('users.Uview_Orders', compact('orders'));
    }



    
    public function addressDetails(Request $Request){
        $products = json_decode($Request->products, true) ?? [];
        if (empty($products)) {
            return back()->with('error', 'Select at least one product to buy.');
        }
    $productIds = collect($products)->pluck('id')->toArray();

    foreach($products as $item){
        if (!isset($item['id'], $item['qty'])) {
            continue;
        }

        $cart[] = [
            'product_id' => (int) $item['id'],
            'qty'        => max(1, (int) $item['qty']),
        ];
    }

        // Store in session
        session()->put('checkout_cart', $cart);
        return view('users.UbuyProduct', compact('productIds'));
    }



        ////plaace order function
        public function placeOrder(Request $request){ 

            //fetch product ids and qty from session
            $products = session('checkout_cart');

                if (!$products || count($products) === 0) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }
            $request->merge([
                'user_phone_number' => preg_replace('/\s+/', '', $request->user_phone_number)  //to merge and remove spaces from contact number
            ]);
            $validated = $request->validate([
                'user_name' => 'required|max:50',
                'user_email' => 'required|email',
                'user_phone_number' => 'required|digits_between:9,13',
                'user_address' => 'required',
                'user_address2' => 'nullable',
                'user_city' => 'required',
                'user_state' => 'required',
                'user_zip' => 'required',
                'paymentType' => 'required',
                // 'productQuantity' => 'required',
                // 'totalAmount' => 'required',                         fetched from session
                'cardNumber' => 'required_if:paymentType,card',
                'cardName' => 'required_if:paymentType,card',
                'expMonth' => 'required_if:paymentType,card',
                'expYear' => 'required_if:paymentType,card',
                'cvv' => 'required_if:paymentType,card',
                'upi' => 'required_if:paymentType,upi',
            ]);
            

            foreach ($products as $item) {
                $totalAmount = 0;
                $productQty = $item['qty'];
                $id = $item['product_id'];

                $product = Product::find($id);
                if ($product) {
                    $tax = 0.05;
                    $subtotal = $product->price * $productQty;
                    $total = $subtotal * $tax;

                    if($productQty < 10){
                        $shippingFee = $productQty * 10;
                    }else{
                        $shippingFee = $productQty * 5;
                    }
                    $shippingFee = $shippingFee;

                    $totalAmount = $subtotal + $total + $shippingFee;
                }


                if(!$product || $product->stock < $productQty){
                    return back()->with('error', 'Product is out of stock or insufficient quantity.');
                }
                else{
                    $save = Order::create([
                        'user_id'=>Auth::id(),
                        'product_id'=>$id,
                        'fullname'=>$request->user_name,
                        'email'=>$request->user_email,
                        'address'=>$request->user_address,
                        'address2'=>$request->user_address2,
                        'city'=>$request->user_city,
                        'state'=>$request->user_state,
                        'zip'=>$request->user_zip,
                        'paymentMethod'=>$request->paymentType,
                        'quantity'=>$productQty,
                        'totalAmount'=>$totalAmount,             ////////////fix it later
                        'cardName'=>$request->cardName,
                        'cardNumber'=>$request->cardNumber,
                        'expmonth'=>$request->expMonth,
                        'expyear'=>$request->expYear,
                        'cvv'=>$request->cvv,
                        'upi'=>$request->upi,
                        'contact_number'=>$request->user_phone_number,
                        'status'=>'Pending',
                        'order_date' => now()->toDateString(),
                        'order_time' => now()->toTimeString(), 
                    ]); 
                    $product->decrement('stock', $productQty);
                    return redirect('/Uproducts')->with('success','Order Placed Successfully');
                }
            }
        }

//////////////////////////////////////////////////////////////////
        //admin orders table view

        public function viewOrders() {
            $orders = Order::with(['product.images'])->get();
            return view('admin.Amanageorders', compact('orders'));
        }

}

        



            

               

