<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Processing;
use App\Mail\Completed;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class order_controller extends Controller
{
    public function index(){ if (session()->get("vendor_id")) { return view('vendor.order.index'); }else{ return redirect('/login/vendor'); }}
    public function Get(Request $request){
        if (session()->get("vendor_id")) {
            $Where = [['product.vendor_id' , session()->get("vendor_id")],['cart.order_id' ,'!=', 0]];
            $take = $request->take;
            if ($request->search != null) {$Where[] = ['order.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['order.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['order.date','<=',$request->end_date];}
            if ($take == 0) { $take = DB::table('order')->where('vendor_id',session()->get('vendor_id'))->count(); }
            $order = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->select('order.date','order.time','order.name','order.phone','order.email','order.code')->where($Where)->groupby('order.date','order.time','order.name','order.phone','order.email','order.code')->orderby('cart.cart_id',$request->orderby)->take($take)->get();
            return view("vendor.include.order.index",['order'=>$order]);
        }else{  return response()->json(['error'=>'logout']); }
    }
    public function info($code){
        if (session()->get("vendor_id")) {
            $Shipment = 0;
            $Where = [['order.code',$code]];
            $Order = DB::table('order')->leftjoin('cart','cart.order_id','=','order.order_id')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.order_id','order.status','order.date','order.time','order.note','order.code','country.name as country','state.name as state','city.name as city','order.address','coupon.discount as coupon')->where($Where)->get();


            $Cart = DB::table('cart')->leftjoin('order','order.order_id','=','cart.order_id')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.shipment','cart.charges','cart.qty','cart.discount','cart.price')->where([['order.code',$code],['product.vendor_id','=',session()->get("vendor_id")]])->get();

            $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['order.code',$code],['product.vendor_id','=',session()->get("vendor_id")],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();
            foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
            foreach ($Cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}


            $Total = 0;
            $Save = 0;
            foreach ($Cart as $c) { if ($c->discount != 0) { $price = (($c->price / 100) * $c->discount - $c->price) * -1;} else { $price = $c->price; } $Total += ($price * $c->qty);}

            if ($Order[0]->coupon != 0) {
                $Save = ($Total / 100) * $Order[0]->coupon;
                $Total = $Total - $Save;
            }

            return view("vendor.order.info",['order' => $Order[0],'code' => $code,'Shipment' => $Shipment,'Total' => $Total,'Save' => $Save]);
        }else{  return response()->json(['error'=>'logout']); }
    }
    public function Cart($code){
        if (session()->get("vendor_id")) {
            $Where = [['order.code',$code],['product.vendor_id' , session()->get("vendor_id")]];
            $cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->leftjoin('order','order.order_id','=','cart.order_id')
            ->select('product.name','product.card','cart.price','cart.discount','cart.qty','cart.color','cart.size','product.stock','cart.status','cart.code','product.url','product.size as sizes')
            ->where($Where)
            ->get();
            return view("vendor.include.order.cart",['cart' => $cart]);
        }else{  return response()->json(['error'=>'logout']); }
    }
    public function Confirm(Request $request){
        if (session()->get("vendor_id")) {

            $Cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->select('product.size as Sizes','cart.product_id','cart.size','cart.qty','product.name','product.stock','cart.order_id')
            ->where([['cart.code',$request->code]])->get();
            // dd($Cart);
            // Check Stock
            foreach ($Cart as $c) {
                $Sizes = json_decode($c->Sizes);
                if($Sizes != null){
                    foreach ($Sizes as $s) {
                        if ($s->size == $c->size) {
                            if ($c->qty > ($s->stock - $s->sold)) {
                                return response()->json(['error' => true,'message'=> $c->name.' Is Not Enough Stock!']);
                            }
                        }
                    }
                }else{
                    if ($c->qty > $c->stock) {
                        return response()->json(['error' => true,'message'=> $c->name.' Is Not Enough Stock!']);
                    }
                }
            }

            // Stock Minus
            foreach ($Cart as $c) {
                $Sizes = json_decode($c->Sizes);
                $New_Sizes = [];
                if($Sizes != null){
                    foreach ($Sizes as $s) {
                        if ($s->size == $c->size) {
                            $New_Sizes[] = array('size'=>$s->size,'stock'=> $s->stock,'sold'=>$s->sold + $c->qty,'status'=>0);
                        }else{ $New_Sizes[] = $s; }
                    }$Update_Size = DB::table('product')->whereproduct_id($c->product_id)->update(['size'=>json_encode($New_Sizes)]);
                }else{
                    $Update_Size = DB::table('product')->whereproduct_id($c->product_id)->update(['stock' => ($c->stock - $c->qty)]);
                }
            }


            // Update Cart Status
            $Cart_Status = DB::table('cart')->where([['cart.code',$request->code]])->update(['status' => 1]);
        
            $Data = DB::table('order')
            ->leftjoin('country','country.country_id','=','order.country_id')
            ->leftjoin('state','state.state_id','=','order.state_id')
            ->leftjoin('city','city.city_id','=','order.city_id')
            ->where([['order.order_id',$Cart[0]->order_id]])
            ->select('order.name','order.email','order.order_id','order.date','country.name as country','state.name as state','city.name as city','order.address')
            ->get();
        
            // Update Order Status
            if (DB::table('cart')->where([['order_id',$Cart[0]->order_id],['status','!=',3]])->count() == 0) {
                $Order = DB::table('order')->whereorder_id($Cart[0]->order_id)->update(['status' => 2]);
                if (Str::contains(request()->getHttpHost(), 'com') == true) {
                    $detail = ['name' => $Data[0]->name,'order_id' => $Data[0]->order_id,'date' => $Data[0]->date,'country' => $Data[0]->country,'state' => $Data[0]->state,'city' => $Data[0]->city,'address' => $Data[0]->address];
                    mail::to($Data[0]->email)->send(new Completed($detail));
                }
            }else{ 
                $Order = DB::table('order')->whereorder_id($Cart[0]->order_id)->update(['status' => 1]); 
                if (Str::contains(request()->getHttpHost(), 'com') == true) {
                    $detail = ['name' => $Data[0]->name,'order_id' => $Data[0]->order_id,'date' => $Data[0]->date,'country' => $Data[0]->country,'state' => $Data[0]->state,'city' => $Data[0]->city,'address' => $Data[0]->address];
                    mail::to($Data[0]->email)->send(new Processing($detail));
                }
            }

        
            // // FCM server key
            // $serverKey = config('app.name');

            // // FCM endpoint
            // $endpoint = 'https://fcm.googleapis.com/fcm/send';

            // // Recipient's FCM token
            // $token = 'recipient_token_here';

            // // Notification details
            // $title = 'Notification Title';
            // $des = 'Notification Body';

            // // Additional data
            // $data = [
            //     'key1' => 'value1',
            //     'key2' => 'value2',
            // ];

            // // Request payload
            // $body = [
            //     'to' => $token,
            //     'priority' => 'high',
            //     'notification' => [
            //         'title' => $title,
            //         'body' => $des,
            //     ],
            //     'data' => $data,
            // ];

            // // Sending the HTTP POST request to FCM
            // $response = Http::withHeaders([
            //     'Content-Type' => 'application/json',
            //     'Authorization' => 'key=' . $serverKey,
            // ])->post($endpoint, $body);

            // // Handling the response
            // if ($response->successful()) {
            //     // FCM request was successful
            //     $responseData = $response->json();
            //     // Handle the response data as needed
            // } else {
            //     // FCM request failed
            //     $errorCode = $response->status();
            //     // Handle the error code or response as needed
            // }

            return response()->json(['error' => false,'message' => 'Order Has Been Confirm Successfully']);
        }else{ return response()->json(['error' => true,'message' => 'Session Expire']); }
    }

    public function Cancel(Request $request){
        if (session()->get("vendor_id")) {
            $Cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->select('product.size as Sizes','cart.product_id','cart.size','cart.qty','product.name','product.stock','cart.order_id')
            ->where([['cart.code',$request->code]])->get();

            // Stock Minus
            foreach ($Cart as $c) {
                $Sizes = json_decode($c->Sizes); 
                $New_Sizes = [];
                if($Sizes != null){
                    foreach ($Sizes as $s) {
                        if ($s->size == $c->size) { 
                            $New_Sizes[] = array('size'=>$s->size,'stock'=>$s->stock,'sold'=>$s->sold - $c->qty,'status'=>0);
                        }else{ $New_Sizes[] = $s; }
                    }$Update_Size = DB::table('product')->whereproduct_id($c->product_id)->update(['size'=>json_encode($New_Sizes)]);
                }else{
                    $Update_Size = DB::table('product')->whereproduct_id($c->product_id)->update(['stock' => ($c->stock + $c->qty)]);
                }
            }
            // Update Cart Status
            $Cart_Status = DB::table('cart')->where([['cart.code',$request->code]])->update(['status' => 2]);

            return response()->json(['error' => false,'message' => 'Order Has Been Cancel Successfully']);
        }else{ return response()->json(['error' => true,'message' => 'Session Expire']); }

    }

    public function Dispatch(Request $request){
        if (session()->get("vendor_id")) {
            
            // Update Cart Status
            $Cart_Status = DB::table('cart')->where([['cart.code',$request->code]])->update(['status' => 3]);
            
            $Cart = DB::table('cart')->select('order_id')->wherecode($request->code)->get();
                
            $Data = DB::table('order')
            ->leftjoin('country','country.country_id','=','order.country_id')
            ->leftjoin('state','state.state_id','=','order.state_id')
            ->leftjoin('city','city.city_id','=','order.city_id')
            ->where([['order.order_id',$Cart[0]->order_id]])
            ->select('order.name','order.email','order.order_id','order.date','country.name as country','state.name as state','city.name as city','order.address')
            ->get();
            
            if (DB::table('cart')->where([['order_id',$Cart[0]->order_id],['status','!=',3]])->count() == 0) {
                $Order = DB::table('order')->whereorder_id($Cart[0]->order_id)->update(['status' => 2]);
                if (Str::contains(request()->getHttpHost(), 'com') == true) {
                    $detail = ['name' => $Data[0]->name,'order_id' => $Data[0]->order_id,'date' => $Data[0]->date,'country' => $Data[0]->country,'state' => $Data[0]->state,'city' => $Data[0]->city,'address' => $Data[0]->address];
                    mail::to($Data[0]->email)->send(new Completed($detail));}
            }else{ $Order = DB::table('order')->whereorder_id($Cart[0]->order_id)->update(['status' => 1]); }
        
            return response()->json(['error' => false,'message' => 'Order Has Been Dispatch Successfully']);

        }else{ return response()->json(['error' => true,'message' => 'Session Expire']); }
    }

    public function Invoice($code){
        if (session()->get("vendor_id")) {

            $Shipment = 0;

            $Order = DB::table('order')->leftjoin('cart','cart.order_id','=','order.order_id')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('country','country.country_id','=','order.country_id')->leftjoin('state','state.state_id','=','order.state_id')->leftjoin('city','city.city_id','=','order.city_id')->leftjoin('coupon','coupon.coupon_id','=','order.coupon_id')->select('order.name','order.email','order.phone','order.order_id','order.status','order.date','order.time','order.note','order.code','country.name as country','state.name as state','city.name as city','order.address','coupon.discount as coupon')->where([['order.code',$code]])->get();

            $Where = [['order.code',$code],['product.vendor_id' , session()->get("vendor_id")]];
            $cart = DB::table('cart')
            ->leftjoin('product','product.product_id','=','cart.product_id')
            ->leftjoin('order','order.order_id','=','cart.order_id')
            ->select('product.name','product.card','cart.price','cart.discount','cart.qty','cart.color','cart.size','product.stock','cart.status','cart.code','product.url','cart.shipment','cart.charges')
            ->where($Where)
            ->get();

            $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['order.code',$code],['product.vendor_id','=',session()->get("vendor_id")],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();
            foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }
            foreach ($cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}


            $Total = 0;
            $Save = 0;
            foreach ($cart as $c) { if ($c->discount != 0) { $price = (($c->price / 100) * $c->discount - $c->price) * -1;} else { $price = $c->price; } $Total += ($price * $c->qty);}

            if ($Order[0]->coupon != 0) {
                $Save = ($Total / 100) * $Order[0]->coupon;
                $Total = $Total - $Save;
            }

            return view("vendor.order.invoice",['Order' => $Order[0],'cart' => $cart,'Total' => $Total,'Save' => $Save,'Shipment' => $Shipment]);

        }else{ return redirect('/login/vendor'); }
    }
}
