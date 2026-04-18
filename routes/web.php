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

Route::get('/', function () {
    return view('users/Uhome');
});

Route::get('Uhome', function () {
    return view('users.Uhome');
});

Route::get('Ufeatures', function () {
    return view('users.Ufeatures');
});

Route::get('Uabout', function () {
    return view('users.Uabout');
});

Route::get('Ucontact', function () {
    return view('users.Ucontact');
});

route::get('/Uproducts',[Products::class,'viewProducts']);

/////////////////////////////////////////////////////////////////// users signup and login routes//////

Route::get('users/Usignup', function () {
    return view('users.Usignup');
});
route::post('users/Usignup',[Users::class,'gmail'])->name('Usignup');

Route::get('/users/Ulogin', function () {
    return view('users.Ulogin');
});
route::post('users/Ulogin', [Users::class,'login'])->name('login');

Route::middleware(['auth',])->group(function () {

        ///////////// users payments route ////////////////////////

        route::post('users/UplaceOrder',[OrderC::class,'placeOrder']);

        ////////////////////////////////////////////////////////////////
        //logout route
        route::post('/users/Ulogout',[Users::class,'logout']);

        ////users details
        route::get('/users/Udetails',[Users::class,'user_details']);
        route::post('/users/Udetails',[Users::class,'update_details']);

        //edit details
        route::get('/users/UeditDetails',[Users::class,'edit_details']);


        /////////////////////////////////////////////////////////////////

        Route::get('/forgot_password', function () {
            return view('users/UforgotP');
        });

        Route::get('/csrf-token', function () {
            return response()->json(['token' => csrf_token()]);
        });

        route::get('users/Ucart',[CartC::class,'cart']);                            //go to cart
        route::post('/Ucart',[CartC::class,'addToCart'])->name('cart.add');         // add to cart
        route::post('users/removeProductsFromCart',[CartC::class,'removeFromCart']);  // remove from cart

        route::post('users/Ucheckout',[Products::class,'paymentDetails']);  // checkout route
        route::post('/users/Ubuyproduct',[OrderC::class,'addressDetails']);  // buy product route
        route::post('/users/returnOrder',[OrderC::class,'returnOrder']);  // return order route
        route::post('users/cancelOrder',[OrderC::class,'cancelOrder']);  // cancel order route
        route::post('users/UreturnOrderReason',[OrderC::class,'returnOrderReason']);  // return reason order route
        route::post('orders/returnReason',[OrderC::class,'submitreturnReason']);  // return reason order route
        route::get("users/Uview_Orders",[OrderC::class,'userOrders']);  // user view orders route
        route::get('users/Uproduct_details/{id}',[Products::class,'buyProduct']); // product details route
        route:: post ('users/UsingleProduct',[UserReviewC::class,'singleProductPage']);  // single product page route
        route:: post ('users/review/',[UserReviewC::class,'addReview'])->name('addReview');  // add review route
        route::get('users/help',function(){
            return view('users.help');
        });
        route::post('users/search',[Users::class,'searchproduct']);
});

        ////////////////////////////////   admin routes  ///////////////////////////////////////////////////////

route:: get('admin/adminLogin',function(){
    return view('admin/adminLogin');
});
route ::post('admin/adminLogin',[AdminController::class,'adminLogin']);


route:: get('admin/adminSignup',function(){
    return view('admin/adminSignup');
});
route:: post('admin/adminSignup',[AdminController::class,'adminSignup']);

Route::middleware(['auth',])->group(function () {

        route::get("admin/Aaddproducts",function(){
            return view('admin.Aaddproducts');
        });


        route::get('admin/adminDashboard',[AdminController::class,'adminPanel']);

        route::get('admin/Amanageorders',[OrderC::class,'viewOrders']); // add Products
        route::post("admin/deleteOrder",[AdminController::class,'deleteOrder']); // delete order
        route::post("admin/orderStatus",[AdminController::class,'updateOrderStatus']); // update order status
        Route::POST("Aaddproducts",[Products::class,'addProducts']);// admin view Products
        Route::get('admin/Aproducts', [Products::class, 'index']);
        Route::POST('admin/Aproducts/{id}/toggle', [Products::class, 'toggleStatus']);

        route::get('admin/AdminUserManagement',[AdminController::class,'userManagement']);
        route::post('admin/updateUserStatus',[AdminController::class,'updateUserStatus']);
        route::post('admin/deleteUser',[AdminController::class,'deleteUser']);
        route::post('admin/profile',[AdminController::class,'updateAdminProfile']);
        route::post('admin/logout',[AdminController::class,'adminLogout']);



        /////////////  edit Products details /////////////////////////////

        // Route::get('admin/AeditProducts/{id}', [Products::class, 'editProducts']);  //   removed because not needed admin edit product
        // Route::post('admin/AeditProducts/{id}', [Products::class, 'updateProducts']);


        Route::post('admin/AdeleteProducts/{id}', [Products::class, 'deleteProducts']);

        route::get('admin/salesSummary',[AdminController::class,'salesSummary'])->name('admin.dashboard');

});

///////////////////////////////////  seller routes  ////////////////////////////////////////////////////////////////


route::get('/seller/signup',function(){
    return view('seller.sellersignup');
});

route::post('/seller/sendOtp',[SellerC::class,'sendOTP']);

route::get('seller/sellerMatchOTP',function(){
    return view('seller.sellerMatchOTP');
});

route::post('/matchotp',[sellerC::class,'matchotp']);

route::get('/seller/sellerDetails',function(){
    return view('seller.sellerDetails');
});



route::get('/seller/sellerLogin',function(){
    return view('seller/sellerLogin');
});

route::post('/seller/login',[SellerC::class,'sellerLogin']);

Route::get('/auth/googlesignup', [AuthController::class, 'signupredirect']);
Route::get('/auth/googlesignup/callback', [AuthController::class, 'signupcallback']);

Route::get('seller/sellerDashboard', [sellerC::class, 'sellerDashboard']);

Route::get('/auth/googlelogin', [SellerC::class, 'Loginredirect']);
Route::get('/auth/googlelogin/callback', [SellerC::class, 'Logincallback']);


route::get('/seller/sellerDetails',function(){
    return view('seller.sellerDetails');
});

route::post('/seller/sellerSubmitDetails',[SellerC::class,'sellerDetails']);
route::post('/seller/logout',[SellerC::class,'sellerLogout']);


Route::middleware(['auth',] )->group(function () {

    route::get('seller/products/',[SellerC::class,'sellerProducts']);
 
    route::get('seller/orders/',[sellerC::class,'sellerorderedProducts']);

    route::get('seller/sellerEditProduct/{id}',[Products::class,'editProducts']);
    route::get('seller/sellerDeleteProduct/{id}',[Products::class,'deleteProducts']);

    route::get('seller/review/',function(){
        return view('seller.sellerReview');
    });

    route::get('seller/payment/',function(){
        return view('seller.sellerPayment');
    });

    route::get('seller/selleraddProduct/',function(){
        return view('seller.sellerAddproduct');
    });

    route::post('/seller/addproduct',[Products::class,'addProducts']);

    // route::get('seller/dashboard/',function(){
    //     return view('seller/sellerHome');
    // });
    

});


    ////////////////////////////////////////////////////////////////////////////////// staff////////////////////

    route::get('staff/staffSignup',function(){
        return view('staff.staffSignup');
    });
    route::post('/staff/sendOtp',[StaffC::class,'sendotp']);

    route::get('staff/SignupDetails',function(){
        return view('staff.SignupDetails');
    });
    route::post('staff/submitSignupDetails',[StaffC::class,'submitSignupDetails']);
    
    route::get('staff/Dashboard',[StaffC::class,'staffDashboard']);
    route::get('staff/orders',[StaffC::class,'staffOrders']);



    Route::post('/orders/updateStatus/{id}', [SellerC::class, 'updateStatus']);