<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class setting_controller extends Controller
{
    public function Index(){
        if (session()->get("user_id")) {
            $User = DB::table('user')->select('name','email','phone','country_id','state_id','city_id','address')->whereuser_id(session()->get("user_id"))->get();
            return view('user.setting.index',['User' => $User]);
        }else{ return redirect('/dashboard/login/user'); }
    }
    public function Personal(Request $request){
        if (session()->get("user_id")) {
            $Update = DB::table('user')->whereuser_id(session()->get("user_id"))->update(['name' => $request->name,'phone' => $request->phone,'country_id' => $request->country_id,'state_id' => $request->state_id,'city_id' => $request->city_id,'address' => $request->address]);
            if ($request->email != null) {$Update = DB::table('user')->whereuser_id(session()->get("user_id"))->update(['email' => $request->email,'verified' => 0]);}
            return response()->json(['error' => false,'message' => 'Personal Information Saved Successfully']);
        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
    public function Password(Request $request){
        if (session()->get("user_id")) {

            $User = DB::table('user')->select('password')->whereuser_id(session()->get("user_id"))->get();

            if (Hash::check($request->current_password, $User[0]->password)){
                $Update = DB::table('user')->whereuser_id(session()->get("user_id"))->update([
                    'password' => Hash::make($request->new_password)
                ]); return response()->json(['error' => false,'message' => 'Password Changed Successfully']);
            }else{ return response()->json(['error' => true,'message' => 'Invalid Current Password']); }

        }else{ return response()->json(['error' => true,'message' => 'Session Has Been Expired']); }
    }
}
