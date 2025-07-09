<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class charges_controller extends Controller
{
    public function Index(){ if (session()->get("vendor_id")) {
        $Vendor = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->select('intra','inter')->get();
        return view('vendor.charges.index',['Vendor' => $Vendor]); }else{ return redirect('/login/vendor'); }}
    public function Save(Request $request){
        if (session()->get("vendor_id")) {
            $Update = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update(['intra' => $request->intra,'inter' => $request->inter]);
            return response()->json(['error' => false,'message'=>'Charges Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
}
