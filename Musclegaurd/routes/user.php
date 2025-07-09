<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\home_controller;
use App\Http\Controllers\User\register_controller;
use App\Http\Controllers\User\login_controller;
use App\Http\Controllers\User\verify_controller;
use App\Http\Controllers\User\wishlist_controller;
use App\Http\Controllers\User\setting_controller;
use App\Http\Controllers\User\order_controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Register
Route::GET("/register/user",[register_controller::class,"index"]);
Route::POST("/User/Register",[register_controller::class,"Insert"]);

// Login
Route::GET("/login/user",[login_controller::class,"index"]);
Route::POST("/User/Login",[login_controller::class,"Check"]);

// Logout
Route::GET("/logout/user",[login_controller::class,"logout"]);

// Otp Verification
Route::GET("/user/email/verify/{CODE}",[verify_controller::class,"index"]);
Route::POST("/Verify/User/Email",[verify_controller::class,"Verify_Email"]);
// Resend
Route::POST("/Resend/Otp",[verify_controller::class,"Resend"]);

// Dashboard
Route::GET("/dashboard/user/home",[home_controller::class,"index"]);

// Wishlist
Route::GET("/dashboard/user/wishlist",[wishlist_controller::class,"Index"]);
Route::POST("/User/Wishlist/Get",[wishlist_controller::class,"Get"]);
Route::POST("/User/Wishlist/Removed",[wishlist_controller::class,"Removed"]);

// Order
Route::GET("/dashboard/user/order",[order_controller::class,"Index"]);
Route::GET("/dashboard/user/order/{STATUS}",[order_controller::class,"Redirect"]);
Route::POST("/User/Order/Get",[order_controller::class,"Get"]);
Route::GET("/dashboard/user/order/{CODE}/info",[order_controller::class,"Info"]);
Route::GET("/User/Order/{CODE}/Cart",[order_controller::class,"Cart"]);

// Setting
Route::GET("/dashboard/user/setting",[setting_controller::class,"Index"]);
Route::POST("/User/Setting/Personal",[setting_controller::class,"Personal"]);
Route::POST("/User/Setting/Password",[setting_controller::class,"Password"]);

