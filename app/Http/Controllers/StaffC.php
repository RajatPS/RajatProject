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
        $deliveries = Order::with('product')->where('staff_id', $user->id)->whereIn('status', ['offDelivery', 'delivered'])->get();
        $pickups = Order::with('product')->where('staff_id', $user->id)->where('status', 'pickup')->get();
        return view('staff.orders', compact('deliveries', 'pickups', 'user'));  
    }

    public function deliverOrder(Request $request){
        $orderId = $request->order_id;
        $staff_id = Auth::id();

        $order= Order::where('staff_id', $staff_id)->where('id', $orderId)->first();
        
        if($order && $order->status === 'offDelivery'){
            $order->status = 'delivered';
            $order->delivered_at = now();
            $order->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or not assigned to you.'
            ]);
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId,  
            'staff_id' => $staff_id,
        ]);

    }

    public function assignOrder(Request $request){
        $rawData = $request->barcode; 

        // 2. Split the string into the two IDs
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
        return view('staff.dashboard', compact('deliveries', 'pickups', 'user'));
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

            return redirect('seller/sellerLogin')
                ->with('success', 'Staff registered successfully.');

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