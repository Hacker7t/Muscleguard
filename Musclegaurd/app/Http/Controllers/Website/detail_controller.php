<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class detail_controller extends Controller
{
    public function Detail($url){
        $Check_Product = DB::table('product')->where([['url',$url],['status',0]])->count();
        $Check_Category = DB::table('category')->where([['url',$url],['status',0],['parent_id',0]])->count();
        $Check_Sub_Category = DB::table('category')->where([['url',$url],['status',0],['parent_id','!=',0]])->count();

        if ($Check_Product != 0) {
            $product = DB::table('product')->leftjoin('category','category.category_id','=','product.category_id')->select('product.name','product.rating','category.name as category','category.url as category_url','product.sub_category_id','product.price','product.discount','product.colors','product.size','product.description','product.specification','product.card','product.images','product.url','product.product_id','product.sku')->where([['product.status',0],['product.url',$url]])->get();

            $SubCategory = DB::table('category')->select('name','url')->where('category_id',$product[0]->sub_category_id)->get();

            if (session()->get("user_id")) {$Wishlist = DB::table('wishlist')->where([['user_id',session()->get("user_id")],['product_id',$product[0]->product_id]])->count();}else{$Wishlist = 0;}

            $Reviews = DB::table('review')->leftjoin('user','user.user_id','=','review.user_id')->select('user.name as user','review.rating','review.review','review.date')->where('product_id',$product[0]->product_id)->orderby('review.review_id','DESC')->TAKE(5)->get();

            $Total_Reviews = DB::table('review')->leftjoin('user','user.user_id','=','review.user_id')->where('product_id',$product[0]->product_id)->orderby('review.review_id','DESC')->count();

            $View = DB::table('product')->whereurl($url)->increment('views');

            return view("website.product.index",['product'=>$product[0],'SubCategory' => $SubCategory[0],'Wishlist'=>$Wishlist,'Reviews'=>$Reviews,'Total_Reviews' => $Total_Reviews]);
        }elseif($Check_Category != 0){
            $Category = DB::table('category')->select('category_id')->whereurl($url)->get();
            return view("website.shop.index",['category_id' => $Category[0]->category_id,'sub_category_id' => 0]);
        }elseif($Check_Sub_Category != 0){
            $Category = DB::table('category')->select('category_id','parent_id')->whereurl($url)->get();
            return view("website.shop.index",['category_id' => $Category[0]->parent_id,'sub_category_id' => $Category[0]->category_id]);
        }
    }

    public function Related($url){
        $Get = DB::table('product')->select('sub_category_id')->whereurl($url)->get();
        $Conditions = [['status',0],['url','!=',$url],['sub_category_id',$Get[0]->sub_category_id]];
        $Select = ['name','url','card','price','discount','product_id','rating'];
        $product = DB::table('product')->select($Select)->where($Conditions)->orderby(DB::raw('RAND()'))->get();
        $wishlist = DB::table('wishlist')->select('product_id')->where('user_id',session()->get("user_id"))->get();
        return view("website.include.product.card",['product'=>$product,'wishlist'=>$wishlist]);
    }

    public function Insert_Rating(Request $request){
        if (session()->get("user_id")) {
            $Product = DB::table('product')->select('product_id')->whereurl($request->url)->get();
            $insert = DB::table('review')->insert(
                [
                    'product_id' => $Product[0]->product_id,
                    'rating' => $request->rating,
                    'user_id' => session()->get("user_id"),
                    'review' => $request->review,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                ]);

            $review_id = DB::getPdo()->lastInsertId(); $update = DB::table('review')->wherereview_id($review_id)->update(['code' => md5($review_id)]);

            $All_Reviews = DB::table('review')->select('rating')->whereproduct_id($Product[0]->product_id)->get();
            $Reviews_Count = count($All_Reviews);
            if ($Reviews_Count > 0) {
            $Ratings=0;
            foreach($All_Reviews as $GR){$Ratings += $GR->rating;}
            $Find_Ratings = $Ratings / $Reviews_Count;
            $Actual_Ratings = round($Find_Ratings + 0,1);
            $Update_Ratings = DB::table('product')->whereproduct_id($Product[0]->product_id)->update(['rating' => $Actual_Ratings]);}

            return response()->json(['error' => false,'message'=>'Review Submit Successfully']);
        }else{ return response()->json(['error' => true,'message' => 'Login Required For Rate This Product!']); }
    }
}
