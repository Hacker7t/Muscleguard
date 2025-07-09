<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use File;
class professional_controller extends Controller
{
    public function index(){
        if (session()->get('account_id')) {
            return view('dashboard.professional.index');
        }else{return redirect('/dashboard/login');}
    }
    public function Filter($filter){
        if (session()->get('account_id')) {
            return view('dashboard.professional.index',['filter' => $filter]);
        }else{return redirect('/dashboard/login');}
    }
    public function Get(Request $request){
        if (session()->get('account_id')) {
            $Where = [['professional.availability' , 0]]; $take = $request->take;
            if ($request->search != null) {$Where[] = ['location.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['professional.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['professional.date','<=',$request->end_date];}
            if ($request->approval != 'all') {$Where[] = ['professional.approval','=',$request->approval];}
            if ($request->banned != 0) {$Where[] = ['professional.block','=',$request->banned];}
            if ($take == 0) { $take = DB::table('professional')->where('professional_id',session()->get('professional_id'))->count(); }
            $professional = DB::table('professional')->leftjoin('country','country.country_id','=','professional.country_id')->leftjoin('state','state.state_id','=','professional.state_id')->leftjoin('city','city.city_id','=','professional.city_id')->select('professional.first_name','professional.last_name','professional.status','professional.date','professional.code','professional.url','professional.views','professional.block','professional.approval','professional.email','professional.verified','professional.views','professional.block')->where($Where)->orderby('professional.professional_id',$request->orderby)->take($take)->get();
            return view("dashboard.include.professional.index",['professional'=>$professional]);
        }else{return redirect('/dashboard/login');}
    }
    public function Changes(Request $request){
        if (session()->get("account_id")) {
            $Changes = DB::table('professional')->where('code', '=', $request->code)->update(['status' => $request->status,'approval' => $request->approval]);
            return response()->json(['error'=> false,'message'=> 'Changes Saved']);
        }
    }
    public function Info($code){
        if (session()->get("account_id")) {
            $professional = DB::table('professional')->leftjoin('country','country.country_id','=','professional.country_id')->leftjoin('state','state.state_id','=','professional.state_id')->leftjoin('city','city.city_id','=','professional.city_id')->select('professional.*','professional.code as professional_code','professional.status as professional_status','country.name as country','state.name as state','city.name as city')->where('professional.code',$code)->get();
            $project = DB::table('project')->leftjoin('professional','professional.professional_id','=','project.professional_id')->select('project.name','project.card','project.date','project.code','project.status','project.block','project.approval')->where('professional.code',$code)->orderby('project.project_id','DESC')->get();
            $Images = DB::table('images')->leftjoin('professional','professional.professional_id','=','images.professional_id')->select('images.name','images.code')->where([['images.availability',0],['professional.code',$code]])->orderby('images.image_id','DESC')->get();
            return view("dashboard.professional.info",['professional' => $professional,'project' => $project,'Images' => $Images]);
        }
    }
    public function Approval(Request $request){
        if (session()->get("account_id")) {
            $Approval_Project = DB::table('project')->wherecode($request->code)->update(['approval' => $request->approval]);
            return response()->json(['error'=> false]);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Banned(Request $request){
        if (session()->get("account_id")) {
            $Update_Banned = DB::table('professional')->wherecode($request->code)->update(['block' => 1]);
            return response()->json(['error'=> false,'message'=> 'Professional Parmanently Banned Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    // Delete
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('professional')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'professional Has Beed Deleted By Administration On '.date('F d Y'),'icon' => 'fa-solid fa-trash-can','date' => date('d F'),'task' => 'Delete');
            $professional = DB::table('professional')->wherecode($request->code)->update(['availability' => 1, 'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'professional Deleted Successfully']);
        }
    }
}
