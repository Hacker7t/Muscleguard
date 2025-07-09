<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class contact_controller extends Controller
{
    public function index(){
        return view('dashboard.contact.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['date','<=',$request->end_date];}
            $contact = DB::table('contact')->select('name','email','subject','message','code','date')->where($Where)->orderby('contact_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.contact.index',['contact'=>$contact]);
        }else{return response()->json(['error' => true]);}
    }
}
