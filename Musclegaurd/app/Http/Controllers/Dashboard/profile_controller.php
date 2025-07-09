<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
class profile_controller extends Controller
{
    public function index(){
        return view('dashboard.profile.index');
    }
    public function Name(Request $request){
        if (session()->get("account_id")) {
            $Update_Name = DB::table('account')->whereaccount_id(session()->get("account_id"))->update(['name' => $request->name]);
            $request->session()->put('name', $request->name);
            return response()->json(['error'=>false,'message'=>'Name Saved Successfully']);
        }else{ return response()->json(['error'=>true,'message'=>'session expires']); }
    }
    public function Email(Request $request){
        if (session()->get("account_id")) {
            $Get_Account = DB::table('account')->select('email')->whereaccount_id(session()->get("account_id"))->get();
            if ($request->email == $Get_Account[0]->email){ return response()->json(['error'=>false,'message'=>'Allready Saved']);}
            $Check = DB::table('account')->where([['email',$request->email],['account_id','!=',session()->get("account_id")]])->count();
            if ($Check != 0) { return response()->json(['error'=>true,'message'=>'Email Is Allready Taken! Try Anoother One.']);}else{
            $Update_Email = DB::table('account')->whereaccount_id(session()->get("account_id"))->update(['email' => $request->email]);
            $request->session()->forget('account_id'); return response()->json(['error'=>false,'message'=>'Email Saved Successfully','reload'=>true]);}
        }else{ return response()->json(['error'=>true,'message'=>'session expires','reload'=>true]); }
    }
    public function Password(Request $request){
        if (session()->get("account_id")) {
            $Account = DB::table('account')->select('password')->whereaccount_id(session()->get("account_id"))->get();
            if (Hash::check($request->current_password, $Account[0]->password)){
                $Update_Password = DB::table('account')->whereaccount_id(session()->get("account_id"))->update(['password' => Hash::make($request->password)]); $request->session()->forget('account_id'); return response()->json(['error'=>false,'message'=>'Password Saved Successfully']);
            }else{ return response()->json(['error'=>true,'message'=>'Current Password Is Wrong']); }
        }else{ return response()->json(['error'=>true,'message'=>'session expires']); }
    }
}
