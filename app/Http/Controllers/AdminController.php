<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function adminLogout(){
        Auth::logout();
        return redirect('admin/adminLogin');
    }

    public function updateAdminProfile(Request $request){
        // Authorization: Verify admin is authenticated
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized access.');
        }
        
        $admin = Auth::user();
        $admin->email = $request->input('email');
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }
        $admin->save();
        return back()->with('success', 'Profile updated successfully.');
    }
    

    public function deleteUser(Request $request){
        $userId = $request->input('user_id');    
        $user=User::where('id', $userId)->whereNotIn('role', ['admin'])->first(); 
        if ($user) {
            $user->delete();
            return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'User not found or cannot be deleted.'
        ], 404);
        
    }

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


    public function userManagement(){
        $users = User::whereNotIn('role', ['admin'])->get();
        return view('admin/AdminUserManagement', compact('users'));
    }

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


    public function salesSummary(){

        $totalProducts= Product::count();
        $currentYear = date('Y');

            $rows = Order::selectRaw("
                YEAR(order_date) as year,
                MONTH(order_date) as month_number,
                DATE_FORMAT(order_date, '%b') as month,
                SUM(totalAmount) as sales,
                COUNT(id) as orders,
                COUNT(DISTINCT user_id) as customers
            ")
            ->groupByRaw("YEAR(order_date), MONTH(order_date),  DATE_FORMAT(order_date, '%b')")
            ->orderByRaw("YEAR(order_date), MONTH(order_date)")
            ->get();

        $data = [];

            foreach ($rows as $row) {
            $data[$row->year][] = [
                'month' => $row->month,
                'sales' => (int) $row->sales,
                'orders' => (int) $row->orders,
                'customers' => (int) $row->customers, 
            ];
        }


        return view('admin/salesSummary', compact('totalProducts', 'currentYear', 'data'));
    }

    public function deleteOrder(Request $request){
        $id = $request->input('id');
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false , 'message' => 'Order not found.']);
        }
        Order::destroy($id);
        return response()->json(['success' => true , 'message' => 'Order deleted successfully.']);
    }

    public function updateOrderStatus(Request $request){
       $orderId = $request->route('orderId');
       $status = $request->input('status');
       
       // Validate status is one of the allowed values
       $validStatuses = ['Pending', 'Confirmed', 'offDelivery', 'delivered', 'Cancelled', 'returned'];
       if (!in_array($status, $validStatuses)) {
           return response()->json(['success' => false, 'message' => 'Invalid status value.'], 400);
       }
       
       $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();
            return response()->json(['success' => true, 'message' => 'Order status updated successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
    }


    public function adminSignup(Request $request){
        $request->validate([
            'gmail'=>'required|email',
            'password'=>'required|min:5',
        ]);

        $user=User::create([
            'email'=>$request->gmail,
            'password'=>bcrypt($request->password),
            'role'=>'admin',
        ]);
        return view('admin/adminLogin')->with('success', 'Admin registered successfully. Please login.');
    }


    public function adminLogin(Request $request){
        $request->validate([
            'login_email'=>'required|email',
            'login_password'=>'required|min:1',
        ]);

        if (Auth::attempt([
            'email' => $request->login_email,
            'password' => $request->login_password,
            'role' => 'admin',
        ])) {
            $user = Auth::user();
            
            // Fixed: Check if admin account is active
            if (strtolower($user->account_status) !== 'active') {
                Auth::logout();
                return back()->withErrors(['login_error' => 'Your account has been disabled.']);
            }

            return redirect('admin/adminDashboard');
        }
        else{
            return back()->withErrors(['login_error' => 'Invalid email or password']);
        }
    }
}
