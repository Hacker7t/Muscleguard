<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use Stripe;

class payment_controller extends Controller
{
    public function Index($code){
        $Check = DB::table('order')->where([['code',$code],['paid','!=',0]])->count();
        if ($Check != 0) {return redirect('/invoice/'.$code);}

        $Order = DB::table('order')->select('charges','order_id','coupon_id')->where([['code',$code],['paid',0]])->get();
        $Cart = DB::table('cart')->select('price','discount','qty')->where('order_id',$Order{0}->order_id)->get();
        $Discount = 0;
        $Save = 0;
        $Sub_Total = 0;
        foreach ($Cart as $c) {
            if ($c->discount != 0) {
                $price = ((($c->price / 100) * $c->discount) - $c->price) * -1;
            }else{$price = $c->price;}
            $Sub_Total += $price * $c->qty;
        }
        $Total = $Sub_Total + $Order[0]->charges;
        if ($Order[0]->coupon_id != 0) {
            $Coupon = DB::table('coupon')->select('discount')->wherecoupon_id($Order[0]->coupon_id)->get();
            $Save = (($Sub_Total / 100) * $Coupon[0]->discount);
            $After_Discount = $Sub_Total - $Save;
            $Total = $After_Discount + $Order[0]->charges;
            $Discount = $Coupon[0]->discount;
        }
        return view("website.payment.index",['Total' => $Total,'Sub_Total'=>$Sub_Total,'Discount' => $Discount,'Charges' => $Order[0]->charges,'Save' => $Save, 'code' => $code]);
    }

    public function stripePost(Request $request)
    {
        // dd($request->code);

        $Order = DB::table('order')->select('charges','order_id','coupon_id','name')->where([['code',$request->code],['paid',0]])->get();
        $Cart = DB::table('cart')->select('price','discount','qty')->where('order_id',$Order{0}->order_id)->get();

        $Sub_Total = 0;
        foreach ($Cart as $c) {
            if ($c->discount != 0) {
                $price = ((($c->price / 100) * $c->discount) - $c->price) * -1;
            }else{$price = $c->price;}
            $Sub_Total += $price * $c->qty;
        }
        $Total = $Sub_Total + $Order[0]->charges;
        if ($Order[0]->coupon_id != 0) {
            $Coupon = DB::table('coupon')->select('discount')->wherecoupon_id($Order[0]->coupon_id)->get();
            $Save = (($Sub_Total / 100) * $Coupon[0]->discount);
            $After_Discount = $Sub_Total - $Save;
            $Total = $After_Discount + $Order[0]->charges;
        }


        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create ([
                "amount" => $Total * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment Received From Order ID (".$Order[0]->order_id.") | Customer Name (".$Order[0]->name.")"
        ]);

        $Order = DB::table('order')->where([['code',$request->code],['paid',0]])->update(['paid' => 1]);
        Session::flash('success', 'Payment successful!');

        return redirect('/invoice/'.$request->code);
    }
}
