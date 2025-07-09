<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class gym_controller extends Controller
{
    public function index(){
        $Shop = DB::table('shop')->select('shop_name','code','shop_overview','logo')->where([['status' , 0]])->orderby('shop_id','DESC')->get();
        return view("website.gym.index",['category_id' => 0,'sub_category_id' => 0,'Shop' => $Shop,'search' => null]);
    }
    public function Search(Request $request){
        $Shop = DB::table('shop')->select('shop_name','code','shop_overview','logo')->where([['status' , 0],['shop_name' ,'LIKE','%'.$request->search.'%']])->orderby('shop_id','DESC')->get();
        return view("website.gym.index",['category_id' => 0,'sub_category_id' => 0,'Shop' => $Shop,'search' => $request->search]);
    }
    public  function Detail($code){
        $Shop = DB::table('shop')->select('shop_name','code','shop_overview','logo','banner','member','date','category_id')->where([['status' , 0],['code' , $code]])->orderby('shop_id','DESC')->get();
        $Category = DB::table('category')->select('name')->where('category_id',$Shop[0]->category_id)->get();
        return view("website.gym.detail",['Shop' => $Shop,'Category' => $Category,'code' => $code]);
    }
    public function Product($code){
        $Shop = DB::table('shop')->select('vendor_id')->where('code' , $code)->get();
        $product = DB::table('product')->select('name','url','price','discount','card','product_id','rating')->where([['vendor_id' , $Shop[0]->vendor_id]])->take(8)->get();
        $wishlist = DB::table('wishlist')->select('product_id')->where('user_id',session()->get("user_id"))->get();
        return view("website.include.product.card",['product' => $product,'wishlist' => $wishlist]);
    }
}
