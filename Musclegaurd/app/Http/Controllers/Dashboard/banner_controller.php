<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;

class banner_controller extends Controller
{
    public function index(){
        if (DB::table('banner')->count() == 0) { $insert = DB::table('banner')->insert(['date' => date('Y-m-d')]); }
        $Banner = DB::table('banner')->get();
        return view('dashboard.banner.index',['Banner' => $Banner]);
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {

            if ($request->file('main')) {
                $main = $request->file('main');
                foreach($main as $c){
                    $img_ext = $c->getClientOriginalExtension(); $mainNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/banner/main/', $mainNameToStore);
                    $Update_main = DB::table('banner')->wherebanner_id($request->banner_id)->update(['main' => $mainNameToStore]);
                }
            }

            if ($request->file('middle')) {
                $middle = $request->file('middle');
                foreach($middle as $c){
                    $img_ext = $c->getClientOriginalExtension(); $middleNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/banner/middle/', $middleNameToStore);
                    $Update_middle = DB::table('banner')->wherebanner_id($request->banner_id)->update(['middle' => $middleNameToStore]);
                }
            }

            return response()->json(['error' => false,'message'=>'Banners Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
}
