<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class contact_controller extends Controller
{
    public function Index(){
        return view("website.contact.index");
    }
    public function Insert(Request $request){
        $insert = DB::table('contact')->insert(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'message' => $request->message,
                'subject' => $request->subject,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            ]);

        $contact_id = DB::getPdo()->lastInsertId(); $update = DB::table('contact')->wherecontact_id($contact_id)->update(['code' => md5($contact_id)]); return response()->json(['error' => false,'message'=>'Message Submit Successfully']);
    }
}
