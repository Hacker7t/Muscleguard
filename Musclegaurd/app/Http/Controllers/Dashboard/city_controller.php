<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;

class city_controller extends Controller
{
    public function index(){
        return view('dashboard.city.index');
    }
    public function Get_Country(){
        if (session()->get("account_id")) {
            $country = DB::table('country')->select('name','country_id')->wherestatus(0)->get();
            return response()->json(['country' => $country]);
        }else{return response()->json(['error' => true]);}
    }
    public function Get_State($country_id){
        if (session()->get("account_id")) {
            $state = DB::table('state')->select('name','state_id')->where([['status',0],['country_id',$country_id]])->get();
            return response()->json(['state' => $state]);
        }else{return response()->json(['error' => true]);}
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
        $insert = DB::table('city')->insert(
                [
                    'name' => $request->name,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                ]);

        $city_id = DB::getPdo()->lastInsertId(); $update = DB::table('city')->wherecity_id($city_id)->update(['code' => md5($city_id)]); 
        if ($request->file('card')) {
            $card = $request->file('card');
            foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/city/card/', $imgNameToStore);
                $Update_Card = DB::table('city')->wherecity_id($city_id)->update(['card' => $imgNameToStore]);
            }
        }
        return response()->json(['error' => false,'message'=>'City Added Successfully']);
        
    }else{return response()->json(['error' => true]);}
    }
    public function GET(Request $request){
        $Where = [];
        if ($request->search != null) {$Where[] = ['city.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['city.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['city.date','<=',$request->end_date];}
        $city = DB::table('city')->leftjoin('account','account.account_id','=','city.account_id')->leftjoin('country','country.country_id','=','city.country_id')->leftjoin('state','state.state_id','=','city.state_id')->select('city.name','city.status','city.code','city.date','city.city_id','account.name as account','country.name as country','state.name as state')->where($Where)->orderby('city.city_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.city.index',['city'=>$city]);
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $city = DB::table('city')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'City Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('city')->select('name','country_id','state_id','card')->where('code','=',$code)->get();
            return view('dashboard.city.edit',['Edit'=>$Edit,'code'=>$code]);
        }else{return response()->json(['error' => true]);}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $update = DB::table('city')->wherecode($request->code)->update([
                'name' => $request->name,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
            ]); 

            if ($request->file('card')) {
                $Get_Card = DB::table('city')->select('card')->wherecode($request->code)->get();
                if ($Get_Card[0]->card != null) { $Card_path = public_path().'/uploads/city/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/city/card/', $imgNameToStore);
                $Update_Images = DB::table('city')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("city")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_city = DB::table('city')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Is De-Active']);}
            if ($check[0]->status == 1) {
                $Active_city = DB::table('city')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> ' Is Active']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
