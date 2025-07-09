<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\home_controller;
use App\Http\Controllers\Vendor\register_controller;
use App\Http\Controllers\Vendor\login_controller;
use App\Http\Controllers\Vendor\verify_controller;
use App\Http\Controllers\Vendor\product_controller;
use App\Http\Controllers\Vendor\property_controller;
use App\Http\Controllers\Vendor\charges_controller;
use App\Http\Controllers\Vendor\setting_controller;
use App\Http\Controllers\Vendor\order_controller;

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
Route::GET("/register/vendor",[register_controller::class,"index"]);
Route::POST("/Vendor/Insert",[register_controller::class,"Insert"]);

// Login
Route::GET("/login/vendor",[login_controller::class,"index"]);
Route::POST("/Vendor/Login",[login_controller::class,"Check"]);

// Logout
Route::GET("/logout/vendor",[login_controller::class,"logout"]);

// Otp Verification
Route::GET("/vendor/email/verify/{CODE}",[verify_controller::class,"index"]);
Route::POST("/Verify/Vendor/Email",[verify_controller::class,"Verify_Email"]);
// Resend
Route::POST("/Vendor/Resend/Otp",[verify_controller::class,"Resend"]);

// Dashboard
Route::GET("/dashboard/vendor/home",[home_controller::class,"index"]);

// Product
// create
Route::GET("/dashboard/vendor/product",[product_controller::class,"Index"]);
Route::GET("/Vendor/Product/Category/Get",[product_controller::class,"Get_Category"]);
Route::GET("/Vendor/Product/Brand/Get",[product_controller::class,"Get_Brand"]);
Route::POST("/Vendor/Product/Insert",[product_controller::class,"Insert"]);
// Listing
Route::GET("/dashboard/vendor/product/listing",[product_controller::class,"Listing"]);
Route::POST("/Vendor/Product/Get",[product_controller::class,"Get"]);
// Edit
Route::GET("/dashboard/vendor/product/{CODE}/edit",[product_controller::class,"Edit"]);
Route::POST("/Vendor/Product/Update",[product_controller::class,"Update"]);
// Status
Route::POST("/Vendor/Product/Status",[product_controller::class,"Status"]);
// Images
Route::GET("/dashboard/vendor/product/{code}/image",[product_controller::class,"Image_Index"]);
Route::POST("/Vendor/Product/Upload/Images",[product_controller::class,"Upload_Image"]);
Route::POST("/Vendor/Product/Get/Images",[product_controller::class,"Get_Image"]);
Route::POST("/Vendor/Product/Delete/Images",[product_controller::class,"Delete_Image"]);
// Color
Route::GET("/dashboard/vendor/product/{code}/color",[product_controller::class,"Color_Index"]);
Route::GET("/Vendor/Product/{CODE}/Color",[product_controller::class,"Get_Color"]);
Route::POST("/Vendor/Product/Insert/Color",[product_controller::class,"Insert_Color"]);
Route::POST("/Vendor/Product/Delete/Color",[product_controller::class,"Delete_Color"]);
Route::POST("/Vendor/Product/Status/Color",[product_controller::class,"Status_Color"]);
// Size
Route::GET("/dashboard/vendor/product/{code}/size",[product_controller::class,"Size_Index"]);
Route::POST("/Vendor/Product/Insert/Size",[product_controller::class,"Insert_Size"]);
Route::GET("/Vendor/Product/{CODE}/Size",[product_controller::class,"Get_Size"]);
Route::POST("/Vendor/Product/Delete/Size",[product_controller::class,"Delete_Size"]);
Route::POST("/Vendor/Product/Status/Size",[product_controller::class,"Status_Size"]);
Route::POST("/Vendor/Product/Update/Size",[product_controller::class,"Update_Size"]);
// Discount
Route::POST("/Vendor/Product/Discount",[product_controller::class,"Discount"]);
// Delete
Route::POST("/Vendor/Product/Delete",[product_controller::class,"Delete"]);
// Review
Route::GET("/dashboard/vendor/product/{code}/review",[product_controller::class,"Review"]);
Route::GET("/Vendor/Product/{CODE}/Review",[product_controller::class,"Get_Review"]);
// Route::POST("/Product/Delete/Review",[product_controller::class,"Delete_Review"]);
// Route::POST("/Product/Status/Review",[product_controller::class,"Status_Review"]);
// Note
Route::GET("/dashboard/vendor/product/{code}/note",[product_controller::class,"Note"]);
Route::GET("/Vendor/Product/{CODE}/Note",[product_controller::class,"Get_Note"]);
Route::POST("/Vendor/Product/Insert/Note",[product_controller::class,"Insert_Note"]);
// Activity
Route::GET("/dashboard/vendor/product/{code}/activity",[product_controller::class,"Activity"]);



// Property
// create
Route::GET("/dashboard/vendor/property",[property_controller::class,"Index"]);
Route::POST("/Vendor/Property/Insert",[property_controller::class,"Insert"]);
// Listing
Route::GET("/dashboard/vendor/property/listing",[property_controller::class,"Listing"]);
Route::POST("/Vendor/Property/Get",[property_controller::class,"Get"]);
// Edit
Route::GET("/dashboard/vendor/property/{CODE}/edit",[property_controller::class,"Edit"]);
Route::POST("/Vendor/Property/Update",[property_controller::class,"Update"]);
// Status
Route::POST("/Vendor/Property/Status",[property_controller::class,"Status"]);
// Images
Route::GET("/dashboard/vendor/property/{code}/image",[property_controller::class,"Image_Index"]);
Route::POST("/Vendor/Property/Upload/Images",[property_controller::class,"Upload_Image"]);
Route::POST("/Vendor/Property/Get/Images",[property_controller::class,"Get_Image"]);
Route::POST("/Vendor/Property/Delete/Images",[property_controller::class,"Delete_Image"]);

// Map
Route::GET("/dashboard/vendor/property/{code}/map",[property_controller::class,"Map_Index"]);
Route::POST("/Vendor/Property/Upload/Map",[property_controller::class,"Upload_Map"]);
Route::POST("/Vendor/Property/Get/Map",[property_controller::class,"Get_Map"]);
Route::POST("/Vendor/Property/Delete/Map",[property_controller::class,"Delete_Map"]);

// Note
Route::GET("/dashboard/vendor/property/{code}/note",[property_controller::class,"Note"]);
Route::GET("/Vendor/Property/{CODE}/Note",[property_controller::class,"Get_Note"]);
Route::POST("/Vendor/Property/Insert/Note",[property_controller::class,"Insert_Note"]);
// Activity
Route::GET("/dashboard/vendor/property/{code}/activity",[property_controller::class,"Activity"]);
// Delete
Route::POST("/Vendor/Property/Delete",[property_controller::class,"Delete"]);

// Charges
Route::GET("/dashboard/vendor/charges",[charges_controller::class,"Index"]);
Route::POST("/Vendor/Charges/Save",[charges_controller::class,"Save"]);


// Setting
Route::GET("/dashboard/vendor/setting",[setting_controller::class,"Index"]);
Route::POST("/Vendor/Setting/Personal",[setting_controller::class,"Personal"]);
Route::POST("/Vendor/Setting/Bank",[setting_controller::class,"Bank"]);
Route::POST("/Vendor/Setting/Store",[setting_controller::class,"Store"]);
Route::POST("/Vendor/Setting/Attachments",[setting_controller::class,"Attachments"]);
Route::POST("/Vendor/Setting/Password",[setting_controller::class,"Password"]);


// Order
Route::GET("/dashboard/vendor/order",[order_controller::class,"Index"]);
Route::POST("/Vendor/Order/Get",[order_controller::class,"Get"]);
Route::GET("/dashboard/vendor/order/{code}/info",[order_controller::class,"Info"]);
Route::GET("/Vendor/Order/Info/{CODE}/Cart",[order_controller::class,"Cart"]);
Route::POST("/Vendor/Order/Confirm",[order_controller::class,"Confirm"]);
Route::POST("/Vendor/Order/Cancel",[order_controller::class,"Cancel"]);
Route::POST("/Vendor/Order/Dispatch",[order_controller::class,"Dispatch"]);
Route::GET("/dashboard/vendor/order/{code}/invoice",[order_controller::class,"Invoice"]);
