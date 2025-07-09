<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class home_controller extends Controller
{
    public function index(){ if (session()->get("vendor_id")) {
        $Earning = DB::table('cart')->leftjoin('product','product.product_id','=','cart.product_id')->leftjoin('order','order.order_id','=','cart.order_id')->where([['cart.status', 3],['product.vendor_id',session()->get("vendor_id")],['cart.order_id','!=', 0]])->select('cart.price','cart.discount','order.date')->get();
        // dd($Earning);


        $Find_Earnings = [];
        $date = null;
        $Amount = 0;
        foreach($Earning as $e){
            if($e->date != $date && $date != null){
                $Find_Earnings[] = array('date' => $date, 'amount' => $Amount);
                $date = $e->date;
                $Amount = 0;
            }else{ $date = $e->date; }

            if ($e->discount != 0) {
                $price = (($e->price / 100) * $e->discount - $e->price) * -1;
            } else {
                $price = $e->price;
            }
            $Amount += $price;
        }

        if ($date != null) {
            $Find_Earnings[] = array('date' => $date, 'amount' => $Amount);
        }
        $Earnings = [];
        $Month = null;
        $Total_Amount = 0;
        foreach ($Find_Earnings as $FE) {
            $F_Month = date('F', strtotime($FE['date']));
            if ($Month != $F_Month && $Month != null) {
                $Earnings[] = array('month' => $Month, 'amount' => $Total_Amount);
                $Month = $F_Month;
                $Total_Amount = 0;
            }else{ $Month = $F_Month; }
            $Total_Amount += $FE['amount'];
        }

        if ($Month != null) {
            $Earnings[] = array('month' => $Month, 'amount' => $Total_Amount);
        }

        $Cart = DB::table('vendor')
        ->leftjoin('product','product.vendor_id','=','vendor.vendor_id')
        ->leftjoin('cart','cart.product_id','=','product.product_id')
        ->select('product.name','product.card','cart.qty','cart.price','cart.discount','product.url','cart.order_id')
        ->where([['cart.status',3],['vendor.vendor_id',session()->get("vendor_id")]])->orderby('cart.order_id','DESC')->get();

        $Vendor = DB::table('shop')->leftjoin('vendor','vendor.vendor_id','=','shop.vendor_id')->leftjoin('category','category.category_id','=','shop.category_id')->select('shop.commission as s_commission','category.commission as c_commission')->where('vendor.vendor_id',session()->get("vendor_id"))->get();
        if($Vendor[0]->c_commission != $Vendor[0]->s_commission){ $Vendor_Commmission = $Vendor[0]->s_commission; }else{ $Vendor_Commmission = $Vendor[0]->c_commission; }
        // dd($Vendor);
        $Sales = 0;
        $Commission = 0;
        foreach($Cart as $c){
            if ($c->discount != 0) {
                $price = (($c->price / 100) * $c->discount - $c->price) * -1;
            } else {
                $price = ($c->price * $c->qty);
            }
            $Sales += $price;
            $Commission += ((($price / 100) * $Vendor_Commmission) * $c->qty);
        }
        $Net_AMOUNT = $Sales - $Commission;
        $Vendor_Profit = 100 - $Commission;




        if (session()->get('vendor_type') == 1) {
            $Total_Views = DB::table('product')->select('views')->where('vendor_id', session()->get("vendor_id"))->sum('views');
            $Top_Products = DB::table('product')->select('views','name')->where('vendor_id', session()->get("vendor_id"))->orderby('views','desc')->take(10)->get();
        }else{
            $Total_Views = DB::table('property')->select('views')->where('vendor_id', session()->get("vendor_id"))->sum('views');
            $Top_Products = DB::table('property')->select('views')->where('vendor_id', session()->get("vendor_id"))->orderby('views','desc')->take(10)->get();
        }

        return view('vendor.home.index',['Earnings' => $Earnings,'Cart' => $Cart,'Sales' => $Sales,'Commission' => $Commission,'Net_AMOUNT' => $Net_AMOUNT,'Vendor_Commmission' => $Vendor_Commmission,'Total_Views' => $Total_Views,'Net_AMOUNT' => $Net_AMOUNT,'Vendor_Profit' => $Vendor_Profit,'Top_Products' => $Top_Products]);
    }else{ return redirect('/login/vendor'); }}
}
