<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class subscriber_controller extends Controller
{
    public function Index(){
        return view('dashboard.subscriber.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['email','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['date','<=',$request->end_date];}
            $subscriber = DB::table('subscriber')->select('email','status','code','date')->where($Where)->orderby('subscriber_id',$request->orderby)->take($request->take)->get();
            return view('dashboard.include.subscriber.index',['subscriber'=>$subscriber]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Delete = DB::table('subscriber')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message' => 'Subscriber Has Been Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("subscriber")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_subscriber = DB::table('subscriber')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Is De-Active']);}
            if ($check[0]->status == 1) {
                $Active_subscriber = DB::table('subscriber')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> ' Is Active']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
