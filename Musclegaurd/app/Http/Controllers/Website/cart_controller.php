<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class cart_controller extends Controller
{
    public function Index(){  return view('website.cart.index');}
    public function Insert(Request $request){
        if (session()->get("user_id")) {
            $product = DB::table('product')->leftjoin('vendor','vendor.vendor_id','=','product.vendor_id')->select('product.product_id','product.discount','product.price','product.shipment','product.inter as p_inter','product.intra as p_intra','vendor.city_id','vendor.inter as v_inter','vendor.intra as v_intra')->whereurl($request->url)->get();

            if ($product[0]->shipment == 1) {
                if (session()->get('city_id') == $product[0]->city_id) {
                    $charges =  $product[0]->p_intra;
                }else{
                    $charges =  $product[0]->p_inter;
                }
            }else{
                if (session()->get('city_id') == $product[0]->city_id) {
                    $charges =  $product[0]->v_intra;
                }else{
                    $charges =  $product[0]->v_inter;
                }
            }

            $Insert = DB::table('cart')->insert([
                'product_id' => $product[0]->product_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'color' => $request->color,
                'price' => $product[0]->price,
                'discount' => $product[0]->discount,
                'shipment' => $product[0]->shipment,
                'charges' => $charges,
                'user_id' => session()->get("user_id"),
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            ]); $cart_id = DB::getPdo()->lastInsertId(); $Update_Code = DB::table('cart')->wherecart_id($cart_id)->update(['code' => md5($cart_id)]);

            return response()->json(['error' => false,'message'=>'Product Add In To Cart Successfully']);
        }else{ return response()->json(['error' => true,'message'=>'Login Required']);  }
    }
    public function Get(Request $request){ $Shipment = 0;
        $cart = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('product.name','cart.price','product.url','cart.discount','cart.qty','product.size','cart.size as selected_size','product.colors','cart.color as selected_color','cart.code','product.card','cart.code','product.stock','cart.shipment','cart.charges','vendor.city_id')->where([['cart.user_id',session()->get("user_id")],['cart.order_id',0]])->orderby('cart.cart_id','desc')->get();
        foreach ($cart as $c) { if ($c->shipment == 1) { $Shipment += ($c->charges * $c->qty); }}

        $Get_Common_Shipment = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('vendor','product.vendor_id','=','vendor.vendor_id')->select('cart.charges','vendor.vendor_id')->where([['cart.user_id',session()->get("user_id")],['cart.order_id',0],['product.shipment',0]])->groupby('cart.charges','vendor.vendor_id')->get();
        foreach ($Get_Common_Shipment as $GCS) { $Shipment += $GCS->charges; }

        $Total = 0;
        foreach ($cart as $c) { if ($c->discount != 0) { $price = (($c->price / 100) * $c->discount - $c->price) * -1;} else { $price = $c->price; } $Total += ($price * $c->qty);}
        return view("website.include.cart.index",['cart'=>$cart,'Total' => $Total,'Shipment' => $Shipment]);
    }
    public function Quantity(Request $request){
        $Update = DB::table('cart')->wherecode($request->code)->update(['qty'=>$request->qty]);
        return response()->json(['error' => false,'message'=>'Quantity Updated Successfully']);
    }
    public function Check(Request $request){
        $coupon = DB::table('coupon')->select('discount')->where([['coupon','=',$request->coupon],['availability',0],['status',0]])->get();
        return response()->json(['coupon'=>$coupon]);
    }
    public function Delete(Request $request){
        $Delete = DB::table('cart')->wherecode($request->code)->delete(); return response()->json(['error' => false,'message' => 'Product Removed From Cart']);
    }
    public function Shipment_Update(Request $request){
        $request->session()->put('country_id', $request->country_id);
        $request->session()->put('state_id', $request->state_id);
        $request->session()->put('city_id', $request->city_id);
        $request->session()->put('address', $request->address);
        return response()->json(['error' => false,'message' => 'Shipment Updated Successfully']);
    }
    public function Shipment_Get(){
        $Country = DB::table('country')->select('name')->wherecountry_id(session()->get("country_id"))->get();
        $State = DB::table('state')->select('name')->wherestate_id(session()->get("state_id"))->get();
        $City = DB::table('city')->select('name')->wherecity_id(session()->get("city_id"))->get();

        $cart = DB::table('cart')->select('product_id','cart_id')->where([['user_id',session()->get("user_id")],['order_id',0]])->get();
        foreach($cart as $c){
            $product = DB::table('product')->leftjoin('vendor','vendor.vendor_id','=','product.vendor_id')->select('product.product_id','product.discount','product.price','product.shipment','product.inter as p_inter','product.intra as p_intra','vendor.city_id','vendor.inter as v_inter','vendor.intra as v_intra')->whereproduct_id($c->product_id)->get();

            if ($product[0]->shipment == 1) {
                if (session()->get('city_id') == $product[0]->city_id) {
                    $charges =  $product[0]->p_intra;
                }else{
                    $charges =  $product[0]->p_inter;
                }
            }else{
                if (session()->get('city_id') == $product[0]->city_id) {
                    $charges =  $product[0]->v_intra;
                }else{
                    $charges =  $product[0]->v_inter;
                }
            }
            $cart = DB::table('cart')->select('product_id','cart_id')->where([['user_id',session()->get("user_id")],['cart_id',$c->cart_id]])->update(['charges' => $charges]);
        }

        return response()->json(['error' => false,'Country' => $Country[0]->name,'State' => $State[0]->name,'City' => $City[0]->name,'address' => session()->get("address")]);
    }
}
