<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class country_controller extends Controller
{
    public function index(){
        return view('dashboard.country.index');
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
            $Check = DB::table('country')->wherecountry_code(strtolower($request->country_code))->count();
            if ($Check != 0) {return response()->json(['error' => true,'message'=>'Country Allready Exist!']);}

            $insert = DB::table('country')->insert(
                [
                    'name' => $request->name,
                    'country_code' => $request->country_code,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                ]);

            $country_id = DB::getPdo()->lastInsertId(); $update = DB::table('country')->wherecountry_id($country_id)->update(['code' => md5($country_id)]); return response()->json(['error' => false,'message'=>'Country Added Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function GET(Request $request){
        $Where = [];
        if ($request->search != null) {$Where[] = ['country.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['country.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['country.date','<=',$request->end_date];}
        $country = DB::table('country')->leftjoin('account','account.account_id','=','country.account_id')->select('country.name','country.status','country.code','country.country_code','country.date','country.country_id','account.name as account')->where($Where)->orderby('country.country_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.country.index',['country'=>$country]);
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $country = DB::table('country')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'country Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('country')->select('name','country_code')->where('code','=',$code)->get();
            return view('dashboard.country.edit',['Edit'=>$Edit,'code'=>$code]);
        }else{return response()->json(['error' => true]);}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $Check = DB::table('country')->where([['country_code',strtolower($request->country_code)],['code','!=',$request->code]])->count();
            if ($Check != 0) {return response()->json(['error' => true,'message'=>'Country Allready Exist!']);}

            $update = DB::table('country')->wherecode($request->code)->update([
                'name' => $request->name,
                'country_code' => $request->country_code,
            ]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("country")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_country = DB::table('country')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' country Is De-Active Successfully']);}
            if ($check[0]->status == 1) {
                $Active_country = DB::table('country')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> 'country Is Active Successfully']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
