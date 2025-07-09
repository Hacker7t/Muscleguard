<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use File;
class product_controller extends Controller
{
    public function index(){
        if (session()->get('account_id')) {
            return view('dashboard.product.index',['filter' => 'no']);
        }else{return redirect('/dashboard/login');}
    }
    public function Filter($filter){
        if (session()->get('account_id')) {
            return view('dashboard.product.index',['filter' => $filter]);
        }else{return redirect('/dashboard/login');}
    }
    public function Insert(Request $request){
        if (session()->get('account_id')) {
            $id = time();
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));

            $insert = DB::table('product')->insert(['category_id' => $request->category_id,'sub_category_id' => $request->sub_category_id,'name' => $request->name,'description' => $request->description,'specifications' => $request->specifications,'price' => $request->price,'keywords' => $request->keywords,'sku' => $sku,'url' => $url,'id' => $id,'account_id' => session()->get('account_id'),'date' => date('Y-m-d'),'time' => date('H:i:s')]);

            $product_id = DB::getPdo()->lastInsertId(); $sku = Str::random(8).$product_id * mt_rand(1111, 9999);
            $Update_Code = DB::table('product')->whereproduct_id($product_id)->update(['code' => md5($product_id),'sku' => $sku]);

            // Insert Card Image
            if ($request->file('card')) {
                $card = $request->file('card'); foreach($card as $c){
                $card_ext = $c->getClientOriginalExtension(); $Card_Name = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$card_ext)); $c->move(public_path().'/uploads/product/card/', $Card_Name);
                $Update_Card = DB::table('product')->whereproduct_id($product_id)->update(['card' => $Card_Name]);}
            }

            return response()->json(['error' => false,'message'=>'Product Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Get(Request $request){
        if (session()->get('account_id')) {
            $Column = 'product.product_id';
            $Where = []; $take = $request->take;
            if ($request->approval != 'all') {$Where[] = ['product.approval','=',$request->approval];}
            if ($request->banned != 0) {$Where[] = ['product.block','=',$request->banned];}
            if ($request->start_date != null) {$Where[] = ['product.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['product.date','<=',$request->end_date];}
            if ($request->search != null) {$Where[] = ['product.name','LIKE','%'.$request->search.'%'];}
            if ($take == 0) { $take = DB::table('product')->count(); }
            if ($request->filter == 'popular') { $Column = 'product.views';}

            $product = DB::table('product')->leftjoin('account','account.account_id','=','product.account_id')->leftjoin('vendor','vendor.vendor_id','=','product.vendor_id')->leftjoin('shop','shop.vendor_id','=','product.vendor_id')->select('product.name','product.status','product.product_id','product.date','product.code','product.discount','product.card','product.size','product.url','product.rating','product.views','product.stock','product.vendor_id','product.block','product.approval','product.price','account.name as account','vendor.first_name as vendor_first_name','vendor.last_name as vendor_last_name','shop.shop_name as shop','product.featured')->where($Where)->orderby($Column,$request->orderby)->take($take)->get();
            return view("dashboard.include.product.index",['product'=>$product]);
        }else{return redirect('/dashboard/login');}
    }
    public function Get_Category(){
        if (session()->get("account_id")) {
            $category = DB::table('category')->select('name','category_id')->where([['status',0],['parent_id',0]])->get();
            return response()->json(['category' => $category]);
        }else{return response()->json(['error' => true]);}
    }
    public function Get_Sub_Category($category_id){
        if (session()->get("account_id")) {
            $subcategory = DB::table('category')->select('name','category_id')->where([['status','=',0],['parent_id',$category_id]])->get();
            return response()->json(['subcategory' => $subcategory]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Product Has Beed Deleted From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-trash-can','date' => date('d F'),'task' => 'Delete');
            $Product = DB::table('product')->wherecode($request->code)->update(['availability' => 1, 'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Product Deleted Successfully']);
        }
    }
    public function Featured(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("product")->select('featured')->wherecode($request->code)->get();
            if ($check[0]->featured == 0) {
                $De_Active_product = DB::table('product')->wherecode($request->code)->update(['featured' => 1]);
                return response()->json(['error'=> false,'message'=> ' Product Is Become Featured']);}
            if ($check[0]->featured == 1) {
                $Active_product = DB::table('product')->wherecode($request->code)->update(['featured' => 0]);
                return response()->json(['error'=> false,'message'=> 'Product Is Become Un Featured']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Approval(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table("product")->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            if ($request->approval == 1) {
                $Activity[] = array('description' => 'Product Approved By Administration On '.date('F d Y'),'icon' => 'fa-regular fa-face-smile','date' => date('d F'),'task' => 'Approved');
            }else{
                $Activity[] = array('description' => 'Product Reject By Administration On '.date('F d Y'),'icon' => 'fa-regular fa-face-sad-tear','date' => date('d F'),'task' => 'Reject');
            }

            $Approval_Product = DB::table('product')->wherecode($request->code)->update(['approval' => $request->approval,'activity' => json_encode($Activity)]);
            return response()->json(['error'=> false]);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Banned(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table("product")->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Product Parmanently Banned By Administration On '.date('F d Y'),'icon' => 'fa-solid fa-ban','date' => date('d F'),'task' => 'Banned');
            $Update_Banned = DB::table('product')->wherecode($request->code)->update(['block' => 1,'activity' => json_encode($Activity)]);
            return response()->json(['error'=> false,'message'=> 'Product Parmanently Banned Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Discount(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Discount Updated From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-percent','date' => date('d F'),'task' => 'Discount');
            $Update_Discount = DB::table('product')->wherecode($request->code)->update(['discount' => $request->discount,'activity' => json_encode($Activity)]);
            return response()->json(['error'=> false,'message'=> 'Product Discount Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('product')->select('name','card','id','colors','price','description','specification','keywords','category_id','sub_category_id','stock')->wherecode($code)->get();
            return view("dashboard.product.edit",['Edit'=>$Edit,'code'=>$code]);
        }else{ return redirect('/dashboard/login');}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));

            $Get = DB::table('product')->select('activity')->wherecode($request->code)->get();
            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Product Edit From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-pen-to-square','date' => date('d F'),'task' => 'Editing');

            $Update = DB::table('product')->wherecode($request->code)->update(
                [
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'specification' => $request->specification,
                    'price' => $request->price,
                    'keywords' => $request->keywords,
                    'stock' => $request->stock,
                    'url' => $url,
                    'activity' => json_encode($Activity),
                ]);

            if ($request->file('card')) {
                $Get_Card = DB::table('product')->select('card')->wherecode($request->code)->get();
                $Card_path = public_path().'/uploads/product/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/product/card/', $imgNameToStore);
                $Update_Images = DB::table('product')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Product Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    // Images
    public function Product_Images($code){
        return view("dashboard.product.images",['code'=>$code]);
    }
    public function Product_Upload_Images(Request $request){
        if (session()->get("account_id")) {
            $Product = DB::table('product')->select('images','activity')->wherecode($request->code)->get();
            $Files_Name = json_decode($Product[0]->images);
            $images = $request->file('images');
            foreach($images as $i){ $img_ext = $i->getClientOriginalExtension();
                $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("vendor_id").'-'.time().'-'.mt_rand(10000000, 99999999).'.'.$img_ext));
                $i->move(public_path().'/uploads/product/images/', $imgNameToStore); $Files_Name[] = $imgNameToStore;}

            $Activity = json_decode($Product[0]->activity);
            $Activity[] = array('description' => 'Add Some Images From Administration On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Images Creation');

            $Update_Code = DB::table('product')->wherecode($request->code)->update(['images' => json_encode($Files_Name),'activity' => json_encode($Activity)]);
            return response()->json(['error'=>false,'message'=>'Images Uploaded Successfully']);
        }else{return response()->json(['error'=>'true','message'=>'session expire']);}
    }
    public function Product_Delete_Images(Request $request){
        if (session()->get("account_id")) {

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
            $Activity[] = array('description' => 'Delete Some Images From Administration On '.date('F d Y'),'icon' => 'fa-regular fa-image','date' => date('d F'),'task' => 'Images Delete');

            $Update_Images = DB::table('product')->wherecode($request->code)->update([
                'images' => $Eecode,
                'activity' => json_encode($Activity)
            ]);

            return response()->json(['error' => false,'message'=>'Images Deleted Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Product_Get_Images(Request $request){
        if (session()->get("account_id")) {
            $Product = DB::table('product')->select('images')->wherecode($request->code)->get();
            return view('dashboard.include.product.images',['Product'=>$Product]);
        }else{return redirect('/dashboard/login');}
    }
    public function Product_Images_Holding(Request $request){
        if (session()->get("account_id")) {
            $Product = DB::table("product")->select('images')->wherecode($request->code)->get();
            $ALL = json_decode($Product[0]->images); $count = count($ALL);
            if ($count > 0) {
                foreach($ALL as $A){
                    if ($A->img != $request->img) {
                        $New[] = $A;
                    }else {
                        $New[] = array('no' => $request->no,'img' => $A->img);
                    }
                }
            }else{ $New = []; }
            $Eecode = json_encode($New);
            $Update_Images = DB::table('product')->wherecode($request->code)->update([
                'images' => $Eecode,
            ]);
            return response()->json(['error' => false,'message'=>'Images Holding Position Updated Successfully']);
        }
    }
    // Colors
    public function Product_Color($code){return view("dashboard.product.color",['code'=>$code]);}
    public function Get_Color($code){
        $Product = DB::table('product')->select('colors')->wherecode($code)->get();
        return response()->json(['colors'=>json_decode($Product[0]->colors)]);
    }
    public function Insert_Color(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('colors')->wherecode($request->code)->get();
            $Colors = json_decode($Get[0]->colors);
            if ($Colors != null) {
                foreach ($Colors as $c) {
                    if ($c->hex == $request->hex) {
                        return response()->json(['error' => true,'message'=>'Color Allready Exist']);
                    }
                }
            }

            $Colors[] = array('name'=>$request->name,'hex'=>$request->hex,'status'=>0);
            $Update = DB::table('product')->wherecode($request->code)->update(['colors'=>json_encode($Colors)]);
            return response()->json(['error' => false,'message'=>'Color Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Delete_Color(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('colors','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->colors);
            $Colors = [];
            foreach ($Decode as $d) {
                if ($d->hex != $request->hex) {
                    $Colors[] = $d;
                }
            }

            $Activity = json_decode($Product[0]->activity);
            $Activity[] = array('description' => 'Color ('.$request->hex.') Delete From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-droplet','date' => date('d F'),'task' => 'Color Delete');

            $Update = DB::table('product')->wherecode($request->code)->update(['colors'=>json_encode($Colors),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Color Deleted Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Status_Color(Request $request){
        if (session()->get("account_id")) {
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
    public function Product_Size($code){
        return view("dashboard.product.size",['code'=>$code]);
    }
    public function Insert_Size(Request $request){
        if (session()->get("account_id")) {
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
            $Activity[] = array('description' => 'Add Size ('.$request->size.') With Stock Of ('.$request->stock.') Piece From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Creation');

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Size Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Get_Size($code){
        $Product = DB::table('product')->select('size')->wherecode($code)->get();
        return view("dashboard.include.product.size",['size'=>json_decode($Product[0]->size),'code'=>$code]);
    }
    public function Delete_Size(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('size','activity')->wherecode($request->code)->get();
            $Decode = json_decode($Get[0]->size);
            $Size = [];
            foreach ($Decode as $d) {
                if ($d->size != $request->size) {
                    $Size[] = $d;
                }
            }

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Delete Size ('.$request->size.') From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Delete');

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Size Deleted Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Status_Size(Request $request){
        if (session()->get("account_id")) {
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
                $desc = 'Size ('.$request->size.') Down From Administration On '.date('F d Y'); $task = 'Size Down';
            }else{ $desc = $desc = 'Size ('.$request->size.') Public From Administration On '.date('F d Y'); $task = 'Size Public'; }
            $Activity[] = array('description' => $desc,'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => $task);

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Update_Size(Request $request){
        if (session()->get("account_id")) {
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
            $Activity[] = array('description' => 'Edit Size ('.$request->size.') With The Stock ('.$request->stock.') From Administration  On '.date('F d Y'),'icon' => 'fa-solid fa-ruler','date' => date('d F'),'task' => 'Size Editing');

            $Update = DB::table('product')->wherecode($request->code)->update(['size'=>json_encode($Size),'activity' => json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    // Review
    public function Product_Review($code){return view("dashboard.product.review",['code'=>$code]);}
    public function Get_Review(Request $request){
        $review = DB::table('review')->leftjoin('product','product.product_id','=','review.product_id')->leftjoin('user','user.user_id','=','review.user_id')->select('review.review','review.rating','user.name','review.code','review.status','review.date')->where('product.code',$code)->orderby('review.review_id','DESC')->get();
        return view("dashboard.include.product.review",['review' => $review]);
    }
    public function Status_Review(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("review")->select('status')->wherecode($request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_review = DB::table('review')->wherecode($request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> 'Review Is Hide Successfully']);}
            if ($check[0]->status == 1) {
                $Active_review = DB::table('review')->wherecode($request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> 'Review Is Show Successfully']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Delete_Review(Request $request){
        $Delete = DB::table('review')->wherecode($request->code)->delete();
        return response()->json(['error' => false,'message'=>'Review Deleted Successfully']);
    }
    // Note
    public function Product_Note($code){
        return view("dashboard.product.note",['code' => $code]);
    }
    public function Insert_Note(Request $request){
        if (session()->get("account_id")) {
            $Get = DB::table('product')->select('note','activity')->wherecode($request->code)->get();
            $Note = json_decode($Get[0]->note);
            $Note[] = array('note' => $request->note);

            $Activity = json_decode($Get[0]->activity);
            $Activity[] = array('description' => 'Add Some Note From Administration On '.date('F d Y'),'icon' => 'fa-solid fa-note-sticky','date' => date('d F'),'task' => 'Note');

            $Update = DB::table('product')->wherecode($request->code)->update(['note'=>json_encode($Note),'activity'=>json_encode($Activity)]);
            return response()->json(['error' => false,'message'=>'Note Saved Successfully']);
        }else{return response()->json(['error'=>'logout']);}
    }
    public function Get_Note($code){
        if (session()->get("account_id")) {
            $Product = DB::table('product')->select('note')->wherecode($code)->get();
            return response()->json(['note' => array_reverse(json_decode($Product[0]->note))]);
        }else{return response()->json(['error'=>'logout']);}
    }

    public function Custom_delete(Request $request){
        $product_id = $request->product_id;
        $insert_data = DB::table('product')->where('product_id', $product_id)->delete();
        return redirect()->route('admin_product');
    }
}
