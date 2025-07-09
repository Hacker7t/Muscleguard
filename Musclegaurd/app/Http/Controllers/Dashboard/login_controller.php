<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\account_otp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class login_controller extends Controller
{
    public function Check_Session(Request $request){
        return session()->get("account_id");
    }
    public function login_page(Request $request){
        if (session()->get("account_id")) {return redirect("/dashboard/home");}else{return view("dashboard.login.index");}
    }
    public function login_check(Request $request){
        if (DB::table('account')->count() == 0) {
        $insert = DB::table('account')->insert(['name' => 'Dummy Account','email' => 'dummy@gmail.com','password' => Hash::make(123),'date' => date('Y-m-d')]); $account_id = DB::getPdo()->lastInsertId();
        $Update_Code = DB::table('account')->whereaccount_id($account_id)->update(['code' => md5($account_id),'otp'=>'123456']);}
        if (!session()->get("account_id")) {
            $Check = DB::table('account')->whereemail($request->email)->count();
            if ($Check != 0) {
                $Account = DB::table('account')->select('password','code','status')->whereemail($request->email)->get();
                if ($Account[0]->status == 1) {
                    return response()->json(['error'=>true,'message'=>'Account Has Been Blocked']);
                }else{
                    if (Hash::check($request->password, $Account[0]->password)){
                        if ($request->email != 'dummy@gmail.com') {$otp = mt_rand(100000, 999999);}else{$otp = 123456;}
                        $Update_OTP = DB::table('account')->wherecode($Account[0]->code)->update(['otp' => $otp]);
                        if (Str::contains(request()->getHttpHost(), 'com') == true) {
                            $detail = ['otp' => $otp]; mail::to($request->email)->send(new account_otp($detail));
                        } return response()->json(['error'=>false,'code'=>$Account[0]->code]);
                    }else{ return response()->json(['error'=>true,'message'=>'Wrong Password Try Another One!']);}
                }
            }else{ return response()->json(['error'=>true,'message'=>'Wrong Email Try Another One!']); }
        }
    }
    public function otp_verification_page($code){if (session()->get("account_id")) {return redirect("/dashboard/home");}else{return view("dashboard.login.verification",['code'=>$code]);}}

    public function resend_otp(Request $request){
        if (session()->get("account_id")) {
            return redirect("/dashboard/home");
        }else{
            $otp = mt_rand(100000, 999999);
            $update = DB::table('account')->where('code', '=', $request->code)->update(['otp' => $otp]);
            $account = DB::table('account')->select('email')->where('code','=',$request->code)->get();
            if (Str::contains(request()->getHttpHost(), 'com') == true) {
                $detail = ['otp' => $otp]; mail::to($account[0]->email)->send(new account_otp($detail));
            }return response()->json(['error'=>false]);
        }
    }
    public function otp_verification(Request $request){
        if (session()->get("account_id")) {
            return response()->json(['error'=>false]);
        }else{
            $account = DB::table('account')->select('account_id','email','name')->where([['code', '=', $request->code],['otp', '=', $request->otp]])->get();
            $count = count($account);
            if ($count == 0) {
                return response()->json(['error'=>true]);
            }else{
                $update = DB::table('account')->where('code', '=', $request->code)->update(['otp' => 0]);
                $request->session()->put('account_id', $account[0]->account_id);
                $request->session()->put('name', $account[0]->name);
                $request->session()->put('email', $account[0]->email);
                return response()->json(['error'=>false]);
            }
        }
    }
    public function logout(Request $request){
        $request->session()->forget('account_id');
        $request->session()->forget('name');
        $request->session()->forget('email');
        return redirect("/dashboard/login");
    }
}
