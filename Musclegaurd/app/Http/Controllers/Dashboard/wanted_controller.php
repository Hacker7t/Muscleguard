<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class wanted_controller extends Controller
{
    public function index(){
        return view('dashboard.wanted.index');
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['wanted.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['wanted.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['wanted.date','<=',$request->end_date];}
            $wanted = DB::table('wanted')
            ->leftjoin('country','country.country_id','=','wanted.country_id')
            ->leftjoin('state','state.state_id','=','wanted.state_id')
            ->leftjoin('city','city.city_id','=','wanted.city_id')
            ->select('wanted.name','wanted.phone','country.name as country','state.name as state','city.name as city','wanted.message','wanted.code','wanted.date','wanted.location','wanted.purpose','wanted.type_id')->where($Where)->orderby('wanted_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.wanted.index',['wanted'=>$wanted]);
        }else{return response()->json(['error' => true]);}
    }
}
