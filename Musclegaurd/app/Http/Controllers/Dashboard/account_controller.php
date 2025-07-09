<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class account_controller extends Controller
{
    public function index(){return view("dashboard.account.index");}
    public function Insert(Request $request){if (session()->get("account_id")) {$Check = DB::table('account')->whereemail($request->email)->count();
    if ($Check != 0) { return response()->json(['error'=>true,'message'=>'Email Is Allready Taken! Try Anoother One.']);
    }else{$insert = DB::table('account')->insert(['name' => $request->name,'email' => $request->email,'password' => Hash::make($request->password),'date' => date('Y-m-d')]); $account_id = DB::getPdo()->lastInsertId();
    $Update_Code = DB::table('account')->where('account_id', '=', $account_id)->update(['code' => md5($account_id)]);
    return response()->json(['error'=>false,'message'=>'Account Has Been Saved Successfully']);}}else{return response()->json(['error'=>true,'message'=>'session expires']);}}
    public function Get(Request $request){$Where = [['account_id', '!=', session()->get("account_id")]];
    if ($request->search != null) {$Where[] = ['name','LIKE','%'.$request->search.'%'];}
    if ($request->start_date != null) {$Where[] = ['date','>=',$request->start_date];}
    if ($request->end_date != null) {$Where[] = ['date','<=',$request->end_date];}
    $Account = DB::table('account')->where($Where)->orderby('account_id',$request->orderby)->take($request->take)->get();
    return view("dashboard.include.account.index",['Account'=>$Account]);}
    public function Status(Request $request){if (session()->get("account_id")) {$check = DB::table("account")->select('status')->wherecode($request->code)->get();if ($check[0]->status == 0) {$De_Active_account = DB::table('account')->wherecode($request->code)->update(['status' => 1]);return response()->json(['error'=> false,'message'=> ' Is De-Active Successfully']);}if ($check[0]->status == 1) {$Active_account = DB::table('account')->wherecode($request->code)->update(['status' => 0]);return response()->json(['error'=> false,'message'=> ' Is Active Successfully']);}}else{return response()->json(['error'=>true,'message'=>'session expires']);}}
    public function Delete(Request $request){ if (session()->get("account_id")) { $account = DB::table('account')->wherecode($request->code)->delete();return response()->json(['error' => false,'message'=>'Account Deleted Successfully']);}else{return response()->json(['error' => true]);}}
    public function Edit($code){if (session()->get("account_id")) {$Edit = DB::table('ACCOUNT')->select('name','email')->wherecode($code)->get();return view('dashboard.ACCOUNT.edit',['Edit'=>$Edit,'code'=>$code]);}else{return response()->json(['error' => true]);}}
    public function Update(Request $request){
        if (session()->get("account_id")) {$Update_Name = DB::table('account')->wherecode($request->code)->update(['name' => $request->name]);

        $Check = DB::table('account')->where([['email',$request->email],['code','!=',$request->code]])->count();if ($Check != 0) { return response()->json(['error'=>true,'message'=>'Email Is Allready Taken! Try Anoother One.']);}else{$Get_Account = DB::table('account')->select('email')->wherecode($request->code)->get();if ($request->email != $Get_Account[0]->email) {$Update_Email = DB::table('account')->wherecode($request->code)->update(['email' => $request->email]);return response()->json(['error'=>true,'message'=>'Account Saved Successfully']);}}

        if ($request->password != null) {$Update_Password = DB::table('account')->wherecode($request->code)->update(['password' => Hash::make($request->password)]);return response()->json(['error'=>true,'message'=>'Account Saved Successfully']);}

        return response()->json(['error'=>false,'message'=>'Account Saved Successfully']);
        }else{ return response()->json(['error'=>true,'message'=>'session expires']); }
    }
}
