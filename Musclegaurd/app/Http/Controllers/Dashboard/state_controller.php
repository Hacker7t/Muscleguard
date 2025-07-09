<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class state_controller extends Controller
{
    public function index(){
        return view('dashboard.state.index');
    }
    public function Get_Country(){
        if (session()->get("account_id")) {
            $country = DB::table('country')->select('name','country_id')->wherestatus(0)->get();
            return response()->json(['country' => $country]);
        }else{return response()->json(['error' => true]);}
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
            $insert = DB::table('state')->insert(
                [
                    'name' => $request->name,
                    'country_id' => $request->country_id,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                ]);

                $state_id = DB::getPdo()->lastInsertId(); $update = DB::table('state')->wherestate_id($state_id)->update(['code' => md5($state_id)]); return response()->json(['error' => false,'message'=>'state Added Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function GET(Request $request){
        $Where = [];
        if ($request->search != null) {$Where[] = ['state.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['state.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['state.date','<=',$request->end_date];}
        $state = DB::table('state')->leftjoin('account','account.account_id','=','state.account_id')->leftjoin('country','country.country_id','=','state.country_id')->select('state.name','state.status','state.code','state.date','state.state_id','account.name as account','country.name as country')->where($Where)->orderby('state.state_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.state.index',['state'=>$state]);
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $state = DB::table('state')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'state Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('state')->select('name','country_id')->where('code','=',$code)->get();
            return view('dashboard.state.edit',['Edit'=>$Edit,'code'=>$code]);
        }else{return response()->json(['error' => true]);}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $update = DB::table('state')->wherecode($request->code)->update([
                'name' => $request->name,
                'country_id' => $request->country_id,
            ]); return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("state")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_state = DB::table('state')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Is De-Active']);}
            if ($check[0]->status == 1) {
                $Active_state = DB::table('state')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> ' Is Active']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
