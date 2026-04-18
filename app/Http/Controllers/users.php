<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Users extends Controller
{

    public function searchproduct(Request $request){
        $searchedProduct = $request-> input('query');
        $products = Product::where('category','LIKE','%' .$searchedProduct . '%')->orWhere('product_name', 'LIKE', '%' . $searchedProduct . '%')
        ->get();
        if(!$products){
            return view('users/Uproducts')->with('error','product not found');
        }
        return view('users.Uproducts',compact("products"));
    }


    //update user details
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

        // dd( $request->all());
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

    //edit user details page
    public function edit_details(Request $request){
        $user_id = Auth::id();
        $udetails = User::find($user_id);
        return view('users.UeditDetails', compact('udetails'));
    }

      //view user details page
    public function user_details(Request $request){
        $user_id = Auth::id();
        $user_details = User::find($user_id);
        return view('users.Udetails', compact('user_details'));
    }


    
    //signup function
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
                'account_status' => 'Active',
            ]);
            
            return redirect("users/Ulogin")->with('success', 'Signup successful! Please login to continue.');
        }
    }

    //login function
    public function login(Request $request)
    {
        $login=$request->validate([
            'login_email'=> 'required|email',
            'login_password' =>'required|min:2,'
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

    //logout function
    public function logout(){
        auth()->guard()->logout();
        return redirect('/');
    }


  

}

