<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class brand_controller extends Controller
{
    public function index(){
        return view('dashboard.brand.index');
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
            $id = time();
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $insert = DB::table('brand')->insert(
                [
                    'name' => $request->name,
                    'url' => $url,
                    'id' => $id,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $brand_id = DB::getPdo()->lastInsertId(); $code = md5($brand_id);
            $update = DB::table('brand')->wherebrand_id($brand_id)->update(['code' => $code]);

            if ($request->file('logo')) {
                $logo = $request->file('logo');
                foreach($logo as $c){
                    $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/brand/logo/', $imgNameToStore);
                    $Update_logo = DB::table('brand')->wherebrand_id($brand_id)->update(['logo' => $imgNameToStore]);
                }
            }

            return response()->json(['error' => false,'message'=>'brand Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Category(){
        $category = DB::table('category')->select('category_id','name')->where([['status',0],['parent_id',0]])->get();
        return response()->json(['category' => $category]);
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['brand.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['brand.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['brand.date','<=',$request->end_date];}
            $brand = DB::table('brand')->leftjoin('account','account.account_id','=','brand.account_id')->select('brand.name','brand.status','brand.code','brand.date','brand.brand_id','brand.logo','account.name as account')->where($Where)->orderby('brand.brand_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.brand.index',['brand'=>$brand]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get_logo = DB::table('brand')->select('logo')->wherecode($request->code)->get();
            $logo_path = public_path().'/uploads/brand/logo/'.$Get_logo[0]->logo; if (File::exists($logo_path)) { unlink($logo_path);}
            $brand = DB::table('brand')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'brand Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('brand')->select('name','logo','id')->where('code','=',$code)->get();
            return view('dashboard.brand.edit',['Edit'=>$Edit,'code'=>$code]);
        }else{return response()->json(['error' => true]);}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $update = DB::table('brand')->wherecode($request->code)->update([
                'name' => $request->name,
                'url' => $url,
            ]);

            if ($request->file('logo')) {
                $Get_logo = DB::table('brand')->select('logo')->wherecode($request->code)->get();
                $logo_path = public_path().'/uploads/brand/logo/'.$Get_logo[0]->logo; if (File::exists($logo_path)) { unlink($logo_path);}
                $logo = $request->file('logo'); foreach($logo as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/brand/logo/', $imgNameToStore);
                $Update_Images = DB::table('brand')->wherecode($request->code)->update(['logo' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("brand")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_brand = DB::table('brand')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' brand Is De-Active Successfully']);}
            if ($check[0]->status == 1) {
                $Active_brand = DB::table('brand')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> 'brand Is Active Successfully']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
}
