<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class blog_controller extends Controller
{
    public function index(){
        return view('dashboard.blog.index');
    }
    public function Insert(Request $request){
        if (session()->get("account_id")) {
            $id = time();
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $insert = DB::table('blog')->insert(
                [
                    'name' => $request->name,
                    'writter_name' => $request->writter_name,
                    'description' => $request->description,
                    'id' => $id,
                    'url' => $url,
                    'account_id' => session()->get('account_id'),
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                ]);
            $blog_id = DB::getPdo()->lastInsertId(); $code = md5($blog_id);
            $update = DB::table('blog')->whereblog_id($blog_id)->update(['code' => $code]);

            if ($request->file('card')) {
                $card = $request->file('card');
                foreach($card as $c){
                    $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/blog/card/', $imgNameToStore);
                    $Update_Card = DB::table('blog')->whereblog_id($blog_id)->update(['card' => $imgNameToStore]);
                }
            }

            if ($request->file('writter_image')) {
                $writter_image = $request->file('writter_image');
                foreach($writter_image as $c){
                    $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/blog/writter_image/', $imgNameToStore);
                    $Update_writter_image = DB::table('blog')->whereblog_id($blog_id)->update(['writter_image' => $imgNameToStore]);
                }
            }

            return response()->json(['error' => false,'message'=>'Blog Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Get(Request $request){
        if (session()->get("account_id")) {
            $Where = [];
            if ($request->search != null) {$Where[] = ['blog.name','LIKE','%'.$request->search.'%'];}
            if ($request->start_date != null) {$Where[] = ['blog.date','>=',$request->start_date];}
            if ($request->end_date != null) {$Where[] = ['blog.date','<=',$request->end_date];}
            $blog = DB::table('blog')->leftjoin('account','account.account_id','=','blog.account_id')->select('blog.name','blog.status','blog.code','blog.date','blog.blog_id','blog.card','blog.writter_name','account.name as account')->where($Where)->orderby('blog.blog_id',$request->orderby)->take($request->take)->get(); return view('dashboard.include.blog.index',['blog'=>$blog]);
        }else{return response()->json(['error' => true]);}
    }
    public function Delete(Request $request){
        if (session()->get("account_id")) {
            $Get_Image = DB::table('blog')->select('card','writter_image')->wherecode($request->code)->get();
            $Card_path = public_path().'/uploads/blog/card/'.$Get_Image[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
            $writter_image_path = public_path().'/uploads/blog/writter_image/'.$Get_Image[0]->writter_image; if (File::exists($writter_image_path)) { unlink($writter_image_path);}
            $blog = DB::table('blog')->wherecode($request->code)->delete();
            return response()->json(['error' => false,'message'=>'Blog Deleted Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Edit($code){
        if (session()->get("account_id")) {
            $Edit = DB::table('blog')->select('name','card','id','writter_image','description','writter_name')->where('code','=',$code)->get();
            return view('dashboard.blog.edit',['Edit'=>$Edit,'code'=>$code]);
        }else{return response()->json(['error' => true]);}
    }
    public function Update(Request $request){
        if (session()->get("account_id")) {
            $id = $request->id;
            $url = preg_replace('/[^a-zA-Z0-9_ -]/s','-',strtolower(str_replace(' ', '-', $request->name.'-'.$id)));
            $update = DB::table('blog')->wherecode($request->code)->update([
                'name' => $request->name,
                'writter_name' => $request->writter_name,
                'description' => $request->description,
                'url' => $url,
            ]);

            if ($request->file('card')) {
                $Get_Card = DB::table('blog')->select('card')->wherecode($request->code)->get();
                $Card_path = public_path().'/uploads/blog/card/'.$Get_Card[0]->card; if (File::exists($Card_path)) { unlink($Card_path);}
                $card = $request->file('card'); foreach($card as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/blog/card/', $imgNameToStore);
                $Update_Images = DB::table('blog')->wherecode($request->code)->update(['card' => $imgNameToStore]);}
            }

            if ($request->file('writter_image')) {
                $Get_writter_image = DB::table('blog')->select('writter_image')->wherecode($request->code)->get();
                $writter_image_path = public_path().'/uploads/blog/writter_image/'.$Get_writter_image[0]->writter_image; if (File::exists($writter_image_path)) { unlink($writter_image_path);}
                $writter_image = $request->file('writter_image'); foreach($writter_image as $c){
                $img_ext = $c->getClientOriginalExtension(); $imgNameToStore = strtolower(str_replace(' ', '-',session()->get("account_id").'-'.$id.'-'.mt_rand(10000000, 99999999).'.'.$img_ext)); $c->move(public_path().'/uploads/blog/writter_image/', $imgNameToStore);
                $Update_Images = DB::table('blog')->wherecode($request->code)->update(['writter_image' => $imgNameToStore]);}
            }

            return response()->json(['error' => false,'message'=>'Changes Saved Successfully']);
        }else{return response()->json(['error' => true]);}
    }
    public function Status(Request $request){
        if (session()->get("account_id")) {
            $check = DB::table("blog")->select('status')->where('code','=',$request->code)->get();
            if ($check[0]->status == 0) {
                $De_Active_blog = DB::table('blog')->where('code', '=', $request->code)->update(['status' => 1]);
                return response()->json(['error'=> false,'message'=> ' Blog Is De-Active Successfully']);}
            if ($check[0]->status == 1) {
                $Active_blog = DB::table('blog')->where('code', '=', $request->code)->update(['status' => 0]);
                return response()->json(['error'=> false,'message'=> 'Blog Is Active Successfully']);}
        }else{ return response()->json(['error'=>'logout']);}
    }
    public function Write($code){ $Edit = DB::table('blog')->select('blog')->wherecode($code)->get();
        return view("dashboard.blog.write",['code'=>$code,'Edit'=>$Edit]);
    }
    public function Get_Image(){
        $Image = DB::table('image')->select('name')->orderby('image_id','DESC')->GET();
        return view("dashboard.include.blog.image",['Image'=>$Image]);
    }
    public function Save(Request $request){
        if (session()->get("account_id")) {
            $Save = DB::table('blog')->wherecode($request->code)->update(['blog' => $request->blog]);
            return response()->json(['error'=> false,'message'=> 'Blog Saved Successfully']);
        }else{ return response()->json(['error'=>'logout']);}
    }
}
