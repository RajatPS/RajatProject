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
    public function submitreturnReason(Request $request){
        $order_id= $request->order_id;
        $reason = $request->reason;
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

    public function returnOrder(Request $request){
        $order_id= $request->order_id;
        $order = Order::where([
                                'id'=>$order_id,
                                'user_id'=>Auth::id()
                                ])->first();

        if(!$order){
            return back()->with('error', 'Order not found or inaccessible.');
        }
        else{
            // Fixed: Changed to case-insensitive check for 'delivered'
            if(strtolower($order->status) == 'delivered'){
                return view('/users/UreturnOrderReason', compact('order'));
            } else {
                return back()->with('error', 'Only delivered orders can be returned.');
            }
        }

    }

    public function cancelOrder(Request $request){
        $order_id= $request->order_id;
        $check_order = Order::where([
                                'id'=>$order_id,
                                'user_id'=>Auth::id()
                                ])->first();

        if(!$check_order){
            return back()->with('error', 'Order not found or inaccessible.');
        }
        else{
            // Fixed: Changed 'Cancelled' to 'Cancelled' for consistency
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
                // Fixed: Ensure status values are consistent
                $order->status = ucfirst($order->status);
            }
            //check if can be returned or not
            foreach ($orders as $order) {
                // Fixed: Changed 'delivered' to 'delivered' and made case-insensitive
                if(strtolower($order->status) != 'delivered'){
                    $order->can_return = false;
                    continue;
                }
                $order->can_return = Carbon::parse($order->delivered_at)->addHours(24)->isFuture();
            }
        return view('users.Uview_Orders', compact('orders'));
    }



    
    public function addressDetails(Request $request){
        $products = json_decode($request->products, true) ?? [];
        if (empty($products)) {
            return back()->with('error', 'Select at least one product to buy.');
        }
    $productIds = collect($products)->pluck('id')->toArray();

    $cart = [];
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





        ////place order function
    public function placeOrder(Request $request) {
        $products = session('checkout_cart');

        if (!$products || count($products) === 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $request->merge([
            'user_phone_number' => preg_replace('/\s+/', '', $request->user_phone_number)
        ]);

        // Validation (Ensure your inputs are correct)
        $request->validate([
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_phone_number' => 'required',
            'user_address' => 'required',
            'paymentType' => 'required',
        ]);

        $orderInfo = [];
        $skippedProducts = []; 

        foreach ($products as $item) {
            $id = $item['product_id'];
            $productQty = $item['qty'];
            $product = Product::find($id);

            // --- FIXED: Check if product exists, is active, and has stock ---
            if (!$product || !$product->status || $product->stock < $productQty) {
                $reason = !$product ? "Product not found" : (!$product->status ? "Product unavailable" : "Insufficient stock");
                $skippedProducts[] = $product ? $product->product_name : "Unknown Product (ID: $id)" . " - $reason";
                continue;
            }

            // Calculations 
            $taxRate = 0.05;
            $subtotal = $product->price * $productQty;
            $shippingFee = ($productQty < 10) ? ($productQty * 10) : ($productQty * 5);
            $totalAmount = $subtotal + ($subtotal * $taxRate) + $shippingFee;

            // Create the order row
            $save = Order::create([
                'user_id'        => Auth::id(),
                'product_id'     => $id,
                'fullname'       => $request->user_name,
                'email'          => $request->user_email,
                'address'        => $request->user_address,
                'address2'       => $request->user_address2,
                'city'           => $request->user_city,
                'state'          => $request->user_state,
                'zip'            => $request->user_zip,
                'paymentMethod'  => $request->paymentType,
                'quantity'       => $productQty,
                'totalAmount'    => $totalAmount,
                'cardName'       => $request->cardName,
                'cardNumber'     => $request->cardNumber,
                'expmonth'       => $request->expMonth,
                'expyear'        => $request->expYear,
                'cvv'            => $request->cvv,
                'upi'            => $request->upi,
                'contact_number' => $request->user_phone_number,
                'status'         => 'Pending',
                'order_date'     => now()->toDateString(),
                'order_time'     => now()->toTimeString(),
            ]);

            $product->decrement('stock', $productQty);

            $orderInfo[] = [
                'order_id' => $save->id,
                'product_name' => $product->product_name,
                'quantity' => $productQty,
                'total_amount' => $totalAmount,
            ];
        }

        if (empty($orderInfo)) {
            return redirect()->back()->with('error', 'None of the items could be ordered due to insufficient stock.');
        }

        session()->put('orderInfo', $orderInfo);
        session()->put('payment_method', $request->paymentType);

        // Prepare a message if some items were skipped
        if (!empty($skippedProducts)) {
            $message = "Order placed successfully! However, the following items were skipped due to stock issues: " . implode(', ', $skippedProducts);
            return redirect('users/CODPayment')->with('success', $message);
        }

        return redirect('users/CODPayment');
    }

        

        public function showOrderSummary(Request $request){
            return view('users.CODPayment');

        }

//////////////////////////////////////////////////////////////////
        //admin orders table view

        public function viewOrders() {
            $orders = Order::with(['product.images'])->get();
            return view('admin.Amanageorders', compact('orders'));
        }

}

        



            

               

