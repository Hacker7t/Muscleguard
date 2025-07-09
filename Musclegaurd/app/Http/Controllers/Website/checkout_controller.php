<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class checkout_controller extends Controller
{
    public function Checkout(Request $request){
        if ($request->coupon != null) {
            $request->session()->put('coupon', $request->coupon);
        } return response()->json(['error' => false]);
    }
    public function Index(Request $request){
        if (session()->get("user_id")) {
            $Check = DB::table('cart')->where([['order_id',0],['user_id',session()->get("user_id")]])->count();
            if ($Check == 0) { return redirect('/cart');}

            $Cart = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.price','cart.discount','cart.qty','cart.shipment','cart.charges','vendor.city_id')->where([['user_id',session()->get("user_id")],['order_id',0]])->get();
            $Sub_Total = 0;
            $Total = 0;
            $Save = 0;
            $Discount = 0;
            $Counpon = session()->get("coupon");
            $Shipment = 0;
            foreach ($Cart as $C) {
                if ($C->discount != 0) {
                    $price = ((($C->price / 100) * $C->discount) - $C->price) * -1;
                }else{$price = $C->price;}
                $Total += $price * $C->qty;
                $Sub_Total += $price * $C->qty;
            }

            // if (session()->get("coupon")) {
            //     $Coupon = DB::table('coupon')->select('discount')->wherecoupon($Counpon)->get();
            //     $Save = (($Sub_Total / 100) * $Coupon[0]->discount);
            //     $Sub_Total = $Sub_Total - $Save;
            //     $Discount = $Coupon[0]->discount;
            // }

            $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['cart.user_id',session()->get("user_id")],['cart.order_id',0],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();
            foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
            foreach ($Cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}

            $Sub_Total += $Shipment;

            $Country = DB::table('country')->select('name')->wherecountry_id(session()->get("country_id"))->get();
            $State = DB::table('state')->select('name')->wherestate_id(session()->get("state_id"))->get();
            $City = DB::table('city')->select('name')->wherecity_id(session()->get("city_id"))->get();

            return view('website.checkout.index',['Sub_Total'=>$Sub_Total,'Total'=>$Total,'Save'=>$Save,'Discount'=>$Discount,'Counpon'=>$Counpon,'Shipment' => $Shipment,'Country' => $Country,'State' => $State,'City' => $City]);

        }else{ return redirect('/login/user'); }

    }

    public function Placed(Request $request){
        if (session()->get("user_id")) {

            $Check = DB::table('cart')->where([['order_id',0],['user_id',session()->get("user_id")]])->count();
            if ($Check == 0) { return response()->json(['error' => true,'message' => 'Cart Is Empty']);}

            $Sub_Total = 0;
            $Coupon = session()->get("coupon");
            $Shipment = 0;
            $coupon_id = 0;

            $Cart = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.price','cart.discount','cart.qty','cart.shipment','cart.charges','vendor.city_id')->where([['user_id',session()->get("user_id")],['order_id',0]])->get();

            $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['cart.user_id',session()->get("user_id")],['cart.order_id',0],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();
            foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
            foreach ($Cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}
            foreach ($Cart as $C) {
                if ($C->discount != 0) {
                    $price = ((($C->price / 100) * $C->discount) - $C->price) * -1;
                }else{$price = $C->price;}
                $Sub_Total += $price * $C->qty;
            }

            // if (session()->get("coupon")) {
            //     $Get_Coupon = DB::table('coupon')->select('coupon_id')->wherecoupon($Coupon)->get();
            //     $coupon_id = $Get_Coupon[0]->coupon_id;

            //     $Update_Coupon = DB::table('coupon')->wherecoupon($Coupon)->update(['availability'=>1]);
            //     $request->session()->forget('coupon');
            // }


            // Insert Order
            $Insert = DB::table('order')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => session()->get("country_id"),
                'city_id' => session()->get("city_id"),
                'state_id' => session()->get("state_id"),
                'address' => session()->get("address"),
                'note' => $request->note,
                'coupon_id' => $coupon_id,
                'user_id' => session()->get("user_id"),
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            ]);

            $order_id = DB::getPdo()->lastInsertId();
            $Update_Code = DB::table('order')->whereorder_id($order_id)->update(['code' => md5($order_id)]);
            $Update_Cart = DB::table('cart')->where([['user_id',session()->get("user_id")],['order_id',0]])->update(['order_id' => $order_id]);

            // if (Str::contains(request()->getHttpHost(), 'com') == true) {
            //     $Data = DB::table('order')
            //     ->leftjoin('country','country.country_id','=','order.country_id')
            //     ->leftjoin('state','state.state_id','=','order.state_id')
            //     ->leftjoin('city','city.city_id','=','order.city_id')
            //     ->where([['order.order_id',$order_id]])
            //     ->select('order.name','order.email','order.order_id','order.date','country.name as country','state.name as state','city.name as city','order.address')
            //     ->get();

            //     $detail = ['name' => $Data[0]->name,'order_id' => $Data[0]->order_id,'date' => $Data[0]->date,'country' => $Data[0]->country,'state' => $Data[0]->state,'city' => $Data[0]->city,'address' => $Data[0]->address];
            //     mail::to($request->email)->send(new Placed($detail));
            // }
            return response()->json(['error' => false,'code' => md5($order_id)]);

        }else{ return response()->json(['error' => false]); }
    }
    public function Invoice($code){
        if (session()->get("user_id")) {

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

            return view("user.order.invoice",['Order' => $Order[0],'cart' => $cart,'Total' => $Total,'Save' => $Save,'Shipment' => $Shipment]);

        }else{ return redirect('/login/user'); }
    }
}
