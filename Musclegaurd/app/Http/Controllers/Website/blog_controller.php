<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class blog_controller extends Controller
{
    public function Blog(){  
        $show_data = DB::table('blog')->get();
        return view('website.blog.index', ['show_data' => $show_data]);
    }

    public function Detail(Request $request){
        $id = $request->id;
        $show_data = DB::table('blog')->find($id);
        return view('website.blog.detail', ['show_data' => $show_data]);
    }

}