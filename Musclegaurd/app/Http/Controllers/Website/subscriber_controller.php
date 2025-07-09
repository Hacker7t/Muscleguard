<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class subscriber_controller extends Controller
{
    public function Insert(Request $request){
        $Check = DB::table('subscriber')->whereemail($request->email)->count();
        if ($Check == 0) {
            $insert = DB::table('subscriber')->insert(['email' => $request->email,'date' => date('Y-m-d'),'time' => date('H:i:s'),]);
            $subscriber_id = DB::getPdo()->lastInsertId(); $update = DB::table('subscriber')->wheresubscriber_id($subscriber_id)->update(['code' => md5($subscriber_id)]);
            return response()->json(['error' => false,'message'=>'Subscribe Successfully']);
        }else{ return response()->json(['error' => true,'message'=>'Your Are Allready Subscribe']); }

    }
}
