<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\account_otp;
use Illuminate\Support\Str;

class forgot_controller extends Controller
{
    public function index(){return view("dashboard.forgot.index");}
    public function Find(Request $request){
    $Find = DB::table('account')->whereemail($request->email)->count();
    if ($Find == 0) { return response()->json(['error'=>true,'message'=>'Wrong Email Try Another One!']); }
    else {
        $Account = DB::table('account')->select('code')->whereemail($request->email)->get();
        $otp = mt_rand(100000, 999999); $Update_OTP = DB::table('account')->wherecode($Account[0]->code)->update(['otp' => $otp,'status' => 1]);
        if (Str::contains(request()->getHttpHost(), 'com') == true) {
            $detail = ['otp' => $otp]; mail::to($request->email)->send(new account_otp($detail));
        }return response()->json(['error'=>false,'code'=>$Account[0]->code]);
    }
    return view("dashboard.forgot.index");}
    public function Verification($code){
        return view("dashboard.forgot.verification",['code'=>$code]);
    }
    public function Verified(Request $request){
        $Count = DB::table('account')->where([['code', '=', $request->code],['otp', '=', $request->otp]])->count();
        if ($Count == 0) { return response()->json(['error'=>true,'message'=>'Wrong OTP']);
        }else{ return response()->json(['error'=>false,'code'=>$request->code]); }
    }
    public function Change($code){
        $Count = DB::table('account')->where([['code', '=', $code],['status', '=', 1]])->count();
        if ($Count != 0) {
            return view("dashboard.forgot.change",['code'=>$code]);
        }else{return redirect('/dashboard/login');}
    }
    public function Change_Password(Request $request){
        $Count = DB::table('account')->where([['code', '=', $request->code],['status', '=', 1]])->count();
        if ($Count != 0) {
            $Update_Password = DB::table('account')->wherecode($request->code)->update(['password' => Hash::make($request->password),'status'=>0]);
            return response()->json(['error'=>false,'message'=>'Password Changed Successfully']);
        }else{return redirect('/dashboard/login');}
    }
}
