<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class verify_controller extends Controller
{
    public function index($code){
    $Check = DB::table('vendor')->where([['code',$code],['otp','!=',0],['verified',0]])->count();
    if ($Check == 0) { return redirect('/login/vendor'); }
    else{ return view('vendor.verify.email',['code' => $code]); }}

    public function Verify_Email(Request $request){
    $Check = DB::table('vendor')->where([['code',$request->code],['otp',$request->otp],['verified',0]])->count();
    if ($Check == 0) {return response()->json(['error' => true,'message'=>'Wrong Otp']);}
    $Update = DB::table('vendor')->wherecode($request->code)->update(['otp' => 0,'verified' => 1]);
    // Make Login
    $Account = DB::table('vendor')->select('first_name','last_name','vendor_id','email','profile','approval','type')->wherecode($request->code)->get();
    $request->session()->put('vendor_id', $Account[0]->vendor_id);
    $request->session()->put('vendor_name', $Account[0]->first_name.' '.$Account[0]->last_name);
    $request->session()->put('vendor_email', $Account[0]->email);
    $request->session()->put('vendor_profile', $Account[0]->profile);
    $request->session()->put('vendor_approval', $Account[0]->approval);
    $request->session()->put('vendor_type', $Account[0]->type);
    return response()->json(['error' => false,'message'=>'Verified']); }

    public function Resend(Request $request){
        $Check = DB::table('vendor')->where([['code',$request->code],['otp','!=',0],['verified',0]])->count();
        if ($Check == 0) { return response()->json(['error' => true,'message'=>'Account Not Found']); }

        $otp = mt_rand(100000, 999999);
        $update = DB::table('vendor')->wherecode($request->code)->update(['otp' => $otp]);
        $vendor = DB::table('vendor')->select('email')->wherecode($request->code)->get();
        if (Str::contains(request()->getHttpHost(), 'com') == true) {
            $detail = ['otp' => $otp]; mail::to($vendor[0]->email)->send(new vendor_otp($detail));
        } return response()->json(['error'=>false]);
    }

}
