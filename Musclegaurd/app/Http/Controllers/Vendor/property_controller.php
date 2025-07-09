<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use File;

class property_controller extends Controller
{
    // Craete
    public function Index(){ if (session()->get("vendor_id")) { return view('vendor.property.index'); }else{ return redirect('/login/vendor'); }}
    public function Insert(Request $request){
    if (session()->get('vendor_id')) {
        $Shop = DB::table('vendor')->leftjoin('shop','shop.vendor_id','=','vendor.vendor_id')->select('shop.shop_id')->where('shop.vendor_id',session()->get('vendor_id'))->get();
        $id = time();
        $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));

        $insert = DB::table('property')->insert(['purpose' => $request->purpose,'type_id' => $request->type_id,'condition' => $request->condition,'price' => $request->price,'country_id' => $request->country_id,'state_id' => $request->state_id,'city_id' => $request->city_id,'location' => $request->location,'bathroom' => $request->bathroom,'bedroom' => $request->bedroom,'area' => $request->area,'area_unit' => $request->area_unit,'description' => $request->description,'shop_id' => $Shop[0]->shop_id,'url' => $url,'id' => $id,'vendor_id' => session()->get('vendor_id'),'date' => date('Y-m-d'),'time' => date('H:i:s')]);

        $Activity[] = array('description' => 'Property Has Been Added On '.date('F d Y'),'icon' => 'fa-solid fa-plus','date' => date('d F'),'task' => 'Creation');
        $Activity[] = array('description' => 'Temperary Down For Approval From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-down-long','date' => date('d F'),'task' => 'Temperary Down For Approval');

        $property_id = DB::getPdo()->lastInsertId();
        $Update_Code = DB::table('property')->whereproperty_id($property_id)->update(['code' => md5($property_id),'activity' => json_encode($Activity)]);

        // Insert Card Image
        if ($request->file('card')) { $card = $request->file('card'); foreach($card as $c){
        $card_ext = $c->getClientOriginalExtension(); $Card_Name = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$card_ext)); $c->move(public_path().'/uploads/property/card/', $Card_Name);
        $Update_Card = DB::table('property')->whereproperty_id($property_id)->update(['card' => $Card_Name]);}}

        return response()->json(['error' => false,'message'=>'Property Created Successfully']);
    }else{return response()->json(['error'=>'logout']);}}
    public function Listing(){ if (session()->get("vendor_id")) { return view('vendor.property.listing'); }else{ return redirect('/login/vendor'); }}

    // Listing
    public function Get(Request $request){
    if (session()->get('vendor_id')) {
        $Where = [['property.availability' , 0]]; $take = $request->take;
        if ($request->search != null) {$Where[] = ['property.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['property.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['property.date','<=',$request->end_date];}
        if ($take == 0) { $take = DB::table('property')->where('vendor_id',session()->get('vendor_id'))->count(); }
        $property = DB::table('property')->leftjoin('country','country.country_id','=','property.country_id')->leftjoin('state','state.state_id','=','property.state_id')->leftjoin('city','city.city_id','=','property.city_id')->select('property.status','property.date','property.code','property.card','property.url','property.views','property.block','property.approval','country.name as country','state.name as state','city.name as city','property.location','property.price','property.area','property.area_unit','property.type_id','property.purpose')->where($Where)->orderby('property.property_id',$request->orderby)->take($take)->get();
        return view("vendor.include.property.index",['property'=>$property]);
    }else{return redirect('/login/vendor');}}


    // Edit
    public function Edit($code){
        if (session()->get("vendor_id")) {
            $Edit = DB::table('property')->select('purpose','type_id','condition','name','price','country_id','state_id','city_id','location','bedroom','bathroom','area','area_unit','description','card','id','code')->wherecode($code)->get();
            return view("vendor.property.edit",['Edit'=>$Edit]);
        }else{ return redirect('/login/vendor');}
    }

    public function Update(Request $request){
        if (session()->get("vendor_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $property = DB::table('property')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Property Has Been Editing On '.date('F d Y'),'icon' => 'fa-solid fa-pen-to-square','date' => date('d F'),'task' => 'Editing');
            $Activity[] = array('description' => 'Property Temporary Down For Administration Approval  On '.date('F d Y'),'icon' => 'fa-solid fa-down-long','date' => date('d F'),'task' => 'Down For Administration Approval');
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
                    'approval' => 0,
                ]);

            if ($request->file('card')) {
                $Get_Card = DB::table('property')->select('card')->wherecode($request->code)->get();
                $Card_path = public_path().'/uploads/property/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/property/card/', $imgNameToStore);
                $Update_Images = DB::table('property')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Property Edited Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Status
    public function Status(Request $request){
        if (session()->get("vendor_id")) {
            $property = DB::table('property')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($property[0]->activity);

            $check = DB::table("property")->select('status')->wherecode($request->code)->get();
            if ($check[0]->status == 0) {
                $Activity[] = array('description' => 'Property Has Been Private On '.date('F d Y'),'icon' => 'fa-solid fa-lock','date' => date('d F'),'task' => 'Down');
                $De_Active_property = DB::table('property')->wherecode($request->code)->update(['status' => 1,'activity' => json_encode($Activity)]);
                return response()->json(['error'=> false,'message'=> 'property Is Become Private']);}
            if ($check[0]->status == 1) {
                $Activity[] = array('description' => 'Property Has Been Public On '.date('F d Y'),'icon' => 'fa-solid fa-unlock','date' => date('d F'),'task' => 'Online');
                $Active_property = DB::table('property')->wherecode($request->code)->update(['status' => 0,'activity' => json_encode($Activity)]);
                return response()->json(['error'=> false,'message'=> 'property Is Become Public']);}
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Images
    public function Image_Index($code){
        return view("vendor.property.images",['code'=>$code]);
    }
    public function Upload_Image(Request $request){
        if (session()->get("vendor_id")) {
            $property = DB::table('property')->select('images','activity')->wherecode($request->code)->get();
            $Files_Name = json_decode($property[0]->images);
            $images = $request->file('images');
            foreach($images as $i){ $img_ext = $i->getClientOriginalExtension();
            $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext));
            $i->move(public_path().'/uploads/property/images/', $imgNameToStore); $Files_Name[] = $imgNameToStore;}

            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Add Some Images On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Uploading');

            $Update_Code = DB::table('property')->wherecode($request->code)->update(['images' => json_encode($Files_Name),'activity' => json_encode($Activity)]);
            return response()->json(['error'=>false,'message'=>'Images Uploaded Successfully']);
        }else{return response()->json(['error'=>'true','message'=>'session expire']);}
    }
    public function Get_Image(Request $request){
        if (session()->get("vendor_id")) {
            $Property = DB::table('property')->select('images')->wherecode($request->code)->get();
            return view('vendor.include.property.images',['Property'=>$Property]);
        }else{return redirect('/login/vendor');}
    }
    public function Delete_Image(Request $request){
        if (session()->get("vendor_id")) {
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
            $Activity[] = array('description' => 'Delete Images('.$request->image.') On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Delete');

            $Update_Images = DB::table('property')->wherecode($request->code)->update([
                'images' => $Eecode,'activity' => json_encode($Activity)
            ]); return response()->json(['error' => false,'message'=>'Images Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Map
    public function Map_Index($code){
        return view("vendor.property.maps",['code'=>$code]);
    }
    public function Upload_Map(Request $request){
        if (session()->get("vendor_id")) {
            $property = DB::table('property')->select('maps','activity')->wherecode($request->code)->get();
            $Files_Name = json_decode($property[0]->maps);
            $maps = $request->file('maps');
            foreach($maps as $i){ $img_ext = $i->getClientOriginalExtension();
            $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext));
            $i->move(public_path().'/uploads/property/maps/', $imgNameToStore); $Files_Name[] = $imgNameToStore;}

            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Add Some maps On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Uploading');

            $Update_Code = DB::table('property')->wherecode($request->code)->update(['maps' => json_encode($Files_Name),'activity' => json_encode($Activity)]);
            return response()->json(['error'=>false,'message'=>'Map Uploaded Successfully']);
        }else{return response()->json(['error'=>'true','message'=>'session expire']);}
    }
    public function Get_Map(Request $request){
        if (session()->get("vendor_id")) {
            $Property = DB::table('property')->select('maps')->wherecode($request->code)->get();
            return view('vendor.include.property.maps',['Property'=>$Property]);
        }else{return redirect('/login/vendor');}
    }
    public function Delete_Map(Request $request){
        if (session()->get("vendor_id")) {
            $property = DB::table("property")->select('maps','activity')->wherecode($request->code)->get();
            $ALL = json_decode($property[0]->maps); $count = count($ALL);
            if ($count > 1) {
                foreach($ALL as $A){
                    if ($A != $request->maps) {
                        $New[] = $A;
                    }else {
                        $Card_path = public_path().'/uploads/property/maps/'.$request->maps; if (File::exists($Card_path)) { unlink($Card_path);}
                    }
                }
            }else{ $New = []; }
            $Eecode = json_encode($New);

            $Activity = json_decode($property[0]->activity);
            $Activity[] = array('description' => 'Delete Maps('.$request->maps.') On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Delete');

            $Update_maps = DB::table('property')->wherecode($request->code)->update([
                'maps' => $Eecode,'activity' => json_encode($Activity)
            ]); return response()->json(['error' => false,'message'=>'Maps Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Note
    public function Note($code){
        if (session()->get("vendor_id")) {
            return view("vendor.property.note",['code' =>$code]);
        }else{ return redirect('/login/vendor'); }
    }
    public function Get_Note($code){
        if (session()->get("vendor_id")) {
            $property = DB::table('property')->select('note')->wherecode($code)->get();
            return response()->json(['note' => array_reverse(json_decode($property[0]->note))]);
        }else{ return response()->json(['error'=>'logout']); }
    }
    public function Insert_Note(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('property')->select('note','activity')->wherecode($request->code)->get();
            $Note = json_decode($Get[0]->note);
            $Note[] = array('note' => $request->note);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Some Note On '.date('F d Y'),'icon' => 'fa-regular fa-note-sticky','date' => date('d F'),'task' => 'Note Writing');
            $Update = DB::table('property')->wherecode($request->code)->update(['note'=>json_encode($Note),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Note Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']); }
    }

    // Activity
    public function Activity($code){
        if (session()->get("vendor_id")) {
            $property = DB::table('property')->select('activity')->wherecode($code)->get();
            return view("vendor.property.activity",['activity' => array_reverse(json_decode($property[0]->activity))]);
        }else{ return redirect('/login/vendor'); }
    }

    // Delete
    public function Delete(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('property')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Property Has Been Delete On '.date('F d Y'),'icon' => 'fa-solid fa-trash-can','date' => date('d F'),'task' => 'Deleted');
            $Delete = DB::table('property')->wherecode($request->code)->update(['availability' => 1,'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Property Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']); }
    }
}
