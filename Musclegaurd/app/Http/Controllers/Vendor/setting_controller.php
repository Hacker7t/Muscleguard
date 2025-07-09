<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
use Illuminate\Support\Facades\Hash;

class setting_controller extends Controller
{
    public function Index(){ if (session()->get("vendor_id")) {
        $Vendor = DB::table('vendor')->select('first_name','last_name','email','phone','address','cnic','bank','front','back','country_id','state_id','city_id')->wherevendor_id(session()->get("vendor_id"))->get();
        $Shop = DB::table('shop')->select('shop_name','shop_overview','business_email','business_phone','business_address','ntn','stn','banner','logo','type_id','category_id')->wherevendor_id(session()->get("vendor_id"))->get();
        return view('vendor.setting.index',['Vendor' => $Vendor,'Shop' => $Shop]);
    }else{ return redirect('/login/vendor'); }}

    public function Personal(Request $request){
        if (session()->get("vendor_id")) {
            $Personal = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update(['first_name' => $request->first_name,'last_name' => $request->last_name,'email' => $request->email,'phone' => $request->phone,'address' => $request->address,'cnic' => $request->cnic]);
            return response()->json(['error' => false,'message' => 'Personal Information Updated']);
        }else{ return response()->json(['error' => true]); }
    }

    public function Bank(Request $request){
        if (session()->get("vendor_id")) {
            $Encode = json_encode(array('bank_id' => $request->bank_id,'iban' => $request->iban,'title' => $request->title));
            $Bank = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update(['bank' => $Encode]);
            return response()->json(['error' => false,'message' => 'Bank Information Updated']);
        }else{ return response()->json(['error' => true]); }
    }

    public function Store(Request $request){
        if (session()->get("vendor_id")) {
            $Bank = DB::table('shop')->wherevendor_id(session()->get("vendor_id"))->update([
                'type_id' => $request->type_id,
                'category_id' => $request->category_id,
                'shop_name' => $request->shop_name,
                'shop_overview' => $request->shop_overview,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'ntn' => $request->ntn,
                'stn' => $request->stn,
            ]);

            if ($request->file('logo')) {
            $Get_Logo = DB::table('shop')->select('logo')->wherevendor_id(session()->get("vendor_id"))->get();
            $Logo_path = public_path().'/uploads/vendor/shop/logo/'.$Get_Logo[0]->logo; if (File::exists($Logo_path)) { unlink($Logo_path);}
            $logo = $request->file('logo'); foreach($logo as $l){ $logo_ext = $l->getClientOriginalExtension();
            $logoNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$logo_ext)); $l->move(public_path().'/uploads/vendor/shop/logo/', $logoNameToStore); $Update_logo = DB::table('shop')->wherevendor_id(session()->get("vendor_id"))->update(['logo' => $logoNameToStore]);}}

            if ($request->file('banner')) {
            $Get_Banner = DB::table('shop')->select('banner')->wherevendor_id(session()->get("vendor_id"))->get();
            $Banner_path = public_path().'/uploads/vendor/shop/banner/'.$Get_Banner[0]->banner; if (File::exists($Banner_path)) { unlink($Banner_path);}
            $banner = $request->file('banner'); foreach($banner as $b){ $banner_ext = $b->getClientOriginalExtension();
            $bannerNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$banner_ext)); $b->move(public_path().'/uploads/vendor/shop/banner/', $bannerNameToStore); $Update_banner = DB::table('shop')->wherevendor_id(session()->get("vendor_id"))->update(['banner' => $bannerNameToStore]);}}


            return response()->json(['error' => false,'message' => 'Bank Information Updated']);
        }else{ return response()->json(['error' => true]); }
    }
    public function Attachments(Request $request){
        if (session()->get("vendor_id")) {

            if ($request->file('front')) {
            $Get_Front = DB::table('vendor')->select('front')->wherevendor_id(session()->get("vendor_id"))->get();
            $Front_path = public_path().'/uploads/vendor/cnic/front/'.$Get_Front[0]->front; if (File::exists($Front_path)) { unlink($Front_path);}
            $Front = $request->file('front'); foreach($Front as $l){ $Front_ext = $l->getClientOriginalExtension();
            $FrontNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$Front_ext)); $l->move(public_path().'/uploads/vendor/cnic/front/', $FrontNameToStore); $Update_Front = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update(['front' => $FrontNameToStore]);}}

            if ($request->file('back')) {
            $Get_Back = DB::table('vendor')->select('back')->wherevendor_id(session()->get("vendor_id"))->get();
            $Back_path = public_path().'/uploads/vendor/cnic/back/'.$Get_Back[0]->back; if (File::exists($Back_path)) { unlink($Back_path);}
            $Back = $request->file('back'); foreach($Back as $l){ $Back_ext = $l->getClientOriginalExtension();
            $BackNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$Back_ext)); $l->move(public_path().'/uploads/vendor/cnic/back/', $BackNameToStore); $Update_Back = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update(['back' => $BackNameToStore]);}}

            return response()->json(['error' => false,'message' => 'Attachments Information Updated']);
        }else{ return response()->json(['error' => true]); }
    }
    public function Password(Request $request){
        if (session()->get("vendor_id")) {

            $Vendor = DB::table('vendor')->select('password')->wherevendor_id(session()->get("vendor_id"))->get();
            if (Hash::check($request->current_password, $Vendor[0]->password)){
                $Update = DB::table('vendor')->wherevendor_id(session()->get("vendor_id"))->update([
                    'password' => Hash::make($request->new_password)
                ]); return response()->json(['error' => false,'message' => 'Password Changed Successfully']);
            }else{ return response()->json(['error' => true,'message' => 'Invalid Current Password']); }

        }else{ return response()->json(['error' => true]); }
    }
}
