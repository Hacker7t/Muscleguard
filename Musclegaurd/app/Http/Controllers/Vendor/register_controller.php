<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class register_controller extends Controller
{
    public function index(){ return view('vendor.register.index'); }
    public function Insert(Request $request){
        if (!session()->get("vendor_id")) {
            $Check_Vendor_Email = DB::table('vendor')->whereemail($request->email)->count();
            if ($Check_Vendor_Email != 0) { return response()->json(['error' => true,'message' => 'Personal Email Is Allready Taken!']); }

            $Check_Vendor_Phone = DB::table('vendor')->wherephone($request->phone)->count();
            if ($Check_Vendor_Phone != 0) { return response()->json(['error' => true,'message' => 'Personal Phone Number Is Allready Taken!']); }

            $Check_Shop_Email = DB::table('shop')->wherebusiness_email($request->business_email)->count();
            if ($Check_Shop_Email != 0) { return response()->json(['error' => true,'message' => 'Business Email Is Allready Taken!']); }

            $Check_Shop_Phone = DB::table('shop')->wherebusiness_phone($request->business_phone)->count();
            if ($Check_Shop_Phone != 0) { return response()->json(['error' => true,'message' => 'Business Phone Number Is Allready Taken!']); }

            $Bank = json_encode(array('bank_id' => $request->bank_id,'iban' => $request->iban,'title' => $request->title));
            $Insert_Vendor = DB::table('vendor')->insert(
                [
                    // 'type' => $request->type,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'cnic' => $request->cnic,
                    'password' => Hash::make($request->password),
                    'bank' => $Bank,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $vendor_id = DB::getPdo()->lastInsertId(); $Update = DB::table('vendor')->wherevendor_id($vendor_id)->update(['code' => md5($vendor_id)]);

            if ($request->file('front')) { $front = $request->file('front'); foreach($front as $l){ $front_ext = $l->getClientOriginalExtension();
            $frontNameToStore = strtolower(str_replace(' ', '-',$vendor_id.'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$front_ext)); $l->move(public_path().'/uploads/vendor/cnic/front/', $frontNameToStore); $Update_front = DB::table('vendor')->wherevendor_id($vendor_id)->update(['front' => $frontNameToStore]);}}

            if ($request->file('back')) { $back = $request->file('back'); foreach($back as $l){ $back_ext = $l->getClientOriginalExtension();
            $backNameToStore = strtolower(str_replace(' ', '-',$vendor_id.'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$back_ext)); $l->move(public_path().'/uploads/vendor/cnic/back/', $backNameToStore); $Update_back = DB::table('vendor')->wherevendor_id($vendor_id)->update(['back' => $backNameToStore]);}}

            if ($request->file('cheque')) { $cheque = $request->file('cheque'); foreach($cheque as $l){ $cheque_ext = $l->getClientOriginalExtension();
            $chequeNameToStore = strtolower(str_replace(' ', '-',$vendor_id.'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$cheque_ext)); $l->move(public_path().'/uploads/vendor/cheque/', $chequeNameToStore); $Update_cheque = DB::table('vendor')->wherevendor_id($vendor_id)->update(['cheque' => $chequeNameToStore]);}}

            $Category = DB::table('category')->select('commission')->wherecategory_id($request->category_id)->get();
            $Insert_Shop = DB::table('shop')->insert(
                [
                    'vendor_id' => $vendor_id,
                    'type_id' => $request->type_id,
                    'category_id' => $request->category_id,
                    'commission' => $Category[0]->commission,
                    'shop_name' => $request->shop_name,
                    'shop_overview' => $request->shop_overview,
                    'business_email' => $request->business_email,
                    'business_phone' => $request->business_phone,
                    'business_address' => $request->business_address,
                    'member' => $request->member,
                    'ntn' => $request->ntn,
                    'stn' => $request->stn,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $shop_id = DB::getPdo()->lastInsertId(); $Update = DB::table('shop')->whereshop_id($shop_id)->update(['code' => md5($vendor_id)]);

            if ($request->file('logo')) { $logo = $request->file('logo'); foreach($logo as $l){ $logo_ext = $l->getClientOriginalExtension();
            $logoNameToStore = strtolower(str_replace(' ', '-',$shop_id.'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$logo_ext)); $l->move(public_path().'/uploads/vendor/shop/logo/', $logoNameToStore); $Update_logo = DB::table('shop')->whereshop_id($shop_id)->update(['logo' => $logoNameToStore]);}}

            if ($request->file('banner')) { $banner = $request->file('banner'); foreach($banner as $b){ $banner_ext = $b->getClientOriginalExtension();
            $bannerNameToStore = strtolower(str_replace(' ', '-',$shop_id.'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$banner_ext)); $b->move(public_path().'/uploads/vendor/shop/banner/', $bannerNameToStore); $Update_banner = DB::table('shop')->whereshop_id($shop_id)->update(['banner' => $bannerNameToStore]);}}

            return response()->json(['error' => false]);

        }else{ return response()->json(['error' => false]); }
    }
}
