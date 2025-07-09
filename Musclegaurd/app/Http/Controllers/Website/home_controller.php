<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class home_controller extends Controller
{
    public function sitemap(){
        $product = DB::table('product')->select('url','date')->get();
        $category = DB::table('category')->select('url','date')->get();

        return response()->view('website.sitemap.index', [
            'product' => $product,
            'category' => $category,
        ])->header('Content-Type', 'text/xml');
    }

    public function index(){
        $category = DB::table('category')->select('name','url','card')->where([['status',0],['parent_id',0]])->get();
        $Shop = DB::table('shop')->select('shop_name','code','shop_overview','logo')->where([['status' , 0]])->orderby('shop_id','DESC')->take(10)->get();
        return view("website.home.index",['category' => $category ,'Shop' => $Shop]);
    }
    public function Popular(){
        $product = DB::table('product')->select('name','url','price','discount','card','product_id','rating')->wherestatus(0)->take(8)->get();
        $wishlist = DB::table('wishlist')->select('product_id')->where('user_id',session()->get("user_id"))->get();
        return view("website.include.product.card",['product' => $product,'wishlist' => $wishlist]);
    }
}
