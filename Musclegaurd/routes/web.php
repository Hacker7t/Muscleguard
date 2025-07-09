<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\common_controller;
use App\Http\Controllers\Website\home_controller;
use App\Http\Controllers\Website\shop_controller;
use App\Http\Controllers\Website\gym_controller;
use App\Http\Controllers\Website\contact_controller;
use App\Http\Controllers\Website\detail_controller;
use App\Http\Controllers\Website\cart_controller;
use App\Http\Controllers\Website\checkout_controller;
use App\Http\Controllers\Website\order_controller;
use App\Http\Controllers\Website\payment_controller;
use App\Http\Controllers\Website\wishlist_controller;
use App\Http\Controllers\Website\subscriber_controller;
use App\Http\Controllers\Website\blog_controller;
use App\Http\Controllers\Website\jazzcash_controller;

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


// Sitemap
Route::GET("/sitemap.xml",[home_controller::class,"sitemap"]);

// Home
Route::GET("/",[home_controller::class,"index"]);
Route::GET("/Get/Popular/Product",[home_controller::class,"Popular"]);

// About
Route::view("/about","website.about.index");
Route::view("/team","website.team.index");
Route::view("/privacy","website.policy.privacy");
Route::view("/term","website.policy.terms");
Route::view("/support","website.policy.support");

// Blog
Route::GET("/blog",[blog_controller::class,"Blog"]);
Route::GET("/blog-detail/{id}",[blog_controller::class,"Detail"]);

// Shop
Route::GET("/shop",[shop_controller::class,"index"]);
Route::POST("/Shop/Filter",[shop_controller::class,"Filter"]);

// Gym
Route::GET("/gym",[gym_controller::class,"index"]);
Route::GET("/gym/{code}",[gym_controller::class,"Detail"]);
Route::POST("/gym/search",[gym_controller::class,"Search"]);
Route::GET("/Get/Popular/Gym/Product/{code}",[gym_controller::class,"Product"]);

// Contact
Route::GET("/contact",[contact_controller::class,"Index"]);
Route::POST("/Contact/Insert",[contact_controller::class,"Insert"]);

// Cart
Route::GET("/cart",[cart_controller::class,"Index"]);
Route::GET("/Get/Cart",[cart_controller::class,"Get"]);
Route::POST("/Cart/Insert",[cart_controller::class,"Insert"]);
Route::POST("/Check/Coupon",[cart_controller::class,"Check"]);
Route::POST("/Cart/Quantity",[cart_controller::class,"Quantity"]);
Route::POST("/Cart/Delete",[cart_controller::class,"Delete"]);
Route::POST("/Cart/Shipment/Update",[cart_controller::class,"Shipment_Update"]);
Route::GET("/Cart/Shipment/Get",[cart_controller::class,"Shipment_Get"]);

// CheckOut
Route::POST("/Checkout",[checkout_controller::class,"Checkout"]);
Route::GET("/checkout",[checkout_controller::class,"index"]);


// PlaceOrder
Route::POST("/Placed/Order",[checkout_controller::class,"Placed"]);
Route::GET("/invoice/{code}",[checkout_controller::class,"Invoice"]);




// // Order
// Route::POST("/Order/Placed",[order_controller::class,"Insert"]);
// Route::POST("/Order/Confirm",[order_controller::class,"Confirm"]);
// Route::GET("/invoice/{ORDER_CODE}",[order_controller::class,"Invoice"]);

// Payment
Route::GET("/payment/{ORDER_CODE}",[payment_controller::class,"Index"]);

Route::controller(payment_controller::class)->group(function(){
    Route::get('stripe', 'stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});
Route::GET("/paywithjazzcash/{ORDER_CODE}",[jazzcash_controller::class,"Pay"]);

// Wishlist
Route::GET("/wishlist",[wishlist_controller::class,"index"]);
Route::GET("/Wishlist/Get",[wishlist_controller::class,"Get"]);
Route::POST("/Wishlist/Insert",[wishlist_controller::class,"Insert"]);

// Detail
Route::GET("/{URL}",[detail_controller::class,"Detail"]);
Route::GET("/Get/Related/{URL}",[detail_controller::class,"Related"]);
Route::POST("/Insert/Rating",[detail_controller::class,"Insert_Rating"]);

// Subscriber
Route::POST("/Insert/Subscriber",[subscriber_controller::class,"Insert"]);


// Common
Route::GET("/Get/Category",[common_controller::class,"Category"]);
Route::GET("/Get/Sub_Category/{CATEGORY_ID}",[common_controller::class,"Sub_Category"]);
Route::GET("/Get/Country",[common_controller::class,"Country"]);
Route::GET("/Get/State/{COUNTRY_ID}",[common_controller::class,"State"]);
Route::GET("/Get/City/{STATE_ID}",[common_controller::class,"City"]);
Route::GET("/Get/Shipment/{CITY_ID}",[common_controller::class,"Shipment"]);
Route::GET("/Get/Announcement",[common_controller::class,"Announcement"]);

