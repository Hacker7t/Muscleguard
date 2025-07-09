<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\login_controller;
use App\Http\Controllers\Dashboard\home_controller;
use App\Http\Controllers\Dashboard\account_controller;
use App\Http\Controllers\Dashboard\profile_controller;
use App\Http\Controllers\Dashboard\forgot_controller;
use App\Http\Controllers\Dashboard\category_controller;
use App\Http\Controllers\Dashboard\brand_controller;
use App\Http\Controllers\Dashboard\coupon_controller;
use App\Http\Controllers\Dashboard\product_controller;
use App\Http\Controllers\Dashboard\property_controller;
use App\Http\Controllers\Dashboard\country_controller;
use App\Http\Controllers\Dashboard\state_controller;
use App\Http\Controllers\Dashboard\city_controller;
use App\Http\Controllers\Dashboard\banner_controller;
use App\Http\Controllers\Dashboard\order_controller;
use App\Http\Controllers\Dashboard\contact_controller;
use App\Http\Controllers\Dashboard\image_controller;
use App\Http\Controllers\Dashboard\blog_controller;
use App\Http\Controllers\Dashboard\subscriber_controller;
use App\Http\Controllers\Dashboard\user_controller;
use App\Http\Controllers\Dashboard\vendor_controller;
use App\Http\Controllers\Dashboard\wanted_controller;
use App\Http\Controllers\Dashboard\professional_controller;


/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Dashboard routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Dashboard" middleware group. Now create something great!
|
*/

// Check Login
Route::GET("/Check/Session",[login_controller::class,"Check_Session"]);


Route::GET("/dashboard/login",[login_controller::class,"login_page"]);
Route::POST("/dashboard/login",[login_controller::class,"login_check"]);
Route::GET("/otp/verification/{code}",[login_controller::class,"otp_verification_page"]);
Route::POST("/resend-otp/",[login_controller::class,"resend_otp"]);
Route::POST("/otp-verification/",[login_controller::class,"otp_verification"]);
Route::GET("/dashboard/logout",[login_controller::class,"logout"]);

// Home
Route::GET("/dashboard/home",[home_controller::class,"index"]);
Route::GET("/Home/Get/Order",[home_controller::class,"Get_Order"]);

// Account
Route::GET("/dashboard/account",[account_controller::class,"index"]);
Route::POST("/Account/Get",[account_controller::class,"Get"]);
Route::POST("/Account/Insert",[account_controller::class,"Insert"]);
Route::POST("/Account/Status",[account_controller::class,"Status"]);
Route::POST("/Account/Delete",[account_controller::class,"Delete"]);
Route::GET("/dashboard/account/{code}/edit",[account_controller::class,"Edit"]);
Route::POST("/Account/Update",[account_controller::class,"Update"]);

// Profile
Route::GET("/dashboard/profile",[profile_controller::class,"index"]);
Route::POST("/Profile/Update/Name",[profile_controller::class,"Name"]);
Route::POST("/Profile/Update/Email",[profile_controller::class,"Email"]);
Route::POST("/Profile/Update/Password",[profile_controller::class,"Password"]);

// Forgot
Route::GET("/dashboard/forgot",[forgot_controller::class,"index"]);
Route::POST("/dashboard/Find",[forgot_controller::class,"Find"]);
Route::GET("/dashboard/email/verification/{code}",[forgot_controller::class,"Verification"]);
Route::POST("/dashboard/verified",[forgot_controller::class,"Verified"]);
Route::GET("/dashboard/change/password/{code}",[forgot_controller::class,"Change"]);
Route::POST("/dashboard/change/password",[forgot_controller::class,"Change_Password"]);


// Category
Route::GET("/dashboard/category",[category_controller::class,"index"]);
Route::GET("/Category/Get/Parent",[category_controller::class,"Get_Parent"]);
Route::POST("/Category/Get",[category_controller::class,"Get"]);
Route::POST("/Category/Insert",[category_controller::class,"Insert"]);
Route::POST("/Category/Delete",[category_controller::class,"Delete"]);
Route::GET("/dashboard/category/{code}/edit",[category_controller::class,"Edit"]);
Route::POST("/Category/Update",[category_controller::class,"Update"]);
Route::POST("/Category/Status",[category_controller::class,"Status"]);
Route::GET("/dashboard/category/{code}/subcategory",[category_controller::class,"Subcategory"]);
Route::POST("/Category/Commission",[category_controller::class,"Commission"]);


// Brand
Route::GET("/dashboard/brand",[brand_controller::class,"index"]);
Route::GET("/Brand/Get/Category",[brand_controller::class,"Category"]);
Route::POST("/Brand/Get",[brand_controller::class,"Get"]);
Route::POST("/Brand/Insert",[brand_controller::class,"Insert"]);
Route::POST("/Brand/Delete",[brand_controller::class,"Delete"]);
Route::GET("/dashboard/brand/{code}/edit",[brand_controller::class,"Edit"]);
Route::POST("/Brand/Update",[brand_controller::class,"Update"]);
Route::POST("/Brand/Status",[brand_controller::class,"Status"]);

// Coupon
Route::GET("/dashboard/coupon",[coupon_controller::class,"index"]);
Route::POST("/Coupon/Insert",[coupon_controller::class,"Insert"]);
Route::POST("/Coupon/Get",[coupon_controller::class,"GET"]);
Route::POST("/Coupon/Delete",[coupon_controller::class,"Delete"]);
Route::POST("/Coupon/Generate",[coupon_controller::class,"Generate"]);
Route::POST("/Coupon/Status",[coupon_controller::class,"Status"]);
Route::POST("/Coupon/Renew",[coupon_controller::class,"Renew"]);

// Products
Route::GET("/dashboard/product",[product_controller::class,"index"])->name('admin_product');
Route::GET("/dashboard/product/{filter}",[product_controller::class,"Filter"]);
Route::POST("/Product/Insert",[product_controller::class,"Insert"]);
Route::POST("/Product/Get",[product_controller::class,"Get"]);
Route::GET("/Product/Get/Category",[product_controller::class,"Get_Category"]);
Route::GET("/Product/Get/SubCategory/{PARENT_ID}",[product_controller::class,"Get_Sub_Category"]);
Route::POST("/Product/Delete",[product_controller::class,"Delete"]);
Route::POST("/Product/Featured",[product_controller::class,"Featured"]);
Route::POST("/Product/Approval",[product_controller::class,"Approval"]);
Route::POST("/Product/Discount",[product_controller::class,"Discount"]);
Route::POST("/Product/Banned",[product_controller::class,"Banned"]);
Route::GET("/dashboard/product/{code}/edit",[product_controller::class,"Edit"]);
Route::POST("/Product/Update",[product_controller::class,"Update"]);
Route::GET("/dashboard/product/delete/{product_id}",[product_controller::class,"Custom_delete"]);
// Image
Route::GET("/dashboard/product/{code}/images",[product_controller::class,"Product_Images"]);
Route::POST("/Product/Upload/Images",[product_controller::class,"Product_Upload_Images"]);
Route::POST("/Product/Get/Images",[product_controller::class,"Product_Get_Images"]);
Route::POST("/Product/Delete/Images",[product_controller::class,"Product_Delete_Images"]);
Route::POST("/Product/Images/Holding",[product_controller::class,"Product_Images_Holding"]);
// Color
Route::GET("/dashboard/product/{code}/color",[product_controller::class,"Product_Color"]);
Route::GET("/Product/{CODE}/Color",[product_controller::class,"Get_Color"]);
Route::POST("/Product/Insert/Color",[product_controller::class,"Insert_Color"]);
Route::POST("/Product/Delete/Color",[product_controller::class,"Delete_Color"]);
Route::POST("/Product/Status/Color",[product_controller::class,"Status_Color"]);
// Size
Route::GET("/dashboard/product/{code}/size",[product_controller::class,"Product_Size"]);
Route::POST("/Product/Insert/Size",[product_controller::class,"Insert_Size"]);
Route::GET("/Product/{CODE}/Size",[product_controller::class,"Get_Size"]);
Route::POST("/Product/Delete/Size",[product_controller::class,"Delete_Size"]);
Route::POST("/Product/Status/Size",[product_controller::class,"Status_Size"]);
Route::POST("/Product/Update/Size",[product_controller::class,"Update_Size"]);
// Review
Route::GET("/dashboard/product/{code}/review",[product_controller::class,"Product_Review"]);
Route::GET("/Product/{CODE}/Review",[product_controller::class,"Get_Review"]);
Route::POST("/Product/Delete/Review",[product_controller::class,"Delete_Review"]);
Route::POST("/Product/Status/Review",[product_controller::class,"Status_Review"]);
// Note
Route::GET("/dashboard/product/{code}/note",[product_controller::class,"Product_Note"]);
Route::POST("/Product/Insert/Note",[product_controller::class,"Insert_Note"]);
Route::GET("/Product/{code}/Get/Note",[product_controller::class,"Get_Note"]);

// Properties
Route::GET("/dashboard/property",[property_controller::class,"index"]);
Route::GET("/dashboard/property/{FILTER}",[property_controller::class,"Filter"]);
Route::POST("/Property/Get",[property_controller::class,"Get"]);
// Approval
Route::POST("/Property/Approval",[property_controller::class,"Approval"]);
// Edit
Route::GET("/dashboard/property/{code}/edit",[property_controller::class,"Edit"]);
Route::POST("/Property/Update",[property_controller::class,"Update"]);
// Image
Route::GET("/dashboard/property/{code}/images",[property_controller::class,"Property_Images"]);
Route::POST("/Property/Upload/Images",[property_controller::class,"Property_Upload_Images"]);
Route::POST("/Property/Get/Images",[property_controller::class,"Property_Get_Images"]);
Route::POST("/Property/Delete/Images",[property_controller::class,"Property_Delete_Images"]);
// Banned
Route::POST("/Property/Banned",[property_controller::class,"Banned"]);
Route::POST("/Property/Featured",[property_controller::class,"Featured"]);
// Note
Route::GET("/dashboard/property/{code}/note",[property_controller::class,"Property_Note"]);
Route::POST("/Property/Insert/Note",[property_controller::class,"Insert_Note"]);
Route::GET("/Property/{code}/Get/Note",[property_controller::class,"Get_Note"]);
// Delete
Route::POST("/Property/Delete",[property_controller::class,"Delete"]);


// Properties
Route::GET("/dashboard/professional",[professional_controller::class,"index"]);
Route::GET("/dashboard/professionals/{FILTER}",[professional_controller::class,"Filter"]);
Route::POST("/Professional/Get",[professional_controller::class,"Get"]);
// Info
Route::GET("/dashboard/professional/{code}/info",[professional_controller::class,"Info"]);
// Approval
Route::POST("/Professional/Info/Changes/Save",[professional_controller::class,"Changes"]);
// Banned
Route::POST("/Professional/Banned",[professional_controller::class,"Banned"]);
// Delete
Route::POST("/Professional/Delete",[professional_controller::class,"Delete"]);

// Project
Route::POST("/Project/Approval",[professional_controller::class,"Approval"]);



// Country
Route::GET("/dashboard/country",[country_controller::class,"index"]);
Route::POST("/Country/Get",[country_controller::class,"Get"]);
Route::POST("/Country/Insert",[country_controller::class,"Insert"]);
Route::POST("/Country/Delete",[country_controller::class,"Delete"]);
Route::GET("/dashboard/country/{code}/edit",[country_controller::class,"Edit"]);
Route::POST("/Country/Update",[country_controller::class,"Update"]);
Route::POST("/Country/Status",[country_controller::class,"Status"]);

// State
Route::GET("/dashboard/state",[state_controller::class,"index"]);
Route::GET("/State/Get/Country",[state_controller::class,"Get_Country"]);
Route::POST("/State/Get",[state_controller::class,"Get"]);
Route::POST("/State/Insert",[state_controller::class,"Insert"]);
Route::POST("/State/Delete",[state_controller::class,"Delete"]);
Route::GET("/dashboard/state/{code}/edit",[state_controller::class,"Edit"]);
Route::POST("/State/Update",[state_controller::class,"Update"]);
Route::POST("/State/Status",[state_controller::class,"Status"]);

// City
Route::GET("/dashboard/city",[city_controller::class,"index"]);
Route::GET("/City/Get/Country",[city_controller::class,"Get_Country"]);
Route::GET("/City/Get/State/{COUNTRY_ID}",[city_controller::class,"Get_State"]);
Route::POST("/City/Get",[city_controller::class,"Get"]);
Route::POST("/City/Insert",[city_controller::class,"Insert"]);
Route::POST("/City/Delete",[city_controller::class,"Delete"]);
Route::GET("/dashboard/city/{code}/edit",[city_controller::class,"Edit"]);
Route::POST("/City/Update",[city_controller::class,"Update"]);
Route::POST("/City/Status",[city_controller::class,"Status"]);

// Banner
Route::GET("/dashboard/banner",[banner_controller::class,"index"]);
// Route::GET("/Banner/Get",[banner_controller::class,"Get"]);
Route::POST("/Banner/Insert",[banner_controller::class,"Insert"]);
// Route::POST("/Banner/Status",[banner_controller::class,"Status"]);
// Route::POST("/Banner/Delete",[banner_controller::class,"Delete"]);

// Mobile Banner
Route::GET("/dashboard/banner/mobile",[mobile_banner_controller::class,"index"]);
Route::GET("/Mobile/Banner/Get",[mobile_banner_controller::class,"Get"]);
Route::POST("/Mobile/Banner/Insert",[mobile_banner_controller::class,"Insert"]);
Route::POST("/Mobile/Banner/Active",[mobile_banner_controller::class,"Active"]);
Route::POST("/Mobile/Banner/Delete",[mobile_banner_controller::class,"Delete"]);

// Order
Route::GET("/dashboard/order",[order_controller::class,"index"]);
Route::POST("/Order/Get",[order_controller::class,"Get"]);
Route::POST("/Order/Status",[order_controller::class,"Status"]);
Route::GET("/dashboard/order/{CODE}/info",[order_controller::class,"Info"]);
Route::GET("/Order/{code}/Cart",[order_controller::class,"Get_Cart"]);
Route::GET("/dashboard/order/{CODE}/invoice",[order_controller::class,"Invoice"]);

// Customize
Route::GET("/dashboard/customize",[customize_controller::class,"index"]);
Route::POST("/Customize/Get",[customize_controller::class,"Get"]);

// Contact
Route::GET("/dashboard/contact",[contact_controller::class,"index"]);
Route::POST("/Contact/Get",[contact_controller::class,"Get"]);
Route::POST("/Contact/Delete",[contact_controller::class,"Delete"]);

// Wanted
Route::GET("/dashboard/wanted",[wanted_controller::class,"index"]);
Route::POST("/Wanted/Get",[wanted_controller::class,"Get"]);
Route::POST("/Wanted/Delete",[wanted_controller::class,"Delete"]);


// Image
Route::GET("/dashboard/image",[image_controller::class,"index"]);
Route::GET("/Image/Get",[image_controller::class,"Get"]);
Route::POST("/Image/Insert",[image_controller::class,"Insert"]);
Route::POST("/Image/Delete",[image_controller::class,"Delete"]);

// Blog
Route::GET("/dashboard/blog",[blog_controller::class,"index"])->name('b_listing');
Route::POST("/dashboard/blog/upload",[blog_controller::class,"upload"]);
Route::POST("/dashboard/blog/update/{id}",[blog_controller::class,"update"]);
Route::GET("/dashboard/blog/add",[blog_controller::class,"add"]);
Route::GET("/dashboard/blog/edit/{id}",[blog_controller::class,"edit"]);
Route::GET("/dashboard/blog/delete/{id}",[blog_controller::class,"delete"]);

// Subscriber
Route::GET("/dashboard/subscriber",[subscriber_controller::class,"Index"]);
Route::POST("/Subscriber/Get",[subscriber_controller::class,"Get"]);
Route::POST("/Subscriber/Delete",[subscriber_controller::class,"Delete"]);
Route::POST("/Subscriber/Status",[subscriber_controller::class,"Status"]);

// Vendors
Route::GET("/dashboard/vendor",[vendor_controller::class,"Index"]);
Route::POST("/Vendor/Get",[vendor_controller::class,"Get"]);
Route::POST("/Vendor/Delete",[vendor_controller::class,"Delete"]);
Route::POST("/Vendor/Status",[vendor_controller::class,"Status"]);
Route::GET("/dashboard/vendor/{code}/info",[vendor_controller::class,"Info"]);
Route::POST("/Vendor/Info/Changes/Save",[vendor_controller::class,"Changes"]);
Route::POST("/Vendor/Info/Commission/Save",[vendor_controller::class,"Commission_Save"]);
Route::GET("/dashboard/vendor/commission",[vendor_controller::class,"Commission"]);
Route::POST("/Get/Commission",[vendor_controller::class,"Get_Commission"]);
Route::GET("/dashboard/vendor/{code}/commission",[vendor_controller::class,"Commission_Info"]);
Route::POST("/Get/Commission/List",[vendor_controller::class,"Commission_List"]);
Route::POST("/Insert/Commission/",[vendor_controller::class,"Insert_Commission"]);
Route::POST("/Get/Sales/List",[vendor_controller::class,"Sales_List"]);

// User
Route::GET("/dashboard/user",[user_controller::class,"Index"]);
Route::POST("/User/Get",[user_controller::class,"Get"]);
Route::POST("/User/Delete",[user_controller::class,"Delete"]);
Route::POST("/User/Status",[user_controller::class,"Status"]);

