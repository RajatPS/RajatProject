<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Twilio\Rest\Client;

class SellerC extends Controller
{
    public function updateStatus(Request $request){ 
         $id = $request->route('id');
        $orderId = $id;
        $status = $request->input('status');
        
        // Verify that the order belongs to this seller's product
        $order = Order::whereHas('product', function ($query) {
            $query->where('seller_id', Auth::id());
        })->findOrFail($orderId);
        
        $order->status = $status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    
    }


    public function sellerLogout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/seller/sellerLogin');
    }

    public function sellerDashboard(){
        $user = Auth::user();
        $userOrders = Order::whereRelation('product', 'seller_id', $user->id)->with(['product','product.images'])->get();
        // Fixed: Use order_date instead of created_at (Order model has timestamps=false)
        $todayOrders = $userOrders->where('order_date', Carbon::today())->count();
        $pendingOrders = $userOrders->where('status', 'Pending');
        // Fixed: Changed 'confirmed' to 'Confirmed' to match database values
        $confirmedOrders = $userOrders->where('status', 'Confirmed');
        // Note: Payout logic shows orders delivered in last 3 days - adjust if different intent
        $pendingPayout = Order::whereHas('product', function ($query) {
            $query->where('seller_id', Auth::id());
            })->where('status', 'delivered')->where('delivered_at', '>=', now()->subDays(3))->sum('totalAmount');

        return view('seller.sellerDashboard', compact('userOrders', 'todayOrders', 'pendingPayout','pendingOrders','confirmedOrders'));
    }

    public function sellerOrderedProducts(){
        $seller_id = Auth::id();
        // Get orders for this seller's products, with product and image relationships
        $orders = Order::whereRelation('product', 'seller_id', $seller_id)
                      ->with(['product.images', 'user'])
                      ->orderByDesc('order_date')
                      ->get();
        
        // Group orders by product for display (alternative to product->orders relationship)
        $products = Product::with(['images', 'orders' => function($query) use ($seller_id) {
            // No additional filtering needed as products are already filtered by seller_id
        }])->where('seller_id', $seller_id)->latest()->get();
        
        return view('seller.sellerOrders', compact('orders', 'products'));
    }


    public function sellerProducts(){
        $products = Product::with('images', 'orders')->where('seller_id', Auth::id())->get();
        return view('seller.sellerProducts', compact('products',));
    } 

//////////////////////////////////////////////// seller registration
    public function sendOTP(Request $request){

        $request->validate([
            'seller_phone' => 'required',
        ]);
        $fullPhoneNumber = '+91' . $request->seller_phone;

        if(User::where('phone_number',$fullPhoneNumber)->where('role', 'seller')->exists()){
            return redirect('seller/sellerLogin')->with(['seller_phone' => 'Phone number already registered. Please log in.']);
        }
        else{
            $otp = rand(100000, 999999);
            session([
                'otp' => $otp,
                'phone' => $fullPhoneNumber,
                'otp_source' => 'seller'
            ]);

            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');
            
            // Validate Twilio configuration
            if (!$sid || !$token || !$from) {
                return back()->withErrors(['error' => 'Twilio configuration is missing. Please contact administrator.']);
            }
            
            $twilio = new Client($sid, $token);
            $twilio->messages->create(
                $fullPhoneNumber, 
                [
                    'from' => $from,
                    'body' => "Your OTP is: $otp"
                ]
            );
            return redirect('seller/sellerMatchOTP')->with('success', 'OTP sent successfully!');
        }
    }


    public function matchotp(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);
        $enteredOtp = implode('', $request->otp);

        if($enteredOtp==session('otp')){
            session()->forget('otp'); 
            session()->put('otp_verified', true);
            if(session('otp_source') == 'seller'){
                session()->forget('otp_source');
                return redirect('seller/sellerDetails');
            }
            elseif (session('otp_source') === 'staff') {
                session()->forget('otp_source');    
                return redirect('staff/SignupDetails');
            }
            return redirect('/');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    }

  
    public function sellerDetails(Request $request){
        // dd($request->all());
        try {
            $request->validate([
            'name'=>'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:2|confirmed',
        ]);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return back()->withErrors($e->validator)->withInput();  
        }

        if (!session('otp_verified')) {
            return redirect('seller/sellerMatchOTP')->withErrors(['otp' => 'Please verify your OTP first.']);
        }

        $seller_phone = session('phone');

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $seller_phone,
            'role' => 'seller',
            'account_status' => 'active',
        ]);

        Auth::login($data);
        session()->forget(['otp_verified', 'phone']);
        return redirect('seller/sellerDashboard');
    }

    ////seller login

    public function sellerLogin(Request $request)
    {
        
        $credentials = $request->validate([
            'login_email' => 'required|email',
            'login_password' => 'required',
        ]);
        

        if (!Auth::attempt([
            'email' => $credentials['login_email'],
            'password' => $credentials['login_password'],
            ])) {
            return back()->withErrors([
                'login_email' => 'Check your email and password and try again.',
            ])->withInput();
        }

        $user = Auth::user();
        
        // Check if account is active
        if (strtolower($user->account_status) !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'login_email' => 'Your account has been disabled. Please contact support.',
            ])->withInput();
        }
        
        // Check if user has correct role
        if ($user->role !== 'seller' && $user->role !== 'staff') {
            Auth::logout();
            return back()->withErrors([
                'login_email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();
        
        if ($user->role === 'staff') {
            return redirect('staff/Dashboard');
        }

        if ($user->role === 'seller') {
            return redirect('seller/sellerDashboard');
        }
        
        Auth::logout();
        return back()->withErrors([
            'login_email' => 'Check your email and password and try again.',
        ]);
    }


/////////////// google login for sellers

   public function Loginredirect(Request $request)
    {
        // Fixed: Use config instead of hardcoded localhost URL
        $redirectUrl = config('app.url') . '/auth/googlelogin/callback';
        return Socialite::driver('google')
        ->redirectUrl($redirectUrl)
        ->redirect();
    }

    public function Logincallback(Request $request)
    {
        try {
        // Fixed: Use config instead of hardcoded localhost URL
        $redirectUrl = config('app.url') . '/auth/googlelogin/callback';
        $user = Socialite::driver('google')
        ->redirectUrl($redirectUrl)
        ->user();

            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                Auth::login($existingUser);
                return redirect('/seller/dashboard');
            } 
            
            else {
                return redirect('/seller/signup')->withErrors(['email' => 'No account found with this email. Please sign up first.']);
            }
        }
         
        catch (\Throwable $e) {
            dd([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'session' => session()->all(),
                'request_state' => request()->get('state'),
                'session_state' => session('state')
                
            ]);
        }
    }
}
