<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartC;
use App\Http\Controllers\OrderC; 
use App\Http\Controllers\Products;
use App\Http\Controllers\Productimgs;
use App\Http\Controllers\SellerC;
use App\Http\Controllers\StaffC;
use App\Http\Controllers\UserReviewC;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;

// ============================== PUBLIC ROUTES ==============================

Route::get('/', [Products::class, 'viewProducts']);
Route::get('/Uproducts', [Products::class, 'viewProducts']);

Route::get('Ufeatures', function () {
    return view('users.Ufeatures');
});

Route::get('Uabout', function () {
    return view('users.Uabout');
});

Route::get('Ucontact', function () {
    return view('users.Ucontact');
});

// ============================== USER AUTHENTICATION ==============================

Route::get('users/Usignup', function () {
    return view('users.Usignup');
});
Route::post('users/Usignup', [Users::class, 'gmail'])->name('Usignup');

Route::get('/users/Ulogin', function () {
    return view('users.Ulogin');
});
Route::post('users/Ulogin', [Users::class, 'login'])->name('login');

// ============================== SELLER AUTHENTICATION ==============================

Route::get('/seller/signup', function () {
    return view('seller.sellerSignup');
});
Route::post('/seller/sendOtp', [SellerC::class, 'sendOTP']);

Route::get('seller/sellerMatchOTP', function () {
    return view('seller.sellerMatchOTP');
});
Route::post('/matchotp', [SellerC::class, 'matchotp']);

Route::get('/seller/sellerDetails', function () {
    return view('seller.sellerDetails');
});

Route::get('/seller/sellerLogin', function () {
    return view('seller/sellerLogin');
});
Route::post('/seller/login', [SellerC::class, 'sellerLogin']);

// Google OAuth
Route::get('/auth/googlesignup', [AuthController::class, 'signupredirect']);
Route::get('/auth/googlesignup/callback', [AuthController::class, 'signupcallback']);

Route::get('/auth/googlelogin', [SellerC::class, 'Loginredirect']);
Route::get('/auth/googlelogin/callback', [SellerC::class, 'Logincallback']);

Route::post('/seller/sellerSubmitDetails', [SellerC::class, 'sellerDetails']);

// ============================== ADMIN AUTHENTICATION ==============================

Route::get('admin/adminLogin', function () {
    return view('admin/adminLogin');
});
Route::post('admin/adminLogin', [AdminController::class, 'adminLogin']);

Route::get('admin/adminSignup', function () {
    return view('admin/adminSignup');
});
Route::post('admin/adminSignup', [AdminController::class, 'adminSignup']);

Route::get('admin/sidebar', function () {
    return view('layouts.adminSidebar');
});

// ============================== AUTHENTICATED USER ROUTES ==============================

Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/users/Ulogout', [Users::class, 'logout']);
    
    // User profile
    Route::get('/users/Udetails', [Users::class, 'user_details']);
    Route::post('/users/Udetails', [Users::class, 'update_details']);
    Route::get('/users/UeditDetails', [Users::class, 'edit_details']);
    
    // Shopping
    Route::get('users/Ucart', [CartC::class, 'cart']);
    Route::post('/Ucart', [CartC::class, 'addToCart'])->name('cart.add');
    Route::post('users/removeProductsFromCart', [CartC::class, 'removeFromCart']);
    
    // Checkout and Orders
    Route::post('users/Ucheckout', [Products::class, 'productcheckout']);
    Route::post('/users/Ubuyproduct', [OrderC::class, 'addressDetails']);
    Route::post('/users/returnOrder', [OrderC::class, 'returnOrder']);
    Route::post('users/cancelOrder', [OrderC::class, 'cancelOrder']);
    Route::post('users/UreturnOrderReason', [OrderC::class, 'returnOrderReason']);
    Route::post('orders/returnReason', [OrderC::class, 'submitreturnReason']);
    Route::get('users/Uview_Orders', [OrderC::class, 'userOrders']);
    Route::get('users/CODPayment', [OrderC::class, 'showOrderSummary']);
    Route::post('users/CodPayment/', [OrderC::class, 'placeOrder']);
    
    // Product browsing
    Route::get('users/Uproduct_details/{id}', [Products::class, 'buyProduct']);
    Route::get('users/UsingleProduct/', [UserReviewC::class, 'singleProductPage']);
    Route::post('users/review/', [UserReviewC::class, 'addReview'])->name('addReview');
    
    // Help
    Route::get('users/help', function () {
        return view('users.help');
    });
    Route::post('users/search', [Users::class, 'searchproduct']);
    
    Route::get('/forgot_password', function () {
        return view('users/UforgotP');
    });
    Route::get('/csrf-token', function () {
        return response()->json(['token' => csrf_token()]);
    });
});

// ============================== AUTHENTICATED SELLER ROUTES ==============================

Route::middleware(['auth'])->group(function () {
    
    Route::get('seller/sellerDashboard', [SellerC::class, 'sellerDashboard']);
    Route::get('seller/products/', [SellerC::class, 'sellerProducts']);
    Route::get('seller/orders/', [SellerC::class, 'sellerorderedProducts']);
    Route::post('/seller/orders/updateStatus/{id}', [SellerC::class, 'updateStatus']);
    
    Route::post('seller/sellerEditProducts', [Products::class, 'editProducts']);
    Route::post('seller/sellerDeleteProduct', [Products::class, 'deleteProducts']);
    
    Route::get('seller/review/', function () {
        return view('seller.sellerReview');
    });
    
    Route::get('seller/payment/', function () {
        return view('seller.sellerPayment');
    });
    
    Route::get('seller/selleraddProduct/', function () {
        return view('seller.sellerAddproduct');
    });
    
    Route::post('/seller/addproduct', [Products::class, 'addProducts']);
    Route::post('/seller/logout', [SellerC::class, 'sellerLogout']);
    
    // Product images (seller)
    Route::post('/seller/product-images/add', [Productimgs::class, 'addProductImage']);
    Route::delete('/seller/product-images/{id}', [Productimgs::class, 'deleteProductImage']);
});

// ============================== AUTHENTICATED STAFF ROUTES ==============================

Route::get('staff/staffSignup', function () {
    return view('staff.staffSignup');
});
Route::post('/staff/sendOtp', [StaffC::class, 'sendotp']);

Route::get('staff/SignupDetails', function () {
    return view('staff.SignupDetails');
});
Route::post('staff/submitSignupDetails', [StaffC::class, 'submitSignupDetails']);

Route::middleware(['auth'])->group(function () {
    Route::get('staff/Dashboard', [StaffC::class, 'staffDashboard']);
    Route::get('staff/orders', [StaffC::class, 'staffOrders']);
    Route::post('/staff/assign-order', [StaffC::class, 'assignOrder']);
    Route::post('/staff/deliverOrder', [StaffC::class, 'deliverOrder']);
});

// ============================== AUTHENTICATED ADMIN ROUTES ==============================

Route::middleware(['auth'])->group(function () {
    
    Route::get('admin/adminDashboard', [AdminController::class, 'adminPanel']);
    
    Route::get('admin/Amanageorders', [OrderC::class, 'viewOrders']);
    Route::post('/admin/orders/{orderId}/update-status', [AdminController::class, 'updateOrderStatus']);
    Route::post('/admin/deleteOrder/', [AdminController::class, 'deleteOrder']);
    
    Route::get('admin/Aaddproducts', function () {
        return view('admin.Aaddproducts');
    });
    Route::post('Aaddproducts', [Products::class, 'addProducts']);
    
    Route::get('admin/Aproducts', [Products::class, 'index']);
    Route::post('admin/Aproducts/{id}/toggle', [Products::class, 'toggleStatus']);
    Route::post('admin/AeditProducts', [Products::class, 'updateProducts']);
    Route::post('admin/AdeleteProducts/{id}', [Products::class, 'deleteProducts']);
    
    Route::get('admin/AdminUserManagement', [AdminController::class, 'userManagement']);
    Route::post('/admin/users/{userId}/toggle-status', [AdminController::class, 'updateUserStatus']);
    Route::post('admin/deleteUser', [AdminController::class, 'deleteUser']);
    
    Route::post('admin/profile', [AdminController::class, 'updateAdminProfile']);
    Route::post('admin/logout', [AdminController::class, 'adminLogout']);
    
    Route::get('admin/salesSummary', [AdminController::class, 'salesSummary'])->name('admin.dashboard');
    
    // Product images (admin)
    Route::post('/admin/product-images/add', [Productimgs::class, 'addProductImage']);
    Route::delete('/admin/product-images/{id}', [Productimgs::class, 'deleteProductImage']);
});
