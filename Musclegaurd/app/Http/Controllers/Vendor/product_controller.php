<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use File;

class product_controller extends Controller
{
    // Craete
    public function Index(){ if (session()->get("vendor_id")) { return view('vendor.product.index'); }else{ return redirect('/login/vendor'); }}
    public function Get_Category(){ if (session()->get("vendor_id")) {
    $category = DB::table('category')->leftjoin('shop','shop.category_id','=','category.parent_id')->select('category.category_id','category.name')->where([['shop.vendor_id',session()->get("vendor_id")]])->get(); return response()->json(['error' => false,'category' => $category]);
    }else{ return response()->json(['error' => true,'message' => 'Session Expire!']); }}
    public function Insert(Request $request){
    if (session()->get('vendor_id')) {
        $Shop = DB::table('vendor')->leftjoin('shop','shop.vendor_id','=','vendor.vendor_id')->select('shop.shop_id','shop.category_id')->where('shop.vendor_id',session()->get('vendor_id'))->get();
        $id = time();
        $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));

        $insert = DB::table('product')->insert(['category_id' => $Shop[0]->category_id,'sub_category_id' => $request->category_id,'name' => $request->name,'description' => $request->description,'specification' => $request->specification,'keywords' => $request->keywords,'price' => $request->price,'url' => $url,'id' => $id,'vendor_id' => session()->get('vendor_id'),'date' => date('Y-m-d'),   'time' => date('H:i:s')]);

        $Activity[] = array('description' => 'Product Has Been Added On '.date('F d Y'),'icon' => 'fa-solid fa-plus','date' => date('d F'),'task' => 'Creation');
        $Activity[] = array('description' => 'Temperary Down For Approval From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-down-long','date' => date('d F'),'task' => 'Temperary Down For Approval');

        $product_id = DB::getPdo()->lastInsertId(); $sku = Str::random(8).$product_id * mt_rand(1111, 9999);
        $Update_Code = DB::table('product')->whereproduct_id($product_id)->update(['code' => md5($product_id),'sku' => $sku,'activity' => json_encode($Activity)]);

        if ($request->stock != null) { $Update_Stock = DB::table('product')->wherecode($request->code)->update(['stock' => $request->stock]); }
        if ($request->shipment == 1) {
        $Update_Shipment = DB::table('product')->whereproduct_id($product_id)->update(['shipment' => $request->shipment,'intra' => $request->intra,'inter' => $request->inter]);}

        // Insert Card Image
        if ($request->file('card')) { $card = $request->file('card'); foreach($card as $c){
        $card_ext = $c->getClientOriginalExtension(); $Card_Name = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$card_ext)); $c->move(public_path().'/uploads/product/card/', $Card_Name);
        $Update_Card = DB::table('product')->whereproduct_id($product_id)->update(['card' => $Card_Name]);}}

        return response()->json(['error' => false,'message'=>'Product Created Successfully']);
    }else{return response()->json(['error'=>'logout']);}}
    public function Listing(){ if (session()->get("vendor_id")) { return view('vendor.product.listing'); }else{ return redirect('/login/vendor'); }}

    // Listing
    public function Get(Request $request){
    if (session()->get('vendor_id')) {
        $Where = [['product.availability' , 0],['product.vendor_id',session()->get("vendor_id")]]; $take = $request->take;
        if ($request->search != null) {$Where[] = ['product.name','LIKE','%'.$request->search.'%'];}
        if ($request->start_date != null) {$Where[] = ['product.date','>=',$request->start_date];}
        if ($request->end_date != null) {$Where[] = ['product.date','<=',$request->end_date];}
        if ($take == 0) { $take = DB::table('product')->where('vendor_id',session()->get('vendor_id'))->count(); }
        $product = DB::table('product')->leftjoin('category','category.category_id','=','product.sub_category_id')->select('product.name','product.status','product.date','product.code','product.discount','product.card','product.size','product.url','product.rating','product.views','product.stock','product.block','product.approval','category.name as category','product.price')->where($Where)->orderby('product.product_id',$request->orderby)->take($take)->get();
        return view("vendor.include.product.index",['product'=>$product]);
    }else{return redirect('/login/vendor');}}


    // Edit
    public function Edit($code){
        if (session()->get("vendor_id")) {
            $Edit = DB::table('product')->select('name','card','id','price','description','specification','sub_category_id','brand_id','code','stock','keywords','intra','inter','shipment')->wherecode($code)->get();
            return view("vendor.product.edit",['Edit'=>$Edit]);
        }else{ return redirect('/login/vendor');}
    }

    public function Update(Request $request){
        if (session()->get("vendor_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $Product = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Product[0]->activity);
            $Activity[] = array('description' => 'Product Has Been Editing On '.date('F d Y'),'icon' => 'fa-solid fa-pen-to-square','date' => date('d F'),'task' => 'Editing');
            $Activity[] = array('description' => 'Product Temporary Down For Administration Approval  On '.date('F d Y'),'icon' => 'fa-solid fa-down-long','date' => date('d F'),'task' => 'Down For Administration Approval');
            // dd($request->shipment);
            $Update = DB::table('product')->wherecode($request->code)->update(
                [
                    'sub_category_id' => $request->category_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'specification' => $request->specification,
                    'keywords' => $request->keywords,
                    'price' => $request->price,
                    'url' => $url,
                    'shipment' => $request->shipment,
                    'activity' => json_encode($Activity),
                    'approval' => 0,
                ]);

            if ($request->shipment == 1){ $Update_Shipment = DB::table('product')->wherecode($request->code)->update(['intra' => $request->intra,'inter' => $request->inter]);}

            if ($request->stock != null) { $Update_Stock = DB::table('product')->wherecode($request->code)->update(['stock' => $request->stock]); }

            if ($request->file('card')) {
                $Get_Card = DB::table('product')->select('card')->wherecode($request->code)->get();
                $Card_path = public_path().'/uploads/product/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/product/card/', $imgNameToStore);
                $Update_Images = DB::table('product')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Product Edited Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Status
    public function Status(Request $request){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Product[0]->activity);

            $check = DB::table("product")->select('status')->wherecode($request->code)->get();
            if ($check[0]->status == 0) {
                $Activity[] = array('description' => 'Product Has Been Private On '.date('F d Y'),'icon' => 'fa-solid fa-lock','date' => date('d F'),'task' => 'Down');
                $De_Active_product = DB::table('product')->wherecode($request->code)->update(['status' => 1,'activity' => json_encode($Activity)]);
                return response()->json(['error'=> false,'message'=> 'Product Is Become Private']);}
            if ($check[0]->status == 1) {
                $Activity[] = array('description' => 'Product Has Been Public On '.date('F d Y'),'icon' => 'fa-solid fa-unlock','date' => date('d F'),'task' => 'Online');
                $Active_product = DB::table('product')->wherecode($request->code)->update(['status' => 0,'activity' => json_encode($Activity)]);
                return response()->json(['error'=> false,'message'=> 'Product Is Become Public']);}
        }else{ return response()->json(['error'=>'logout']);}
    }

    // Images
    public function Image_Index($code){
        return view("vendor.product.images",['code'=>$code]);
    }
    public function Upload_Image(Request $request){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('images','activity')->wherecode($request->code)->get();
            $Files_Name = json_decode($Product[0]->images);
            $images = $request->file('images');
            foreach($images as $i){ $img_ext = $i->getClientOriginalExtension();
            $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext));
            $i->move(public_path().'/uploads/product/images/', $imgNameToStore); $Files_Name[] = $imgNameToStore;}

            $Activity = json_decode($Product[0]->activity);
            $Activity[] = array('description' => 'Add Some Images On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Uploading');

            $Update_Code = DB::table('product')->wherecode($request->code)->update(['images' => json_encode($Files_Name),'activity' => json_encode($Activity)]);
            return response()->json(['error'=>false,'message'=>'Images Uploaded Successfully']);
        }else{return response()->json(['error'=>'true','message'=>'session expire']);}
    }
    public function Get_Image(Request $request){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('images')->wherecode($request->code)->get();
            return view('vendor.include.product.images',['Product'=>$Product]);
        }else{return redirect('/login/vendor');}
    }
    public function Delete_Image(Request $request){
        if (session()->get("vendor_id")) {
            $Product = DB::table("product")->select('images','activity')->wherecode($request->code)->get();
            $ALL = json_decode($Product[0]->images); $count = count($ALL);
            if ($count > 1) {
                foreach($ALL as $A){
                    if ($A != $request->image) {
                        $New[] = $A;
                    }else {
                        $Card_path = public_path().'/uploads/product/images/'.$request->image; if (File::exists($Card_path)) { unlink($Card_path);}
                    }
                }
            }else{ $New = []; }
            $Eecode = json_encode($New);

            $Activity = json_decode($Product[0]->activity);
            $Activity[] = array('description' => 'Delete Images('.$request->image.') On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Image Delete');

            $Update_Images = DB::table('product')->wherecode($request->code)->update([
                'images' => $Eecode,'activity' => json_encode($Activity)
            ]); return response()->json(['error' => false,'message'=>'Images Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    // Color
    public function Color_Index($code){return view("vendor.product.color",['code'=>$code]);}
    public function Get_Color($code){
        $Product = DB::table('product')->select('colors')->wherecode($code)->get();
        return response()->json(['colors'=>json_decode($Product[0]->colors)]);
    }
    public function Insert_Color(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('colors','activity')->wherecode($request->code)->get();
            $Colors = json_decode($Get[0]->colors);
            if ($Colors != null) {
                foreach ($Colors as $c) {
                    if ($c->hex == $request->hex) {
                        return response()->json(['error' => true,'message'=>'Color Allready Exist']);
                    }
                }
            }

            $Colors[] = array('name'=>$request->name,'hex'=>$request->hex,'status'=>0);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Color ('.$request->name.') On '.date('F d Y'),'icon' => 'fa-solid fa-droplet','date' => date('d F'),'task' => 'Color Creation');

            $Update = DB::table('product')->wherecode($request->code)->update(['colors'=>json_encode($Colors),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Color Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Delete_Color(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('colors','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->colors);
            $Colors = [];
            foreach ($Decode as $d) {
                if ($d->hex != $request->hex) {
                    $Colors[] = $d;
                }
            }
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Delete Color ('.$request->hex.') On '.date('F d Y'),'icon' => 'fa-solid fa-droplet','date' => date('d F'),'task' => 'Color Delete');
            $Update = DB::table('product')->wherecode($request->code)->update(['colors'=>json_encode($Colors),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Color Deleted Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Status_Color(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('colors','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->colors);
            $Colors = [];
            foreach ($Decode as $d) {
                if ($d->hex != $request->hex) {
                    $Colors[] = $d;
                }else{
                    if ($d->status == 0) {$status = 1;}else{$status = 0;}
                    $Colors[] = array('name'=>$d->name,'hex'=>$d->hex,'status'=>$status);
                }
            }
            $Activity = json_decode($Get[0]->activity);
            if ($status == 1) {
                $desc = 'Color ('.$request->hex.') Down On '.date('F d Y'); $task = 'Color Down';
            }else{ $desc = $desc = 'Color ('.$request->hex.') Public On '.date('F d Y'); $task = 'Color Public'; }
            $Activity[] = array('description' => $desc,'icon' => 'fa-solid fa-droplet','date' => date('d F'),'task' => $task);

            $Update = DB::table('product')->wherecode($request->code)->update(['colors'=>json_encode($Colors),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    // Size
    public function Size_Index($code){
        return view("vendor.product.size",['code'=>$code]);
    }
    public function Insert_Size(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('size','activity')->wherecode($request->code)->get();
            $Size = json_decode($Get[0]->size);
            if ($Size != null) {
                foreach ($Size as $s) {
                    if ($s->size == $request->size) {
                        return response()->json(['error' => true,'message'=>'Size Allready Exist']);
                    }
                }
            }
            $Size[] = array('size'=>$request->size,'stock'=>$request->stock,'sold'=>0,'status'=>0);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Size ('.$request->size.') With Stock Of ('.$request->stock.') Piece On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Creation');

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Size Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Get_Size($code){
        $Product = DB::table('product')->select('size')->wherecode($code)->get();
        return view("vendor.include.product.size",['size'=>json_decode($Product[0]->size),'code'=>$code]);
    }
    public function Delete_Size(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('size','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->size);
            $Size = [];
            foreach ($Decode as $d) {
                if ($d->size != $request->size) {
                    $Size[] = $d;
                }
            }

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Delete Size ('.$request->size.') On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Delete');

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Size Deleted Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Status_Size(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('size','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->size);
            $Size = [];
            foreach ($Decode as $d) {
                if ($d->size != $request->size) {
                    $Size[] = $d;
                }else{
                    if ($d->status == 0) {$status = 1;}else{$status = 0;}
                    $Size[] = array('stock'=>$d->stock,'size'=>$d->size,'sold'=>$d->sold,'status'=>$status);
                }
            }

            $Activity = json_decode($Get[0]->activity);
            if ($status == 1) {
                $desc = 'Size ('.$request->size.') Down On '.date('F d Y'); $task = 'Size Down';
            }else{ $desc = $desc = 'Size ('.$request->size.') Public On '.date('F d Y'); $task = 'Size Public'; }
            $Activity[] = array('description' => $desc,'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => $task);

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Update_Size(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('size','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->size);
            $Size = [];
            if ($Size != null) {
                foreach ($Size as $s) {
                    if ($s->size != $request->size && $s->size == $request->old_size) {
                        return response()->json(['error' => true,'message'=>'Size Allready Exist']);
                    }
                }
            }
            foreach ($Decode as $d) {
                if ($d->size != $request->old_size) {
                    $Size[] = $d;
                }else{
                    $Size[] = array('stock'=>$request->stock,'size'=>$request->size,'sold'=>$d->sold,'status'=>$d->status);
                }
            }
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Edit Size ('.$request->size.') With The Stock ('.$request->stock.') On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Editing');
            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    // Discount
    public function Discount(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('price','activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            if ($request->discount != 0) {
                $Activity[] = array('description' => 'Product Go On Discount ('.$request->discount.'%) On '.date('F d Y'),'icon' => 'fa-solid fa-percent','date' => date('d F'),'task' => 'Go On Discount');
            }else{
                $Activity[] = array('description' => 'Product Back On Actual Price ('.$Get[0]->price.') On '.date('F d Y'),'icon' => 'fa-solid fa-percent','date' => date('d F'),'task' => 'Back On Actual Price');
            }

            $Discount = DB::table('product')->wherecode($request->code)->update(['discount' => $request->discount,'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Discount Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']); }
    }
    // Delete
    public function Delete(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Product Has Been Delete On '.date('F d Y'),'icon' => 'fa-solid fa-trash-can','date' => date('d F'),'task' => 'Deleted');
            $Delete = DB::table('product')->wherecode($request->code)->update(['availability' => 1,'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Product Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']); }
    }

    public function Review($code){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('product_id','rating')->wherecode($code)->get();
            $Five_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating',5]])->count();
            $Four_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating',4]])->count();
            $Three_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating',3]])->count();
            $Two_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating',2]])->count();
            $One_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating',1]])->count();
            $Positive_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating','>',3]])->count();
            $Negative_Star = DB::table('review')->where([['product_id',$Product[0]->product_id],['rating','<',3]])->count();
            $Total_Reviews = DB::table('review')->where([['product_id',$Product[0]->product_id]])->count();

            if ($Five_Star != 0) { $Five_Star_Per = ($Five_Star / $Total_Reviews) * 100; }else{ $Five_Star_Per = 0; }
            if ($Four_Star != 0) { $Four_Star_Per = ($Four_Star / $Total_Reviews) * 100; }else{ $Four_Star_Per = 0; }
            if ($Three_Star != 0) { $Three_Star_Per = ($Three_Star / $Total_Reviews) * 100; }else{ $Three_Star_Per = 0; }
            if ($Two_Star != 0) { $Two_Star_Per = ($Two_Star / $Total_Reviews) * 100; }else{ $Two_Star_Per = 0; }
            if ($One_Star != 0) { $One_Star_Per = ($One_Star / $Total_Reviews) * 100; }else{ $One_Star_Per = 0; }
            if ($Positive_Star != 0) { $Positive_Star_Per = ($Positive_Star / $Total_Reviews) * 100; }else{ $Positive_Star_Per = 0; }
            if ($Negative_Star != 0) { $Negative_Star_Per = ($Negative_Star / $Total_Reviews) * 100; }else{ $Negative_Star_Per = 0; }

            return view("vendor.product.review",['code' => $code,'One_Star' => $One_Star,'Two_Star' => $Two_Star,'Three_Star' => $Three_Star,'Four_Star' => $Four_Star,'Five_Star' => $Five_Star,'Five_Star_Per' => $Five_Star_Per,'Four_Star_Per' => $Four_Star_Per,'Three_Star_Per' => $Three_Star_Per,'Two_Star_Per' => $Two_Star_Per,'One_Star_Per' => $One_Star_Per,'Total_Reviews' => $Total_Reviews,'Product' => $Product,'Positive_Star_Per' => $Positive_Star_Per,'Negative_Star_Per' => $Negative_Star_Per]);

        }else{ return redirect('/login/vendor'); }
    }

    public function Get_Review($code){
        $Review = DB::table('review')->leftjoin('product','product.product_id','=','review.product_id')->leftjoin('user','user.user_id','=','review.user_id')->select('review.review','review.rating','user.name','review.code','review.date')->where('product.code',$code)->orderby('review.review_id','DESC')->get();
        return view("vendor.include.product.review",['Review' => $Review]);
    }

    // Note
    public function Note($code){
        if (session()->get("vendor_id")) {
            return view("vendor.product.note",['code' =>$code]);
        }else{ return redirect('/login/vendor'); }
    }
    public function Get_Note($code){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('note')->wherecode($code)->get();
            return response()->json(['note' => array_reverse(json_decode($Product[0]->note))]);
        }else{ return response()->json(['error'=>'logout']); }
    }
    public function Insert_Note(Request $request){
        if (session()->get("vendor_id")) {
            $Get = DB::table('product')->select('note','activity')->wherecode($request->code)->get();
            $Note = json_decode($Get[0]->note);
            $Note[] = array('note' => $request->note);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Some Note On '.date('F d Y'),'icon' => 'fa-regular fa-note-sticky','date' => date('d F'),'task' => 'Note Writing');
            $Update = DB::table('product')->wherecode($request->code)->update(['note'=>json_encode($Note),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Note Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']); }
    }

    // Activity
    public function Activity($code){
        if (session()->get("vendor_id")) {
            $Product = DB::table('product')->select('activity')->wherecode($code)->get();
            return view("vendor.product.activity",['activity' => array_reverse(json_decode($Product[0]->activity))]);
        }else{ return redirect('/login/vendor'); }
    }
}
