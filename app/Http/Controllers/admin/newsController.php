<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\newspost;
use Illuminate\Support\Facades\Auth;
class newsController extends Controller
{
    public function news(Request $request){
		return view('admin/news/newspost');		
	}
	
	public function addnews(Request $request){
		$user = auth()->user(); 
		$newspost = new newspost(); 
		$newspost['news_title'] = $request->newstitle;
		$newspost['news_description'] = $request->news_dscription;
		$newspost['user_id'] = $user->id;
		if ($request->hasfile('news_image')) {			
						$image = $request->file('news_image');
						$name = time().'.'.$image->getClientOriginalExtension();
						$destinationPath = public_path('/images/newsimage');
						$image->move($destinationPath, $name);
						$imageurl = '/public/images/newsimage/'.$name;
						$newspost['image'] = $imageurl;		   
					}
		$newspost->save();
		$request->session()->flash('newsupdate','Update news successfully');			
    	return redirect('/admin/news');		
	}
}
