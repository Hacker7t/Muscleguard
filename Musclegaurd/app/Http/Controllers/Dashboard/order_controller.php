<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order;
use Illuminate\Support\Str;

class order_controller extends Controller
{
    public function index(){
        return view('dashboard.order.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['date','<=',$request->end_date];}
            $order = DB::table('order')->select('name','email','phone','status','code','date','time')->where($Where)->orderby('order_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.order.index',['order'=>$order]);
        }else{return response()->json(['error' => true]);}
    }
    public function Info($code){
        $order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.status','order.date','order.time','order.coupon_id','order.order_id','order.address','country.name as country','city.name as city','state.name as state','coupon.discount as coupon','order.note','order.code')->where([['order.code',$code]])->get();

        $Shipment = 0;
        $cart = DB::table('cart')
        ->leftjoin('product','product.product_id','=','cart.product_id')
        ->leftjoin('order','order.order_id','=','cart.order_id')
        ->select('cart.shipment','cart.charges','cart.qty','cart.discount','cart.price')
        ->where([['order.code',$code]])
        ->get();

        $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['order.code',$code],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();

        foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
        foreach ($cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}

        $Total = 0;
        $Save = 0;
        foreach ($cart as $c) { if ($c->discount != 0) { $price = (($c->price / 100) * $c->discount - $c->price) * -1;} else { $price = $c->price; } $Total += ($price * $c->qty);}

        if ($order[0]->coupon != 0) {
            $Save = ($Total / 100) * $order[0]->coupon;
            $Total = $Total - $Save;
        }

        return view("dashboard.order.info",['order' => $order[0],'code' => $code,'Total' => $Total,'Save' => $Save,'Shipment' => $Shipment]);
    }
    public function Get_Cart($code){
        $Where = [['order.code',$code]];
        $cart = DB::table('cart')
        ->leftjoin('product','product.product_id','=','cart.product_id')
        ->leftjoin('order','order.order_id','=','cart.order_id')
        ->select('product.name','product.card','cart.price','cart.discount','cart.qty','cart.color','cart.size','product.stock','cart.status','cart.code','product.url')
        ->where($Where)
        ->get();
        return view("dashboard.include.order.cart",['cart' => $cart]);
    }
    public function Invoice($code){
        $Shipment = 0;
            $Where = [['order.code',$code]];
            $Order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.order_id','order.status','order.date','order.time','order.note','order.code','country.name as country','state.name as state','city.name as city','order.address','coupon.discount as coupon')->where($Where)->get();


            $cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->leftjoin('order','order.order_id','=','cart.order_id')
            ->select('product.name','product.card','cart.price','cart.discount','cart.qty','cart.color','cart.size','product.stock','cart.status','cart.code','product.url','cart.shipment','cart.charges','cart.order_id')
            ->where($Where)
            ->get();

            $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['order.code',$code],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();

            foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
            foreach ($cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}

            $Total = 0;
            $Save = 0;
            foreach ($cart as $c) { if ($c->discount != 0) { $price = (($c->price / 100) * $c->discount - $c->price) * -1;} else { $price = $c->price; } $Total += ($price * $c->qty);}

            if ($Order[0]->coupon != 0) {
                $Save = ($Total / 100) * $Order[0]->coupon;
                $Total = $Total - $Save;
            }

            return view("dashboard.order.invoice",['Order' => $Order[0],'cart' => $cart,'Total' => $Total,'Save' => $Save,'Shipment' => $Shipment]);
    }
}
