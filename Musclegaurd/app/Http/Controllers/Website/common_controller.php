<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class common_controller extends Controller
{
    public function Category(){
        $category = DB::table('category')->select('name','url','category_id')->where([['status',0],['parent_id',0]])->get();
        return response()->json(['category' => $category]);
    }
    public function Sub_Category($category_id){
        $sub_category = DB::table('category')->select('name','url','category_id')->where([['status',0],['parent_id',$category_id]])->get();
        return response()->json(['sub_category' => $sub_category]);
    }
    public function Country(){
        $country = DB::table('country')->select('country_id','name')->wherestatus(0)->get();
        return response()->json(['error' => false,'country' => $country]);
    }
    public function State($country_id){
        $state = DB::table('state')->select('state_id','name')->where([['country_id',$country_id],['status',0]])->get();
        return response()->json(['error' => false,'state' => $state]);
    }
    public function City($state_id){
        $city = DB::table('city')->select('city_id','name')->where([['state_id',$state_id],['status',0]])->get();
        return response()->json(['error' => false,'city' => $city]);
    }
    public function Shipment($city_id){
        $city = DB::table('city')->select('charges')->where([['city_id',$city_id],['status',0]])->get();
        if (count($city) != 0) { $charges = $city[0]->charges; }else{ $charges = 00.00; }
        return response()->json(['error' => false,'charges' => $charges]);
    }
    public function Announcement(){
        $Announcement = DB::table('announcement')->select('text')->get();
        return response()->json(['Announcement' => $Announcement]);
    }

    public function team() {
        return view('website.team.index');
    }
}
