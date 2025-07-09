<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class shop_controller extends Controller
{
    public function index(){
        return view("website.shop.index",['category_id' => 0,'sub_category_id' => 0]);
    }
    public function Filter(Request $request){
        $Conditions = [['status',0]];
        $Order_Column = 'product_id';
        if ($request->keywords != 0){ $Conditions[] = ['keywords','LIKE','%'.$request->keywords.'%']; }
        if ($request->category_id != 0){ $Conditions[] = ['category_id',$request->category_id]; }
        if ($request->sub_category_id != 0){ $Conditions[] = ['sub_category_id',$request->sub_category_id]; }
        if ($request->min != 100){ $Conditions[] = ['price','>=',$request->min]; }
        if ($request->max != 200){ $Conditions[] = ['price','<=',$request->max]; }
        if ($request->top_rated != 0){ $Order_Column = 'rating';}
        if ($request->most_viewed != 0){ $Order_Column = 'views';}

        $Select = ['name','url','card','price','discount','product_id','rating'];
        $product = DB::table('product')->select($Select)->where($Conditions)->orderby($Order_Column,'DESC')->get();
        $wishlist = DB::table('wishlist')->select('product_id')->where('user_id',session()->get("user_id"))->get();
        return view("website.include.product.card",['product' => $product,'wishlist' => $wishlist]);
    }
}
