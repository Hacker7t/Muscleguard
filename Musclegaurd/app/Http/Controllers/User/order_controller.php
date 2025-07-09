<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class order_controller extends Controller
{
    public function Index(){
        if (session()->get("user_id")) {
            return view('user.order.index',['status' => 'all']);
        }else{ return redirect('/login/user'); }
    }
    public function Redirect($status){
        if (session()->get("user_id")) {
            return view('user.order.index',['status' => $status]);
        }else{ return redirect('/login/user'); }
    }
    public function Get(Request $request){
        if (session()->get("user_id")) {
            $Where = [['order.user_id',session()->get("user_id")]];
            if ($request->search != null) {$Where[] = ['order.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['order.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['order.date','<=',$request->end_date];}
            if ($request->status != 'all') {$Where[] = ['order.status','=',$request->status];}
            $order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('city','city.city_id','=','order.city_id')->select('order.name','order.email','order.phone','order.status','order.code','order.date','order.time')->where($Where)->orderby('order.order_id',$request->orderby)->take($request->take)->get();
            return view('user.include.order.index',['order'=>$order]);
        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
    public function Info($code){
        if (session()->get("user_id")) {
            $order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.status','order.date','order.time','order.coupon_id','order.order_id','order.address','order.code','order.note','country.name as country','city.name as city','state.name as state','coupon.discount as coupon')->where([['order.code',$code]])->get();

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

            return view("user.order.info",['order' => $order[0],'code' => $code,'Total' => $Total,'Save' => $Save,'Shipment' => $Shipment]);
        }else{ return redirect('/login/user'); }
    }
    public function Cart($code){
        if (session()->get("user_id")) {
            $Where = [['order.code',$code]];
            $cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->leftjoin('order','order.order_id','=','cart.order_id')
            ->select('product.name','product.card','cart.price','cart.discount','cart.qty','cart.color','cart.size','product.stock','cart.status','cart.code','product.url')
            ->where($Where)
            ->get();
            return view("user.include.order.cart",['cart' => $cart]);
        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
}
