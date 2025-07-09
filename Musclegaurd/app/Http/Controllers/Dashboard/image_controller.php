<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class image_controller extends Controller
{
    public function index(){return view('dashboard.image.index');}
    public function Get(){
        if (session()->get("account_id")) {
            $Image = DB::table('image')->select('name','code')->orderby('image_id','DESC')->get();
            return view("dashboard.include.image.index",['Image'=>$Image]);
        }else{return response()->json(['error' => true,'message'=>'session expire']);}
    }
    public function Insert(Request $request){
        if ($request->images) {
            $images = $request->file('images');
            foreach($images as $i){
                $img_ext = $i->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $i->move(public_path().'/uploads/image/', $imgNameToStore);
                $Insert = DB::table('image')->insert(['name' => $imgNameToStore,'account_id' => session()->get('account_id'),'date' => date('Y-m-d'),'time' => date('H:i:s')]);
                $image_id = DB::getPdo()->lastInsertId(); $code = md5($image_id);
                $Update_Code = DB::table('image')->whereimage_id($image_id)->update(['code' => $code]);
            }
        }
        return response()->json(['error' => false,'message'=>'Images Saved Successfully']);
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get_Image = DB::table('image')->select('name')->wherecode($request->code)->get();
            $Image_path = public_path().'/uploads/image/'.$Get_Image[0]->name; if (File::exists($Image_path)) { unlink($Image_path);}
            $Delete = DB::table('image')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'Image Delete Successfully']);
        }else{return response()->json(['error' => true,'message'=>'session expire']);}
    }
}
