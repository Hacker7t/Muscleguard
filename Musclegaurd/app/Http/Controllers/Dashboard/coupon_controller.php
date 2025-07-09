<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class coupon_controller extends Controller
{
    public function index(){
        return view('dashboard.coupon.index');
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
            // DD($request);
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.time())));
            $insert = DB::table('coupon')->insert(
                [
                    'coupon' => $request->coupon,
                    'discount' => $request->discount,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $coupon_id = DB::getPdo()->lastInsertId(); $code = md5($coupon_id);
            $update = DB::table('coupon')->where('coupon_id', '=', $coupon_id)->update(['code' => $code]);

            return response()->json(['error' => false,'message'=>'Coupon Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function GET(Request $request){
        $Where = [];
        if ($request->search != null) {$Where[] = ['coupon.discount','=',$request->search];}
        $coupon = DB::table('coupon')->leftjoin('account','account.account_id','=','coupon.account_id')->select('coupon.coupon','coupon.discount','coupon.status','coupon.date','coupon.code','coupon.availability','account.name as account')->where($Where)->orderby('coupon_id',$request->orderby)->take($request->take)->get();
        return view("dashboard.include.coupon.index",['coupon'=>$coupon]);
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $coupon = DB::table('coupon')->where('code','=',$request->code)->delete();
            return response()->json(['error' => false,'message'=>'Coupon Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Generate(){return response()->json(['error' => false,'coupon'=>Str::random(8).mt_rand(100000, 999999)]);}
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("coupon")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_coupon = DB::table('coupon')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> 'Coupon Is De-Active Successfully']);}
            if ($check[0]->status == 1) {
                $Active_coupon = DB::table('coupon')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> 'Coupon Is Active Successfully']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Renew(Request $request){
        if (session()->get("account_id")) {
            $Renew = DB::table('coupon')->wherecode($request->code)->update(['availability' => 0]);
            return response()->json(['error'=> false,'message'=> 'Coupon Is Renew Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
}
