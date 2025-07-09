<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class vendor_controller extends Controller
{
    public function Index(){
        return view('dashboard.vendor.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [['vendor.availability',0]];
            if ($request->search != null) {$Where[] = ['vendor.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['vendor.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['vendor.date','<=',$request->end_date];}
            $vendor = DB::table('vendor')->leftjoin('shop','shop.vendor_id','=','vendor.vendor_id')->select('vendor.first_name','vendor.last_name','shop.shop_name','vendor.email','vendor.status','vendor.code','vendor.date','vendor.verified','vendor.approval')
            ->where($Where)->orderby('vendor.vendor_id',$request->orderby)->take($request->take)->get();
            return view('dashboard.include.vendor.index',['vendor'=>$vendor]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Delete = DB::table('vendor')->wherecode($request->code)->update(['availability' => 1]);
            return response()->json(['error' => false,'message' => 'Vendor Has Been Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("vendor")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_vendor = DB::table('vendor')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Is De-Active']);}
            if ($check[0]->status == 1) {
                $Active_vendor = DB::table('vendor')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> ' Is Active']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Info($code){
        if (session()->get("account_id")) {
            $Vendor = DB::table('vendor')->leftjoin('shop','shop.vendor_id','=','vendor.vendor_id')->leftjoin('country','country.country_id','=','vendor.country_id')->leftjoin('state','state.state_id','=','vendor.state_id')->leftjoin('city','city.city_id','=','vendor.city_id')->leftjoin('category','category.category_id','=','shop.category_id')->select('vendor.*','shop.*','vendor.code as vendor_code','vendor.status as vendor_status','country.name as country','state.name as state','city.name as city','category.name as category','shop.commission as s_commission','category.commission as c_commission','vendor.type as register_as','vendor.cheque')->where('vendor.code',$code)->get();
            return view("dashboard.vendor.info",['Vendor' => $Vendor]);
        }
    }
    public function Changes(Request $request){
        if (session()->get("account_id")) {
            $Changes = DB::table('vendor')->where('code', '=', $request->code)->update(['status' => $request->status,'approval' => $request->approval]);
            return response()->json(['error'=> false,'message'=> 'Changes Saved']);
        }
    }
    public function Commission_Save(Request $request){
        if (session()->get("account_id")) {
            $Commission = DB::table('shop')->wherecode($request->code)->update(['commission' => $request->commission]);
            return response()->json(['error'=> false,'message'=> 'Commission Updated Successfully']);
        }
    }
    public function Commission(){
        if (session()->get("account_id")) {
            return view("dashboard.vendor.commission.index");
        }else{ return redirect('/dashboard/login'); }
    }

    public function Get_Commission(Request $request){
        $Where = [['vendor.availability',0],['cart.status',3]];
        if ($request->search != null) {$Where[] = ['vendor.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['vendor.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['vendor.date','<=',$request->end_date];}
        $vendor = DB::table('vendor')
        ->leftjoin('shop','shop.vendor_id','=','vendor.vendor_id')
        ->leftjoin('category','category.category_id','=','shop.category_id')
        ->leftjoin('product','product.vendor_id','=','vendor.vendor_id')
        ->leftjoin('cart','cart.product_id','=','product.product_id')
        ->select('product.vendor_id',DB::raw('count(cart.product_id) as sales'),'shop.category_id','shop.commission as s_commission','category.commission as c_commission','category.name as category','vendor.first_name','vendor.last_name','shop.shop_name','vendor.status','vendor.verified','vendor.approval','vendor.code')
        ->groupby('cart.product_id','product.vendor_id','shop.category_id','shop.commission','category.commission','category.name','vendor.first_name','vendor.last_name','shop.shop_name','vendor.status','vendor.verified','vendor.approval','vendor.code')
        ->where($Where)->orderby('vendor.vendor_id',$request->orderby)->take($request->take)->get();
        return view('dashboard.include.vendor.commission.index',['vendor'=>$vendor]);
    }

    public function Commission_Info($code){
        if (session()->get("account_id")) {
            return view("dashboard.vendor.commission.info",['code' => $code]);
        }else{ return redirect('/dashboard/login'); }
    }

    public function Sales_List(Request $request){
        if (session()->get("account_id")) {
            $Where = [['vendor.availability',0],['cart.status',3],['vendor.code',$request->code]];
            $Cart = DB::table('vendor')
            ->leftjoin('product','product.vendor_id','=','vendor.vendor_id')
            ->leftjoin('cart','cart.product_id','=','product.product_id')
            ->select('product.name','product.card','cart.qty','cart.price','cart.discount','product.url','cart.order_id')
            ->where($Where)->orderby('cart.order_id','DESC')->get();

            $Vendor = DB::table('shop')->leftjoin('vendor','vendor.vendor_id','=','shop.vendor_id')->leftjoin('category','category.category_id','=','shop.category_id')->select('shop.commission as s_commission','category.commission as c_commission')->where('vendor.code',$request->code)->get();

            if($Vendor[0]->c_commission != $Vendor[0]->s_commission){ $Commission = $Vendor[0]->s_commission; }else{ $Commission = $Vendor[0]->c_commission; }

            return view("dashboard.include.vendor.commission.sales",['Commission' => $Commission,'Cart' => $Cart]);
        }else{ return response()->json(['error'=> true,'message'=> 'Session Expire']); }
    }

    public function Insert_Commission(Request $request){
        if (session()->get("account_id")) {
            $Vendor = DB::table('vendor')->select('vendor_id')->wherecode($request->code)->get();
            $insert = DB::table('commission')->insert(
                [
                    'vendor_id' => $Vendor[0]->vendor_id,
                    'commission' => $request->commission,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $commission_id = DB::getPdo()->lastInsertId(); $code = md5($commission_id);
            $update = DB::table('commission')->wherecommission_id($commission_id)->update(['code' => $code]);
            return response()->json(['error'=> false,'message'=> 'Commission Saved Successfully']);
        }else{ return response()->json(['error'=> true,'message'=> 'Session Expire']); }
    }

    public function Commission_List(Request $request){
        if(session()->get("account_id")){
            $Commission = DB::table('commission')->leftjoin('vendor', 'vendor.vendor_id', '=', 'commission.vendor_id')->leftjoin('account', 'account.account_id', '=', 'commission.account_id')->select('commission.*','account.name as account')->where([['vendor.code',$request->code]])->get();
            return view("dashboard.include.vendor.commission.list",['Commission' => $Commission]);
        }else{ return response()->json(['error'=> true,'message'=> 'Session Expire']); }
    }
}
