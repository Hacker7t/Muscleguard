<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order;
use Illuminate\Support\Str;

class order_controller extends Controller
{
    public function Insert(Request $request){
        $coupon_id = 0;
        $Get_Charges = DB::table('city')->select('charges')->where([['status',0],['city_id',$request->city_id]])->get();
        $charges = $Get_Charges[0]->charges;

        if (session()->get("coupon")) {
            $Get_Coupon = DB::table('coupon')->select('coupon_id')->wherecoupon(session()->get("coupon"))->get();
            $coupon_id = $Get_Coupon[0]->coupon_id;

            $Update_Coupon = DB::table('coupon')->wherecoupon(session()->get("coupon"))->update(['availability'=>1]);
            $request->session()->forget('coupon');
        }

        if (session()->get("user_id")) { $user_id = 0; }else{ $user_id = 0; }

        // Insert Order
        $Insert = DB::table('order')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'coupon_id' => $coupon_id,
            'user_id' => $user_id,
            'charges' => $charges,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ]);

        $order_id = DB::getPdo()->lastInsertId();
        $Update_Code = DB::table('order')->whereorder_id($order_id)->update(['code' => md5($order_id)]);
        $Update_Cart = DB::table('cart')->where([['ip',$request->ip()],['order_id',0]])->update(['order_id' => $order_id]);
        if (session()->get("user_id")){
            $Update_Code = DB::table('order')->whereorder_id($order_id)->update(['user_id' => session()->get("user_id")]);  
        }
        if (Str::contains(request()->getHttpHost(), 'com') == true) {
            $Order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('city','city.city_id','=','order.city_id')->select('order.order_id','order.name','order.email','order.user_id','order.address','order.date','country.name as country','state.name as state','city.name as city')->where([['order.order_id',$order_id]])->get();
            $detail = ['status' => $request->status,'order_id' => $Order[0]->order_id,'date' => $Order[0]->date,'address' => $Order[0]->address,'country' => $Order[0]->country,'city' => $Order[0]->city,'state' => $Order[0]->state,'name' => $Order[0]->name];
                mail::to($Order[0]->email)->send(new Order($detail));
        }
        $request->session()->put('order_id', $order_id);

        return response()->json(['error'=>false,'message'=>'Order Placed Successfully','code'=>md5($order_id)]);
    }
    public function Invoice($code){
        $order = DB::table('order')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.date','order.order_id','order.address','country.name as country','city.name as city','state.name as state','coupon.discount','order.charges','order.zip_code','order.paid')->where([['order.code','=',$code]])->get();
        $cart = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->select('cart.size','cart.qty','cart.color','product.name','cart.price','cart.discount')->where([['order.code',$code]])->get();
        return view("website.invoice.index",['order'=>$order,'cart'=>$cart]);
    }
    public function Confirm(Request $request){
        $Order = DB::table('order')->where([['code',$request->code],['paid',0]])->update(['paid' => 2]);
        return response()->json(['error'=>false,'message'=>'Order Confirm Successfully']);
    }
}
