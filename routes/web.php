<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartC;
use App\Http\Controllers\OrderC; 
use App\Http\Controllers\Products;
use App\Http\Controllers\SellerC;
use App\Http\Controllers\StaffC;
use App\Http\Controllers\UserReviewC;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('users.Uhome');
// });

// Route::get('Uhome', function () {
//     return view('users.Uhome');
// });

Route::get('Ufeatures', function () {
    return view('users.Ufeatures');
});

Route::get('Uabout', function () {
    return view('users.Uabout');
});

Route::get('Ucontact', function () {
    return view('users.Ucontact');
});

Route::get('/',[Products::class,'viewProducts']);
Route::get('/Uproducts',[Products::class,'viewProducts']);

/////////////////////////////////////////////////////////////////// users signup and login routes//////

Route::get('users/Usignup', function () {
    return view('users.Usignup');
});
Route::post('users/Usignup',[Users::class,'gmail'])->name('Usignup');

Route::get('/users/Ulogin', function () {
    return view('users.Ulogin');
});
Route::post('users/Ulogin', [Users::class,'login'])->name('login');

Route::middleware(['auth',])->group(function () {

        ///////////// users payments route ////////////////////////

        // route::post('users/UplaceOrder',[OrderC::class,'placeOrder']);

        ////////////////////////////////////////////////////////////////
        //logout route
        Route::post('/users/Ulogout',[Users::class,'logout']);

        ////users details
        Route::get('/users/Udetails',[Users::class,'user_details']);
        Route::post('/users/Udetails',[Users::class,'update_details']);

        //edit details
        Route::get('/users/UeditDetails',[Users::class,'edit_details']);


        /////////////////////////////////////////////////////////////////

        Route::get('/forgot_password', function () {
            return view('users/UforgotP');
        });

        Route::get('/csrf-token', function () {
            return response()->json(['token' => csrf_token()]);
        });

        Route::get('users/Ucart',[CartC::class,'cart']);                            //go to cart
        Route::post('/Ucart',[CartC::class,'addToCart'])->name('cart.add');         // add to cart
        Route::post('users/removeProductsFromCart',[CartC::class,'removeFromCart']);  // remove from cart

        Route::post('users/Ucheckout',[Products::class,'productcheckout']);  // checkout route
        Route::post('/users/Ubuyproduct',[OrderC::class,'addressDetails']);  // buy product route
        Route::post('/users/returnOrder',[OrderC::class,'returnOrder']);  // return order route
        Route::post('users/cancelOrder',[OrderC::class,'cancelOrder']);  // cancel order route
        Route::post('users/UreturnOrderReason',[OrderC::class,'returnOrderReason']);  // return reason order route
        Route::post('orders/returnReason',[OrderC::class,'submitreturnReason']);  // return reason order route
        Route::get("users/Uview_Orders",[OrderC::class,'userOrders']);  // user view orders route
        Route::get('users/Uproduct_details/{id}',[Products::class,'buyProduct']); // product details route
        Route::get ('users/UsingleProduct/',[UserReviewC::class,'singleProductPage']);  // single product page route
        Route::post ('users/review/',[UserReviewC::class,'addReview'])->name('addReview');  // add review route
        Route::get('users/help',function(){
            return view('users.help');
        });
        Route::post('users/search',[Users::class,'searchproduct']);
});

// route::post('users/CardPayment',function(){
//     return view('users.CardPayment');
// });
// route::post('users/CardPayment',[OrderC::class,'placeOrder']);

// route::post('UpiPayment/',[OrderC::class,'upiPayment']);
Route::post('users/CodPayment/',[OrderC::class,'placeOrder']);
Route::get('users/CODPayment',[OrderC::class,'showOrderSummary']);

        ////////////////////////////////   admin routes  ///////////////////////////////////////////////////////

Route::get('admin/adminLogin',function(){
    return view('admin/adminLogin');
});
Route::post('admin/adminLogin',[AdminController::class,'adminLogin']);


Route::get('admin/adminSignup',function(){
    return view('admin/adminSignup');
});
Route::post('admin/adminSignup',[AdminController::class,'adminSignup']);

Route::middleware(['auth',])->group(function () {

        Route::get("admin/Aaddproducts",function(){
            return view('admin.Aaddproducts');
        });


        Route::get('admin/adminDashboard',[AdminController::class,'adminPanel']);

        Route::get('admin/Amanageorders',[OrderC::class,'viewOrders']); // add Products
        Route::post("admin/deleteOrder/",[AdminController::class,'deleteOrder']); // delete order
        Route::post("/admin/orders/{orderId}/update-status",[AdminController::class,'updateOrderStatus']); // update order status
        Route::POST("Aaddproducts",[Products::class,'addProducts']);// admin view Products
        Route::get('admin/Aproducts', [Products::class, 'index']);
        Route::POST('admin/Aproducts/{id}/toggle', [Products::class, 'toggleStatus']);

        Route::get('admin/AdminUserManagement',[AdminController::class,'userManagement']);
        Route::post('/admin/users/{userId}/toggle-status',[AdminController::class,'updateUserStatus']);
        Route::post('admin/deleteUser',[AdminController::class,'deleteUser']);
        Route::post('admin/profile',[AdminController::class,'updateAdminProfile']);
        Route::post('admin/logout',[AdminController::class,'adminLogout']);



        /////////////  edit Products details /////////////////////////////

        Route::post('admin/AeditProducts', [Products::class, 'updateProducts']);


        Route::post('admin/AdeleteProducts/{id}', [Products::class, 'deleteProducts']);

        route::get('admin/salesSummary',[AdminController::class,'salesSummary'])->name('admin.dashboard');

});

Route::get('admin/sidebar',function(){
    return view('layouts.adminSidebar');
});

///////////////////////////////////  seller routes  ////////////////////////////////////////////////////////////////


Route::get('/seller/signup',function(){
    return view('seller.sellerSignup');
});

Route::post('/seller/sendOtp',[SellerC::class,'sendOTP']);

Route::get('seller/sellerMatchOTP',function(){
    return view('seller.sellerMatchOTP');
});

Route::post('/matchotp',[sellerC::class,'matchotp']);

Route::get('/seller/sellerDetails',function(){
    return view('seller.sellerDetails');
});



Route::get('/seller/sellerLogin',function(){
    return view('seller/sellerLogin');
});

Route::post('/seller/login',[SellerC::class,'sellerLogin']);

Route::get('/auth/googlesignup', [AuthController::class, 'signupredirect']);
Route::get('/auth/googlesignup/callback', [AuthController::class, 'signupcallback']);

Route::get('seller/sellerDashboard', [sellerC::class, 'sellerDashboard']);

Route::get('/auth/googlelogin', [SellerC::class, 'Loginredirect']);
Route::get('/auth/googlelogin/callback', [SellerC::class, 'Logincallback']);


Route::get('/seller/sellerDetails',function(){
    return view('seller.sellerDetails');
});

Route::post('/seller/sellerSubmitDetails',[SellerC::class,'sellerDetails']);
Route::post('/seller/logout',[SellerC::class,'sellerLogout']);


Route::middleware(['auth',] )->group(function () {

    Route::get('seller/products/',[SellerC::class,'sellerProducts']);
    Route::get('seller/orders/',[sellerC::class,'sellerorderedProducts']);


    Route::post('/seller/orders/updateStatus/{id}',[sellerC::class,'updateStatus']);


    Route::post('seller/sellerEditProducts',[Products::class,'editProducts']);
    Route::post('seller/sellerDeleteProduct',[Products::class,'deleteProducts']);

    Route::get('seller/review/',function(){
        return view('seller.sellerReview');
    });

    Route::get('seller/payment/',function(){
        return view('seller.sellerPayment');
    });

    Route::get('seller/selleraddProduct/',function(){
        return view('seller.sellerAddproduct');
    });

    Route::post('/seller/addproduct',[Products::class,'addProducts']);

    // route::get('seller/dashboard/',function(){
    //     return view('seller/sellerHome');
    // });
    

});


    ////////////////////////////////////////////////////////////////////////////////// staff////////////////////

    Route::get('staff/staffSignup',function(){
        return view('staff.staffSignup');
    });
    Route::post('/staff/sendOtp',[StaffC::class,'sendotp']);

    Route::get('staff/SignupDetails',function(){
        return view('staff.SignupDetails');
    });
    Route::post('staff/submitSignupDetails',[StaffC::class,'submitSignupDetails']);
    
    Route::get('staff/Dashboard',[StaffC::class,'staffDashboard']);
    Route::get('staff/orders',[StaffC::class,'staffOrders']);



    // Route::post('/orders/updateStatus/{id}', [SellerC::class, 'updateStatus']);

    Route::post('/staff/assign-order',[StaffC::class,'assignOrder']);
    Route::post('/staff/deliverOrder',[StaffC::class,'deliverOrder']);