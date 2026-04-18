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
        $admin = Auth::user();
        $admin->email = $request->input('email');
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }
        $admin->save();
        return back()->with('success', 'Profile updated successfully.');
    }
    

    public function deleteUser(Request $request){
        $userId = $request->input('userId');    //for all types of data use input() method
        $user=User::where('id', $userId)->where('role', '!=', ['admin', 'seller'])->first(); 
        if ($user) {
            $user->delete();
        }
        return back();
    }

    public function updateUserStatus(Request $request){
        // dd($request->all());
        $userId = $request->input('user_id'); 
        $newStatus = $request->input('status');

        $user = User::where('id', $userId)->where('role', '!=', ['admin', 'seller'])->first();
        if ($user) {
            $user->account_status = $newStatus;
            $user->save();
            return back()->with('success', 'User status updated successfully.');
        } else {
            return back()->withErrors(['error' => 'User not found.']);
        }
        
    }

    public function userManagement(){
        $users = User::whereNotIn('role', ['admin', 'seller'])->get();
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
        // if(!session()->has('adminName')){
        //     return redirect('admin/adminlogin');
        // }


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

        // dd($totalSales, $totalOrders, $totalProducts, $totalUsers);

        return view('admin/salesSummary', compact('totalProducts', 'currentYear', 'data'));
    }

    public function deleteOrder(Request $request){
        $orderId = $request->input('orderId');
        
        Order::destroy($orderId);
        return back();
    }

    public function updateOrderStatus(Request $request){
        $orderId = $request->order_id;
        $status = $request->status;
        $order = Order::find($orderId)->first();
        if ($order) {
            $order->status = $status;
            $order->save();
            return back()->with('success', 'Order status updated successfully.');
        }
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


    public function adminLogin(request $request){
        $request->validate([
            'login_email'=>'required|email',
            'login_password'=>'required|min:1',
        ]);

        if (Auth::attempt([
            'email' => $request->login_email,
            'password' => $request->login_password,
            'role' => 'admin',
        ])) {

            return redirect('admin/adminDashboard');
        }
        else{
            return back()->withErrors(['login_error' => 'Invalid email or password']);
        }
    }
}
