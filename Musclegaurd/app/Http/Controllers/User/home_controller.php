<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class home_controller extends Controller
{
    public function index(){ if (session()->get("user_id")) { 
        $Total_Orders = DB::table('order')->where([['user_id' ,'=', session()->get("user_id")]])->count();
        $Pending_Orders = DB::table('order')->where([['user_id' ,'=', session()->get("user_id")],['status',0]])->count();
        $Processing_Orders = DB::table('order')->where([['user_id' ,'=', session()->get("user_id")],['status',1]])->count();
        $Completed_Orders = DB::table('order')->where([['user_id' ,'=', session()->get("user_id")],['status',2]])->count();
        return view('user.home.index',['Total_Orders' => $Total_Orders,'Pending_Orders' => $Pending_Orders,'Processing_Orders' => $Processing_Orders,'Completed_Orders' => $Completed_Orders]); 
    }else{ return redirect('/login/user'); }}
}
