<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use File;
class property_controller extends Controller
{
    public function index(){
        if (session()->get('account_id')) {
            return view('dashboard.property.index');
        }else{return redirect('/dashboard/login');}
    }
    public function Filter($filter){
        if (session()->get('account_id')) {
            return view('dashboard.property.index',['filter' => $filter]);
        }else{return redirect('/dashboard/login');}
    }
    public function Get(Request $request){
        if (session()->get('account_id')) {
            $Where = [['property.availability' , 0]]; $take = $request->take;
            if ($request->search != null) {$Where[] = ['location.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['property.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['property.date','<=',$request->end_date];}
            if ($request->approval != 'all') {$Where[] = ['property.approval','=',$request->approval];}
            if ($request->banned != 0) {$Where[] = ['property.block','=',$request->banned];}
            if ($take == 0) { $take = DB::table('property')->where('vendor_id',session()->get('vendor_id'))->count(); }
            $property = DB::table('property')->leftjoin('vendor','vendor.vendor_id','=','property.vendor_id')->leftjoin('country','country.country_id','=','property.country_id')->leftjoin('shop','shop.vendor_id','=','property.vendor_id')->leftjoin('state','state.state_id','=','property.state_id')->leftjoin('city','city.city_id','=','property.city_id')->select('property.status','property.date','property.code','property.card','property.url','property.views','property.block','property.approval','country.name as country','state.name as state','city.name as city','property.location','property.price','vendor.first_name as vendor_first_name','vendor.last_name as vendor_last_name','shop.shop_name as shop','property.area','property.area_unit','property.type_id','property.purpose','property.featured')->where($Where)->orderby('property.property_id',$request->orderby)->take($take)->get();
            return view("dashboard.include.property.index",['property'=>$property]);
        }else{return redirect('/dashboard/login');}
    }
    public function Approval(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table("property")->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            if ($request->approval == 1) {
                $Activity[] = array('description' => 'property Approved By Administration On '.date('F d Y'),'icon' => 'fa-regular fa-face-smile','date' => date('d F'),'task' => 'Approved');
            }else{
                $Activity[] = array('description' => 'property Reject By Administration On '.date('F d Y'),'icon' => 'fa-regular fa-face-sad-tear','date' => date('d F'),'task' => 'Reject');
            }

            $Approval_property = DB::table('property')->wherecode($request->code)->update(['approval' => $request->approval,'activity' => json_encode($Activity)]);
            return response()->json(['error'=> false]);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Featured(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("property")->select('featured')->wherecode($request->code)->get();
            if ($check[0]->featured == 0) {
                $De_Active_property = DB::table('property')->wherecode($request->code)->update(['featured' => 1]);
                return response()->json(['error'=> false,'message'=> ' Property Is Become Featured']);}
            if ($check[0]->featured == 1) {
                $Active_property = DB::table('property')->wherecode($request->code)->update(['featured' => 0]);
                return response()->json(['error'=> false,'message'=> 'Property Is Become Un Featured']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('property')->select('purpose','type_id','condition','price','country_id','state_id','city_id','location','bedroom','bathroom','area','area_unit','description','card','id','code')->wherecode($code)->get();
            return view("dashboard.property.edit",['Edit'=>$Edit,'code'=>$code]);
        }else{ return redirect('/dashboard/login');}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));

            $Get = DB::table('property')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Property Edit From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-pen-to-square','date' => date('d F'),'task' => 'Editing');

            $Update = DB::table('property')->wherecode($request->code)->update(
                [
                    'purpose' => $request->purpose,
                    'type_id' => $request->type_id,
                    'condition' => $request->condition,
                    'price' => $request->price,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'location' => $request->location,
                    'bathroom' => $request->bathroom,
                    'bedroom' => $request->bedroom,
                    'area' => $request->area,
                    'area_unit' => $request->area_unit,
                    'description' => $request->description,
                    'url' => $url,
                    'activity' => json_encode($Activity),
                ]);

            if ($request->file('card')) {
                $Get_Card = DB::table('property')->select('card')->wherecode($request->code)->get();
                $Card_path = public_path().'/uploads/property/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/property/card/', $imgNameToStore);
                $Update_Images = DB::table('property')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Property Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    // Images
    public function Property_Images($code){
        return view("dashboard.property.images",['code'=>$code]);
    }
    public function Property_Upload_Images(Request $request){
        if (session()->get("account_id")) {

            $property = DB::table('property')->select('images','activity')->wherecode($request->code)->get();
            $Files_Name = json_decode($property[0]->images);
            $images = $request->file('images');
            foreach($images as $i){ $img_ext = $i->getClientOriginalExtension();
            $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext));
            $i->move(public_path().'/uploads/property/images/', $imgNameToStore); $Files_Name[] = $imgNameToStore;}

            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Add Some Images From Administration On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Images Creation');

            $Update_Code = DB::table('property')->wherecode($request->code)->update(['images' => json_encode($Files_Name),'activity' => json_encode($Activity)]);
            return response()->json(['error'=>false,'message'=>'Images Uploaded Successfully']);
        }else{return response()->json(['error'=>'true','message'=>'session expire']);}
    }
    public function Property_Delete_Images(Request $request){
        if (session()->get("account_id")) {

            $property = DB::table("property")->select('images','activity')->wherecode($request->code)->get();
            $ALL = json_decode($property[0]->images); $count = count($ALL);
            if ($count > 1) {
                foreach($ALL as $A){
                    if ($A != $request->image) {
                        $New[] = $A;
                    }else {
                        $Card_path = public_path().'/uploads/property/images/'.$request->image; if (File::exists($Card_path)) { unlink($Card_path);}
                    }
                }
            }else{ $New = []; }
            $Eecode = json_encode($New);

            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Delete Some Images From Administration On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Images Delete');

            $Update_Images = DB::table('property')->wherecode($request->code)->update([
                'images' => $Eecode,
                'activity' => json_encode($Activity)
            ]);

            return response()->json(['error' => false,'message'=>'Images Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Property_Get_Images(Request $request){
        if (session()->get("account_id")) {
            $Property = DB::table('property')->select('images')->wherecode($request->code)->get();
            return view('dashboard.include.property.images',['Property'=>$Property]);
        }else{return redirect('/dashboard/login');}
    }
    public function Banned(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table("property")->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Property Parmanently Banned By Administration On '.date('F d Y'),'icon' => 'fa-solid fa-ban','date' => date('d F'),'task' => 'Banned');
            $Update_Banned = DB::table('property')->wherecode($request->code)->update(['block' => 1,'activity' => json_encode($Activity)]);
            return response()->json(['error'=> false,'message'=> 'Property Parmanently Banned Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    // Note
    public function Property_Note($code){
        return view("dashboard.property.note",['code' => $code]);
    }
    public function Insert_Note(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('property')->select('note','activity')->wherecode($request->code)->get();
            $Note = json_decode($Get[0]->note);
            $Note[] = array('note' => $request->note);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Some Note From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-note-sticky','date' => date('d F'),'task' => 'Note');

            $Update = DB::table('property')->wherecode($request->code)->update(['note'=>json_encode($Note),'activity'=>json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Note Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Get_Note($code){
        if (session()->get("account_id")) {
            $property = DB::table('property')->select('note')->wherecode($code)->get();
            return response()->json(['note' => array_reverse(json_decode($property[0]->note))]);
        }else{return response()->json(['error'=>'logout']);}
    }
    // Delete
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('property')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Property Has Beed Deleted By Administration On '.date('F d Y'),'icon' => 'fa-solid fa-trash-can','date' => date('d F'),'task' => 'Delete');
            $property = DB::table('property')->wherecode($request->code)->update(['availability' => 1, 'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Property Deleted Successfully']);
        }
    }
}
