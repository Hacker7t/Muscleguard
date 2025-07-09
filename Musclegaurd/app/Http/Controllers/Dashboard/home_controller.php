<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class home_controller extends Controller
{
    public function index(){
        $Product_Vendor = DB::table('vendor')->where('verified',1)->count();
        $Product_subscriber = DB::table('subscriber')->count();
        $Products = DB::table('product')->count();
        $Category = DB::table('category')->count();
        $Property_Vendor = DB::table('vendor')->where('type',2)->count();
        $User = DB::table('user')->count();
        $Professional = DB::table('professional')->count();
        $Property_Views = DB::table('property')->select('views')->sum('views');
        $Product_Views = DB::table('product')->select('views')->sum('views');
        $Professional_Views = DB::table('professional')->select('views')->sum('views');

        $Cart = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->select('cart.qty','cart.price','cart.discount','product.vendor_id')->where([['cart.status',3]])->get();

        // $Sales = 0;
        // $Commission = 0;
        // foreach($Cart as $c){
        //     $Vendor = DB::table('shop')->leftjoin('vendor','vendor.vendor_id','=','shop.vendor_id')->leftjoin('category','category.category_id','=','shop.category_id')->select('shop.commission as s_commission','category.commission as c_commission')->where('vendor.vendor_id',$c->vendor_id)->get();
        //     if($Vendor[0]->c_commission != $Vendor[0]->s_commission){ $Vendor_Commmission = $Vendor[0]->s_commission; }else{ $Vendor_Commmission = $Vendor[0]->c_commission; }

        //     if ($c->discount != 0) {
        //         $price = (($c->price / 100) * $c->discount - $c->price) * -1;
        //     } else {
        //         $price = ($c->price * $c->qty);
        //     }
        //     $Sales += $price;
        //     $Commission += ((($price / 100) * $Vendor_Commmission) * $c->qty);

        // }

        // $Received_Commission = DB::table('commission')->sum('commission');
        return view("dashboard.home.index",['Product_Vendor' => $Product_Vendor,'Category' => $Category,'Products' => $Products, 'Product_subscriber' => $Product_subscriber,'Property_Vendor' => $Property_Vendor,'User' => $User,'Professional' => $Professional,'Property_Views' => $Property_Views,'Product_Views' => $Product_Views,'Professional_Views' => $Professional_Views]);
    }
    public function Get_Order(){
        $order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('city','city.city_id','=','order.city_id')->select('order.name','order.email','order.phone','order.status','order.code','order.date','order.time')->orderby('order.order_id','DESC')->take(10)->get();
        return view('dashboard.include.order.index',['order'=>$order]);
    }
}
