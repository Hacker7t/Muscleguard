<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class wishlist_controller extends Controller
{
    public function index(){
    if (session()->get("user_id")) {
        return view('user.wishlist.index');
    }else{ return redirect('/login/user'); }}

    public function Insert(Request $request){
        if (session()->get("user_id")) {
            $Product = DB::table('product')->select('product_id')->whereurl($request->url)->get();
            $Count = DB::table('wishlist')->where([['user_id',session()->get("user_id")],['product_id',$Product[0]->product_id]])->count();
            if ($Count != 0) {
                $Delete = DB::table('wishlist')->where([['user_id',session()->get("user_id")],['product_id',$Product[0]->product_id]])->delete();
                return response()->json(['error' => false,'message'=>'Wishlist Removed Successfully','wishlist'=>0]);
            }else{
                $Insert = DB::table('wishlist')->insert(['product_id' => $Product[0]->product_id,'user_id' => session()->get("user_id"),'date' => date('Y-m-d'),'time' => date('H:i:s')]);
                $wishlist_id = DB::getPdo()->lastInsertId(); $update = DB::table('wishlist')->wherewishlist_id($wishlist_id)->update(['code' => md5($wishlist_id)]); return response()->json(['error' => false,'message'=>'Wishlist Saved Successfully','wishlist'=>1]);
            }
        }else{ return response()->json(['error' => true]);}
    }
    public function Get(){
        if (session()->get("user_id")) {
            $Conditions = [['product.status',0],['wishlist.user_id',session()->get("user_id")]];
            $Select = ['product.name','product.url','product.card','category.name as category','product.rating','product.price','product.best','product.discount','product.product_id'];
            $Product = DB::table('product')->leftjoin('category','category.category_id','=','product.category_id')->leftjoin('wishlist','wishlist.product_id','=','product.product_id')->select($Select)->where($Conditions)->orderby('product.product_id','DESC')->get();
            $wishlist = DB::table('wishlist')->select('product_id')->where('user_id',session()->get("user_id"))->get();
            return view("website.include.product.index",['Product'=>$Product,'wishlist' => $wishlist]);
        }else{ return response()->json(['error' => true]);  }
    }
}
