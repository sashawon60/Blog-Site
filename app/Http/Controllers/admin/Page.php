<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Page extends Controller
{
    //
    function listing(){
    	$data['result']=DB::table('pages')->orderBy('id','desc')->get();
    	return view('admin.page.list',$data);
    }

    function submit(Request $request){
    	$request->validate([
    		'name'=>'required',
			'slug'=>'required|unique:pages',
			'description'=>'required'
    	]);

    	$data=array(
    		'name'=>$request->input('name'),
    		'slug'=>$request->input('slug'),
    		'description'=>$request->input('description'),
			'status'=>1,
			'added_on'=>date('Y-m-d h:i:s')
		);
		
		DB::table('pages')->insert($data);
		$request->session()->flash('msg','Page Saved');
		return redirect('/admin/page/list');

    }

    function delete(Request $request, $id){
    	DB::table('pages')->where('id',$id)->delete();
    	$request->session()->flash('msg','Page Deleted');
		return redirect('/admin/page/list');
    }

    function edit(Request $request, $id){
    	$data['result']=DB::table('pages')->where('id',$id)->get();
    	return view('admin.page.edit',$data);
    }

    function update(Request $request, $id){
    	$request->validate([
    		'name'=>'required',
			'slug'=>'required',
			'description'=>'required'
    	]);

    	$data=array(
    		'name'=>$request->input('name'),
    		'slug'=>$request->input('slug'),
    		'description'=>$request->input('description'),
			'status'=>1,
			'added_on'=>date('Y-m-d h:i:s')
		);    	
		
		DB::table('pages')->where('id',$id)->update($data);
		$request->session()->flash('msg','Page Updated');
		return redirect('/admin/page/list');
    }
}
