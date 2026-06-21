# Laravel Project Search Report

## Overview
This report documents all files related to user status checking, login authentication, order creation/display, and seller/user order management in the RajatProject Laravel application.

---

## 1. USER MODEL & STATUS COLUMNS

### File: [app/Models/User.php](app/Models/User.php)
**Key Status Field:**
- `account_status` - enum column with values: `['active', 'inactive']` (default: 'active')

**User Model Structure:**
```php
class User extends Authenticatable
{
    use HasFactory;
    protected $fillable=[
        'name',
        'email',
        'phone_number',
        'address',
        'DOB',
        'gender',
        'password',
        'role',
        'account_status',  // USER STATUS FIELD
        'assigned_area',
        'vehicle_type',
        'vehicle_no',
        'license_no',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function cart(){
        return $this->hasMany(Cart::class,'user_id','id');
    }
}
```

### File: [database/migrations/2014_10_12_000000_create_users_table.php](database/migrations/2014_10_12_000000_create_users_table.php)
**User Table Schema:**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name')->nullable();
    $table->string('email')->unique();
    $table->string('password');
    $table->string('phone_number')->nullable();
    $table->string('address')->nullable();
    $table->date('DOB')->nullable();
    $table->string('gender')->nullable();
    $table->enum('role', ['user', 'seller', 'staff', 'admin'])->default('user');
    $table->enum('account_status', ['active', 'inactive'])->default('active'); // STATUS COLUMN
    $table->string('assigned_area')->nullable();
    $table->string('vehicle_type')->nullable();
    $table->string('vehicle_no')->nullable();
    $table->string('license_no')->nullable();
    $table->timestamp('LastSeen')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->index('email');
    $table->index('role');
});
```

---

## 2. LOGIN CONTROLLER & AUTHENTICATION

### File: [app/Http/Controllers/Users.php](app/Http/Controllers/Users.php)
**Login Function (User Authentication):**
```php
public function login(Request $request)
{
    $login=$request->validate([
        'login_email'=> 'required|email',
        'login_password' =>'required|min:2'
    ]);
    $userExists = User::where('email', $login['login_email'])->exists();
    if (!$userExists) {
        return redirect("users/Usignup")->withErrors(['login_email' => 'This email is not registered,you can signup to create a new account',])
        ->onlyInput('login_email');
    }

   if(auth()->guard()->attempt(['email'=>$login['login_email'],'password'=>$login['login_password'],'role'=>'user'])){
    $request->session()->regenerate();
    return redirect('/')->with('success', 'Login successful!');
   }
    else{
        return back()->withErrors(['login_email' => 'Invalid email or password.',])->onlyInput('login_email');
    }
}
```

**Signup Function (User Registration):**
```php
public function gmail(Request $request)
{
    $signup=$request->validate([
        'gmail' => 'required|email',
        'password' => 'required|max:255',
     ]);

    $existingUser = User::where('email', $signup['gmail'])->first();
    if($existingUser){
        return redirect("users/Ulogin")->withErrors(['duplicate_gmail' => 'This Gmail address is already registered.Please login here.'])
            ->onlyInput('duplicate_gmail');
    }  
    else{               
        $login_at_signup=User::create([
            'email' => $signup['gmail'],
            'password' =>Hash::make($signup['password']),
            'role' => 'user',
            'account_status' => 'Active',  // USER STATUS SET ON SIGNUP
        ]);
        
        return redirect("users/Ulogin")->with('success', 'Signup successful! Please login to continue.');
    }
}
```

**Logout Function:**
```php
public function logout(){
    auth()->guard()->logout();
    return redirect('/');
}
```

### File: [app/Http/Controllers/AuthController.php](app/Http/Controllers/AuthController.php)
**Google OAuth Login:**
```php
public function signupcallback()
{
    try {
        $user = Socialite::driver('google')
            ->redirectUrl('http://localhost:8000/auth/googlesignup/callback')
            ->user();

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return redirect('/seller/dashboard');
        } 
        else {
            $newUser = User::create([
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'seller',
            ]);
            Auth::login($newUser);
            return redirect('/seller/dashboard');
        }
    } 
    catch (\Throwable $e) {
        dd(['exception' => get_class($e), 'message' => $e->getMessage()]);
    }
}
```

### File: [app/Http/Middleware/RoleMiddleware.php](app/Http/Middleware/RoleMiddleware.php)
**Role-Based Access Control:**
```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }
        abort(403, 'Unauthorized access');
    }
}
```

---

## 3. ORDER MODEL & RELATED CODE

### File: [app/Models/Order.php](app/Models/Order.php)
**Order Model Structure:**
```php
class Order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'staff_id',
        'fullname',
        'contact_number',
        'email',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'paymentMethod',
        'quantity',
        'totalAmount',
        'product_id',
        'cardName',
        'cardNumber',
        'expmonth',
        'expyear',
        'cvv',
        'upi',
        'status',  // ORDER STATUS FIELD
        'order_date',
        'order_time',
    ];  

    protected $casts = [
        'order_date' => 'date',
        'order_time' => 'datetime',
    ];

    public function user(){
       return $this->belongsTo(User::class, 'user_id', 'id'); 
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productImages()
    {
        return $this->hasManyThrough(
            ProductImg::class,
            Product::class,
            'id',
            'product_id',
            'product_id',
            'id'
        );
    }
}
```

### File: [database/migrations/2024_01_05_000000_create_orders_table.php](database/migrations/2024_01_05_000000_create_orders_table.php)
**Order Table Schema:**
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('product_id');
    $table->unsignedBigInteger('staff_id')->nullable();
    $table->string('fullname');
    $table->string('contact_number');
    $table->string('email');
    $table->text('address');
    $table->text('address2')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('zip')->nullable();
    $table->string('paymentMethod')->nullable();
    $table->integer('quantity')->default(1);
    $table->decimal('totalAmount', 12, 2);
    $table->string('cardName')->nullable();
    $table->string('cardNumber')->nullable();
    $table->string('expmonth')->nullable();
    $table->string('expyear')->nullable();
    $table->string('cvv')->nullable();
    $table->string('upi')->nullable();
    $table->string('status')->default('Pending');  // ORDER STATUS
    $table->date('order_date')->nullable();
    $table->time('order_time')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->text('reason')->nullable(); // for return reason
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
    $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
    $table->index('user_id');
    $table->index('product_id');
    $table->index('status');
    $table->index('order_date');
});
```

---

## 4. ORDER CONTROLLER & ORDER MANAGEMENT

### File: [app/Http/Controllers/OrderC.php](app/Http/Controllers/OrderC.php)

**Create Order (Place Order Function):**
```php
public function placeOrder(Request $request) {
    $products = session('checkout_cart');

    if (!$products || count($products) === 0) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

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

        if (!$product || $product->stock < $productQty) {
            $skippedProducts[] = $product ? $product->product_name : "Unknown Product (ID: $id)";
            continue;
        }

        $taxRate = 0.05;
        $subtotal = $product->price * $productQty;
        $shippingFee = ($productQty < 10) ? ($productQty * 10) : ($productQty * 5);
        $totalAmount = $subtotal + ($subtotal * $taxRate) + $shippingFee;

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
            'status'         => 'Pending',  // ORDER STATUS CREATED AS PENDING
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
}
```

**View User Orders:**
```php
public function userOrders(){
    $orders = Order::with(['product.images'])->where('user_id', Auth::id())->get();
    
    // Check if can be cancelled or not
    foreach ($orders as $order) {
        $order->can_cancel = Carbon::parse($order->order_date)->addHours(24)->isFuture();
        $order->status = ucfirst($order->status);
    }
    
    // Check if can be returned or not
    foreach ($orders as $order) {
        if($order->status != 'delivered'){
            $order->can_return = false;
            continue;
        }
        $order->can_return = Carbon::parse($order->delivered_at)->addHours(24)->isFuture();
    }
    
    return view('users.Uview_Orders', compact('orders'));
}
```

**Cancel Order:**
```php
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
        $check_order->status = 'Cancelled';
        $check_order->save();
        return back()->with('success', 'Order cancelled successfully!');
    }
}
```

**Return Order:**
```php
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
        if($order->status == 'delivered'){
            return view('/users/UreturnOrderReason', compact('order'));
        } else {
            return back()->with('error', 'Only delivered orders can be returned.');
        }
    }
}
```

---

## 5. ADMIN CONTROLLER - USER STATUS MANAGEMENT

### File: [app/Http/Controllers/AdminController.php](app/Http/Controllers/AdminController.php)

**Update User Status (Enable/Disable Users):**
```php
public function updateUserStatus($userId)
{
    $user = User::where('id', $userId)
                ->whereNotIn('role', ['admin'])
                ->first();

    if ($user) {
        $newStatus = ($user->account_status === 'active') ? 'inactive' : 'active';
        $user->account_status = $newStatus;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.'
        ]);
    }
    return response()->json([
        'success' => false,
        'message' => 'User not found or status cannot be modified.'
    ], 404);
}
```

**User Management (View All Users):**
```php
public function userManagement(){
    $users = User::whereNotIn('role', ['admin'])->get();
    return view('admin/AdminUserManagement', compact('users'));
}
```

**Update Order Status:**
```php
public function updateOrderStatus(Request $request){
   $orderId = $request->route('orderId');
   $status = $request->input('status');
    $order = Order::find($orderId);
    if ($order) {
        $order->status = $status;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Order status updated successfully.']);
    }
    return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
}
```

**Admin Dashboard (View Sales):**
```php
public function adminPanel(){
    $sales = Order::sum('totalAmount');
    $newOrders = Order::whereDate('order_date', Carbon::today())->count();
    $onlineUsers = User::where('LastSeen', '>=', now()->subMinutes(5))->where('role','!=', 'admin')->count();
    if(Auth::check() && Auth::user()->role === 'admin'){
        return view('admin/adminDashboard', compact('sales', 'newOrders', 'onlineUsers'));
    }
    else{
        return redirect('admin/adminlogin');
    }
}
```

---

## 6. SELLER CONTROLLER - ORDER MANAGEMENT

### File: [app/Http/Controllers/SellerC.php](app/Http/Controllers/SellerC.php)

**Seller Dashboard (View Their Orders):**
```php
public function sellerDashboard(){
    $user = Auth::user();
    $userOrders = Order::whereRelation('product', 'seller_id', $user->id)->with(['product','product.images'])->get();
    $todayOrders = $userOrders->where('created_at', '>=', now()->startOfDay())->count();
    $pendingOrders = $userOrders->where('status', 'Pending');
    $confirmedOrders = $userOrders->where('status', 'confirmed');
    $pendingPayout = Order::whereHas('product', function ($query) {
        $query->where('seller_id', Auth::id());
        })->where('status', 'delivered')->where('delivered_at', '>=', now()->subDays(3))->sum('totalAmount');

    return view('seller.sellerDashboard', compact('userOrders', 'todayOrders', 'pendingPayout','pendingOrders','confirmedOrders'));
}
```

**Seller Orders (View Products with Orders):**
```php
public function sellerOrderedProducts(){
    $products=Product::with('images','orders')->where('seller_id',Auth::id())->latest()->get();
    return view('seller.sellerOrders',compact('products'));
}
```

**Update Order Status (Seller Side):**
```php
public function updateStatus(Request $request){ 
     $id = $request->route('id');
    $orderId = $id;
    $status = $request->input('status');
    $order = Order::findOrFail($orderId);
    $order->status = $status;
    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
}
```

---

## 7. USER EDIT & DETAILS PAGE

### File: [app/Http/Controllers/Users.php](app/Http/Controllers/Users.php)

**Edit User Details (View Page):**
```php
public function edit_details(Request $request){
    $user_id = Auth::id();
    $udetails = User::find($user_id);
    return view('users.UeditDetails', compact('udetails'));
}
```

**Update User Details:**
```php
public function update_details(Request $request){
    $user_id = Auth::id();
     
    $request->validate([
        'name' => 'required|string|max:50|min:3',
        'email' => 'required|email|max:100',
        'phone' => 'required|string|max:12|min:10',
        'address' => 'required|string|max:500',
        'DOB' => 'required|date',
        'gender' => 'required|string|max:10',
    ]);

    User::where('id', $user_id)->update([
        'name' => $request->name,
        'email' =>$request->email,
        'phone_number' =>$request->phone,
        'address' =>$request->address,
        'DOB' =>$request->DOB,
        'gender' =>$request->gender
    ]);
    return redirect('/users/Udetails')->with('success', 'Details updated successfully!');
}
```

**View User Details:**
```php
public function user_details(Request $request){
    $user_id = Auth::id();
    $user_details = User::find($user_id);
    return view('users.Udetails', compact('user_details'));
}
```

### File: [resources/views/users/UeditDetails.blade.php](resources/views/users/UeditDetails.blade.php)
**User Edit Page** - Displays form for editing user information

---

## 8. USER ORDER VIEW PAGE

### File: [resources/views/users/Uview_Orders.blade.php](resources/views/users/Uview_Orders.blade.php)
**User Order History Page** - Displays all user orders with:
- Order status
- Product details
- Order date/time
- Cancel/Return buttons (based on status & time)

**Key Features:**
- Shows order status (Pending, Confirmed, Delivered, Cancelled, Returned)
- Can cancel orders within 24 hours of placement
- Can return delivered orders within 24 hours of delivery
- Displays product images and details via `with(['product.images'])`

---

## 9. SELLER ORDER PAGES

### File: [resources/views/seller/sellerDashboard.blade.php](resources/views/seller/sellerDashboard.blade.php)
**Seller Dashboard** - Displays:
- Today's order count
- Pending orders
- Confirmed orders
- Pending payout amount
- Table of all seller orders with status

### File: [resources/views/seller/sellerOrders.blade.php](resources/views/seller/sellerOrders.blade.php)
**Seller Orders Management Page** - Displays:
- All products with their associated orders
- Order shipment tracking
- Order status management
- Filter buttons for different order statuses
- Table with order information

---

## 10. ADMIN USER MANAGEMENT PAGE

### File: [resources/views/admin/AdminUserManagement.blade.php](resources/views/admin/AdminUserManagement.blade.php)
**Admin User Management Page** - Features:
- Search functionality for users
- Display all users with their details
- Toggle user account status (active/inactive)
- Delete users functionality
- Shows user roles and account status

---

## ROUTES SUMMARY

### File: [routes/web.php](routes/web.php)

**Key Routes:**
```php
// User Authentication
Route::get('users/Usignup', function () {
    return view('users.Usignup');
});
Route::post('users/Usignup',[Users::class,'gmail'])->name('Usignup');

Route::get('/users/Ulogin', function () {
    return view('users.Ulogin');
});
Route::post('users/Ulogin', [Users::class,'login'])->name('login');

// Protected Routes (Auth Required)
Route::middleware(['auth',])->group(function () {
    Route::post('/users/Ulogout',[Users::class,'logout']);
    
    // User Details
    Route::get('/users/Udetails',[Users::class,'user_details']);
    Route::post('/users/Udetails',[Users::class,'update_details']);
    Route::get('/users/UeditDetails',[Users::class,'edit_details']);
    
    // Orders
    Route::post('/users/Ubuyproduct',[OrderC::class,'addressDetails']);
    Route::get("users/Uview_Orders",[OrderC::class,'userOrders']);
    Route::post('users/cancelOrder',[OrderC::class,'cancelOrder']);
    Route::post('users/returnOrder',[OrderC::class,'returnOrder']);
    Route::post('orders/returnReason',[OrderC::class,'submitreturnReason']);
});
```

---

## STATUS VALUES

### User Account Status:
- `active` - User account is active and can login
- `inactive` - User account is disabled and cannot login

### Order Status Values:
- `Pending` - Order placed, awaiting confirmation
- `confirmed` - Order confirmed by seller
- `shipped` - Order shipped
- `delivered` - Order delivered to customer
- `Cancelled` - Order cancelled by user or admin
- `returned` - Order returned by customer

---

## KEY AUTHENTICATION FLOWS

### User Signup Flow:
1. User fills signup form with email and password
2. System checks if email already exists
3. If not exists: Create new user with `role='user'` and `account_status='Active'`
4. Redirect to login page

### User Login Flow:
1. User enters email and password
2. System authenticates with `auth()->guard()->attempt()`
3. Checks role is 'user'
4. Regenerates session
5. Redirects to home page

### Order Creation Flow:
1. User adds products to cart
2. Proceeds to checkout
3. Enters delivery address
4. Selects payment method
5. System validates stock availability
6. Creates Order records with `status='Pending'`
7. Decrements product stock

### Order Status Update Flow (Admin/Seller):
1. Admin or Seller views orders
2. Selects new status from dropdown
3. Submits status update via AJAX/Form
4. System updates Order.status field
5. Returns success response

