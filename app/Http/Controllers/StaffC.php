<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class StaffC extends Controller
{
    public function staffOrders(){
        if(!Auth::check()){
            return redirect('seller/sellerLogin')->withErrors(['error' => 'Please log in to access the dashboard.']);
        }
        $user = Auth::user();
        // Fixed: Standardized status values to match database (lowercase)
        $deliveries = Order::with('product')->where('staff_id', $user->id)->whereIn('status', ['offDelivery', 'delivered'])->get();
        $pickups = Order::with('product')->where('staff_id', $user->id)->where('status', 'pickup')->get();
        return view('staff.orders', compact('deliveries', 'pickups', 'user'));  
    }

    public function deliverOrder(Request $request){
        $orderId = $request->order_id;
        $barcode = $request->barcode ?? null;
        $staff_id = Auth::id();

        // Require scanned barcode (or explicit product_id) to prevent random scans marking deliveries
        if (!$barcode && !$request->product_id) {
            return response()->json([
                'success' => false,
                'message' => 'No scanned value provided. Scan the product QR before marking delivered.'
            ]);
        }

        // Try to extract product id from barcode if present
        $productId = $request->product_id ?? null;
        if ($barcode && !$productId) {
            $parts = explode('|', $barcode);
            foreach ($parts as $p) {
                if (stripos($p, 'ProductID') !== false) {
                    $productId = trim(str_replace(['ProductID:', 'ProductID=', 'ProductID'], '', $p));
                    break;
                }
            }
            // fallback: extract first integer found
            if (!$productId) {
                if (preg_match('/(\d+)/', $barcode, $m)) {
                    $productId = $m[1];
                }
            }
        }

        $order = Order::where('staff_id', $staff_id)->where('id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or not assigned to you.'
            ]);
        }

        if ($order->status !== 'offDelivery') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not currently out for delivery.'
            ]);
        }

        // If we have a product id from the scan, ensure it matches the order's product
        if ($productId && $order->product_id != $productId) {
            return response()->json([
                'success' => false,
                'message' => 'Scanned product does not match this order.'
            ]);
        }

        $order->status = 'delivered';
        $order->delivered_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'staff_id' => $staff_id,
        ]);

    }

    public function assignOrder(Request $request){
        $rawData = $request->barcode; 

        $parts = explode('|', $rawData);
        $orderId = str_replace('OrderID:', '', $parts[0]);
        $productId = str_replace('ProductID:', '', $parts[1] ?? '');
        $staff_id = Auth::id();
        $user_id = Order::where('id', $orderId)->value('user_id');

            $order= Order::where('id', $orderId)->first();        
            if($order->status === 'confirmed'  && $order->staff_id === null){
                $order->status = 'offDelivery';
                $order->staff_id = $staff_id;
                $order->save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or already assigned.'
                ]);
            }
        return response()->json([
            'success' => true,
            'order_id' => $orderId,  
            'product_id' => $productId, 
        ]);
    }

    public function staffDashboard(){
        if(!Auth::check()){
            return redirect('seller/sellerLogin')->withErrors(['error' => 'Please log in to access the dashboard.']);
        }
        $user = Auth::user();
        // $deliveries = Order::with('product')->get();
        $deliveries = Order::with('product')->where('status', 'offDelivery')->get();
        $pickups = Order::with('product')->where('status', 'pickup')->get();
        return view('staff.Dashboard', compact('deliveries', 'pickups', 'user'));
    }


    public function submitSignupDetails(Request $request){
         
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|string|min:2|confirmed|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'dob' => 'required|date|max:50',
            'gender' => 'required|string|in:Male,Female,Other|max:20',
            'assigned_area' => 'required|string|in:Area 1,Area 2,Area 3',
            'vehicle_type' => 'required|string|in:Motorcycle,Car,Bicycle,Truck',
            'vehicle_no' => 'required|string|max:20',
            'license_no' => 'required|string|max:20',
        ]);
        

        try {

            $user = User::create([
                'name' => $request->name.' '.$request->last_name,
                'email' => $request->email,
                'phone_number' => session('phone'),
                'password' => bcrypt($request->password),
                'address' => $request->address . ', ' . $request->city . ', ' . $request->state,
                'zip' => $request->zip,
                'DOB' => $request->dob,
                'gender' => $request->gender,
                'role' => 'staff',
                'account_status' => 'active',
                'assigned_area' => $request->assigned_area,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_no' => $request->vehicle_no,
                'license_no' => $request->license_no,
            ]);

            session()->forget(['otp', 'phone', 'otp_source']);

            // Fixed: Redirect to staff login, not seller login
            return redirect('seller/sellerLogin')
                ->with('success', 'Staff registered successfully. Please log in.');

        } 
        catch (\Exception $e) {

            \Log::error('Signup Error: '.$e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }


    public function sendotp(Request $request){                              //staff registration otp  
        $request->validate([                                                //staff match otp is in SellerC because otp is sent from there 
            'staff_phone' => 'required',          //used otp_source to differentiate between seller and staff
            
        ]);

        // if (User::where('phone_number', $request->staff_phone)->exists()) {
        //     return back()->withErrors(['staff_phone' => 'Phone number already exists.']);
        // }
        // else{
            $otp = rand(100000, 999999);
            session([
                'otp' => $otp,
                'phone' => $request->staff_phone,
                'otp_source' => 'staff'
            ]);

            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');
            $twilio = new Client($sid, $token);
            $twilio->messages->create(
                $request->staff_phone, 
                [
                    'from' => $from,
                    'body' => "Your OTP is: $otp"
                ]
            );
            return redirect('seller/sellerMatchOTP')->with('success', 'OTP sent successfully!');
        
        // }
        
        return redirect('staff/staffLogin')->with('success', 'Staff registered successfully. Please log in.');
    }
}


// |unique:users,phone_number