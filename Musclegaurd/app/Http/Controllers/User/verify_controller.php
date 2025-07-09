<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Mail\user_otp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class verify_controller extends Controller
{
    public function index($code){
    $Check = DB::table('user')->where([['code',$code],['otp','!=',0],['verified',0]])->count();
    if ($Check == 0) { return redirect('/login/user'); }
    else{ return view('user.verify.email',['code' => $code]); }}

    public function Verify_Email(Request $request){
    $Check = DB::table('user')->where([['code',$request->code],['otp',$request->otp],['verified',0]])->count();
    if ($Check == 0) {return response()->json(['error' => true,'message'=>'Wrong Otp']);}
    $Update = DB::table('user')->wherecode($request->code)->update(['otp' => 0,'verified' => 1]);
    // Make Login
    $Account = DB::table('user')->select('name','user_id','email','profile','address','country_id','state_id','city_id','phone')->wherecode($request->code)->get();
    $request->session()->put('user_id', $Account[0]->user_id);
    $request->session()->put('user_name', $Account[0]->name);
    $request->session()->put('user_email', $Account[0]->email);
    $request->session()->put('user_phone', $Account[0]->phone);
    $request->session()->put('user_profile', $Account[0]->profile);
    $request->session()->put('country_id', $Account[0]->country_id);
    $request->session()->put('state_id', $Account[0]->state_id);
    $request->session()->put('city_id', $Account[0]->city_id);
    $request->session()->put('address', $Account[0]->address);
    return response()->json(['error' => false,'message'=>'Verified']); }

    public function Resend(Request $request){
        $Check = DB::table('user')->where([['code',$request->code],['otp','!=',0],['verified',0]])->count();
        if ($Check == 0) { return response()->json(['error' => true,'message'=>'Account Not Found']); }

        $otp = mt_rand(100000, 999999);
        $update = DB::table('user')->wherecode($request->code)->update(['otp' => $otp]);
        $user = DB::table('user')->select('email')->wherecode($request->code)->get();
        if (Str::contains(request()->getHttpHost(), 'com') == true) {
            $detail = ['otp' => $otp]; mail::to($user[0]->email)->send(new user_otp($detail));
        } return response()->json(['error'=>false]);
    }

}
