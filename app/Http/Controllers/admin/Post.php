<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Post extends Controller
{
    //
    function listing(){
    	$data['result']=DB::table('posts')->orderBy('id','desc')->get();
    	return view('admin.post.list',$data);
    }

    function submit(Request $request){
    	$request->validate([
    		'title'=>'required',
            'slug'=>'required|unique:posts',
			'short_desc'=>'required',
			'long_desc'=>'required',
			'image'=>'required|mimes:jpg,jpeg,png',
			'post_date'=>'required'
    	]);

    	//$image=$request->file('image')->store('public/post');
    	$image=$request->file('image');
    	$ext=$image->extension();
    	$file=time().'.'.$ext;
    	$image->storeAs('public/post',$file);

    	$data=array(
    		'title'=>$request->input('title'),
            'slug'=>$request->input('slug'),
    		'short_desc'=>$request->input('short_desc'),
    		'long_desc'=>$request->input('long_desc'),
    		'image'=>$file,
    		'post_date'=>$request->input('post_date'),
			'status'=>1,
			'added_on'=>date('Y-m-d h:i:s')
		);
		
		DB::table('posts')->insert($data);
		$request->session()->flash('msg','Post Saved');
		return redirect('/admin/post/list');

    }

    function delete(Request $request, $id){
    	DB::table('posts')->where('id',$id)->delete();
    	$request->session()->flash('msg','Post Deleted');
		return redirect('/admin/post/list');
    }

    function edit(Request $request, $id){
    	$data['result']=DB::table('posts')->where('id',$id)->get();
    	return view('admin.post.edit',$data);
    }

    function update(Request $request, $id){
    	$request->validate([
    		'title'=>'required',
            'slug'=>'required|unique:posts',
			'short_desc'=>'required',
			'long_desc'=>'required',
			'image'=>'mimes:jpg,jpeg,png',
			'post_date'=>'required'
    	]);

    	$data=array(
    		'title'=>$request->input('title'),
            'slug'=>$request->input('slug'),
    		'short_desc'=>$request->input('short_desc'),
    		'long_desc'=>$request->input('long_desc'),
    		//'image'=>$file,
    		'post_date'=>$request->input('post_date'),
			'status'=>1,
			'added_on'=>date('Y-m-d h:i:s')
		);

    	if ($request->hasfile('image')) {
    		//$image=$request->file('image')->store('public/post');
	    	$image=$request->file('image');
	    	$ext=$image->extension();
	    	$file=time().'.'.$ext;
	    	$image->storeAs('public/post',$file);
	    	$data['image']=$file;
    	}
    	
		
		DB::table('posts')->where('id',$id)->update($data);
		$request->session()->flash('msg','Post Updated');
		return redirect('/admin/post/list');
    }
    
}
