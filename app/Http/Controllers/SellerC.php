<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Twilio\Rest\Client;

class SellerC extends Controller
{
public function updateStatus(Request $request, $id) {
    
    $order = Order::findOrFail($id);
    $order->status = $request->status;
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
        $userOrders = Order::whereRelation('product', 'seller_id', $user->id)->with(['product','product.images'])->get();  //better version thsn previous ones
        $todayOrders = $userOrders->where('created_at', '>=', now()->startOfDay())->count();
        $pendingOrders = $userOrders->where('status', 'Pending');
        $confirmedOrders = $userOrders->where('status', 'confirmed');
        $pendingPayout = Order::whereHas('product', function ($query) {
            $query->where('seller_id', Auth::id());
            })->where('status', 'delivered')->where('delivered_at', '>=', now()->subDays(3))->sum('totalAmount');

        return view('seller.sellerDashboard', compact('userOrders', 'todayOrders', 'pendingPayout','pendingOrders','confirmedOrders'));
    }

    public function sellerOrderedProducts(){
        $products=Product::with('images','orders')->where('seller_id',Auth::id())->get();
        // dd($products);
        return view('seller.sellerOrders',compact('products'));
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

        if(User::where('phone_number',$request->seller_phone)->exists()){
            return redirect('seller/sellerLogin')->with(['seller_phone' => 'Phone number already registered. Please log in.']);
        }
        else{
            $otp = rand(100000, 999999);
            session([
                'otp' => $otp,
                'phone' => $request->seller_phone,
                'otp_source' => 'seller'
            ]);

            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');
            $twilio = new Client($sid, $token);
            $twilio->messages->create(
                $request->seller_phone, // must be like +91XXXXXXXXXX
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
        $request->validate([
            'name'=>'required|string|max:255',
            'email' => 'required|email|unique:Users,email',
        ]);

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
            'account_status' => 'active',
            ])) {
            return back()->withErrors([
                'login_email' => 'Check your email and password and try again.',
            ])->withInput();
        }

        $request->session()->regenerate();
        $user = Auth::user();
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
        return Socialite::driver('google')
        ->redirectUrl('http://localhost:8000/auth/googlelogin/callback')
        ->redirect();
    }

    public function Logincallback(Request $request)
    {
        try {

        $user = Socialite::driver('google')
        ->redirectUrl('http://localhost:8000/auth/googlelogin/callback')
        ->user();

            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                Auth::Login($existingUser);
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
