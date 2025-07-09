<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Mail\vendor_otp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class login_controller extends Controller
{
    public function index(){
        if (!session()->get("vendor_id")) {
            return view('vendor.login.index');
        }else{ return redirect('/dashboard/vendor/home'); }
    }
    public function Check(Request $request){
    if (!session()->get("vendor_id")) {
        $Check = DB::table('vendor')->whereemail($request->email)->count();
        if ($Check != 0) {
            $vendor = DB::table('vendor')->select('password','code','status','verified')->whereemail($request->email)->get();
            if ($vendor[0]->status == 1) {
                return response()->json(['error'=>true,'message'=>'Account Has Been Blocked']);
            }else{
                if (Hash::check($request->password, $vendor[0]->password)){
                    if ($vendor[0]->verified == 0) {
                        $otp = mt_rand(100000, 999999);
                        $Update_OTP = DB::table('vendor')->wherecode($vendor[0]->code)->update(['otp' => $otp]);
                        if (Str::contains(request()->getHttpHost(), 'com') == true OR Str::contains(request()->getHttpHost(), 'au') == true) {
                            $detail = ['otp' => $otp]; mail::to($request->email)->send(new vendor_otp($detail));
                        } return response()->json(['error'=>true,'code'=>$vendor[0]->code,'message'=>'Account Not Verified']);
                    }else{
                        $Account = DB::table('vendor')->select('first_name','last_name','vendor_id','email','profile','approval','type')->wherecode($vendor[0]->code)->get();
                        $request->session()->put('vendor_id', $Account[0]->vendor_id);
                        $request->session()->put('vendor_name', $Account[0]->first_name.' '.$Account[0]->last_name);
                        $request->session()->put('vendor_email', $Account[0]->email);
                        $request->session()->put('vendor_profile', $Account[0]->profile);
                        $request->session()->put('vendor_approval', $Account[0]->approval);
                        $request->session()->put('vendor_type', $Account[0]->type);
                        return response()->json(['error'=>false]);
                    }
                }else{ return response()->json(['error'=>true,'message'=>'Wrong Password Try Another One!']);}
            }
        }else{ return response()->json(['error'=>true,'message'=>'Wrong Email Try Another One!']); }
    }else{ return response()->json(['error'=>false]); }}

    public function logout(Request $request){
        $request->session()->forget('vendor_id');
        $request->session()->forget('vendor_name');
        $request->session()->forget('vendor_email');
        $request->session()->forget('vendor_profile');
        $request->session()->forget('vendor_approval');
        $request->session()->forget('vendor_type');
        return redirect("/login/vendor");
    }
}
