<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Hash;
use App\newspost;

class newsController extends Controller
{
	public function news(Request $request){
		$errors_array = array();		
		if (!$request->has('newstitle') || $request->input('newstitle') == "")
			$errors_array['newstitle'] = 'News title needs to have a value';
		
		if (!$request->has('news_description') || $request->input('news_description') == "")
			$errors_array['news_description'] = 'News description needs to have a value';
		
		if (count($errors_array) == 0){
			$newsdata = new newspost;
			$newsdata['news_title'] = $request->input('newstitle');
			$newsdata['news_description'] = $request->input('news_description');
			$newsdata['user_id'] = $request->input('user_id');
			$newsdata->save();
			return Response::json(array('error' => false, 'message' => 'News update successfully', 'data' => $newsdata), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}
	}
	
	public function allnews(){
		$errors_array = array();				
		$newsdata = new newspost;
		$allnews = $newsdata::all();
			if($allnews != null){			
			return Response::json(array('error' => false, 'message' => 'News display successfully', 'data' => $allnews), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}
	}
}
