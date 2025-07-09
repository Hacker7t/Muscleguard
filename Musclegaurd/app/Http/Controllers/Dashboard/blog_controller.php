<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class blog_controller extends Controller
{   
    public function index(){  
        $show_data = DB::table('blog')->get();
        return view('dashboard.blog.index', ['show_data' => $show_data]);
    }

    public function upload(Request $request) {

        $card_img = time(). '.' .$request->card->extension();
        $request->card->move(public_path('upload/blog_card_img'), $card_img);

        $data = array(
            'name' => $request->name,
            'card' => $card_img,
            'card_description' => $request->card_description,
            'description' => $request->description,
            'second_description' => $request->second_description,
            'writter_name' => $request->writter_name,
            'category' => $request->category,
            'qoute' => $request->qoute,
        );

        $insert_data = DB::table('blog')->insert($data);
        return redirect()->route('b_listing');
    }

    public function delete(Request $request){
        $id = $request->id;
        $insert_data = DB::table('blog')->where('id', $id)->delete();
        return redirect()->route('b_listing');
    }

    public function edit(Request $request){  
        $id = $request->id;
        $show_data = DB::table('blog')->find($id);
        return view('dashboard.blog.edit', ['show_data' => $show_data]);
    }

    public function update(Request $request) {
        $id = $request->id;
        
        $data = array(
            'name' => $request->name,
            'card_description' => $request->card_description,
            'description' => $request->description,
            'second_description' => $request->second_description,
            'writter_name' => $request->writter_name,
            'category' => $request->category,
            'qoute' => $request->qoute,
        );

        $add = DB::table('blog')->where('id', $id)->update($data);
        return redirect()->route('b_listing');
    }
    
    public function add(){  
        return view('dashboard.blog.write');
    }
}