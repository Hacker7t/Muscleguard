<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Mail\user_otp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class register_controller extends Controller
{
    public function index(){ return view('user.register.index'); }
    public function Insert(Request $request){
        if (!session()->get("user_id")) {
            $Check_user_Email = DB::table('user')->whereemail($request->email)->count();
            if ($Check_user_Email != 0) { return response()->json(['error' => true,'message' => 'Email Is Allready Taken!']); }

            $Insert_user = DB::table('user')->insert(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $user_id = DB::getPdo()->lastInsertId(); $Update = DB::table('user')->whereuser_id($user_id)->update(['code' => md5($user_id)]);
            return response()->json(['error' => false]);

        }else{ return response()->json(['error' => false]); }
    }
}
