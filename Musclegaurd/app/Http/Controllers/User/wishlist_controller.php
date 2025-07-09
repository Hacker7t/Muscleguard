<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class wishlist_controller extends Controller
{
    public function index(){
        if (!session()->get("user_id")) {
            return view('user.login.index');
        }else{ return view('user.wishlist.index'); }
    }
    public function Get(){
        if (session()->get("user_id")) {
            $Conditions = [['product.status',0],['wishlist.user_id',session()->get("user_id")]];
            $Select = ['product.name','product.url','product.card','product.rating','product.price','product.discount','wishlist.code','product.size','product.stock'];
            $Product = DB::table('product')->leftjoin('category','category.category_id','=','product.category_id')->leftjoin('wishlist','wishlist.product_id','=','product.product_id')->select($Select)->where($Conditions)->orderby('product.product_id','DESC')->get();
            return view("user.include.wishlist.index",['Product'=>$Product]);
        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
    public function Removed(Request $request){
        if (session()->get("user_id")) {
            $Delete = DB::table('wishlist')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message' => 'Product Removed From Wishlist Successfully']);
        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
}
