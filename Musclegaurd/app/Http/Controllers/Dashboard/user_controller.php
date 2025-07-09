<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class user_controller extends Controller
{
    public function Index(){
        return view('dashboard.user.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['email','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['date','<=',$request->end_date];}
            $user = DB::table('user')->select('name','email','phone','status','code','date','verified')->where($Where)->orderby('user_id',$request->orderby)->take($request->take)->get();
            return view('dashboard.include.user.index',['user'=>$user]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Delete = DB::table('user')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message' => 'user Has Been Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("user")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_user = DB::table('user')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Is De-Active']);}
            if ($check[0]->status == 1) {
                $Active_user = DB::table('user')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> ' Is Active']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
