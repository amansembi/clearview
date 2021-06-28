<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Hash;
use App\contact;
use App\technician;
use App\userchat;

class chatController extends Controller
{
    public function enterchat(Request $request)
    {
       $errors_array = array();
        if (!$request->has('sender_id') || $request->input('sender_id') == "")
            $errors_array['sender_id'] = 'Sender id is required';

        if (!$request->has('receiver_id') || $request->input('receiver_id') == "")
            $errors_array['receiver_id'] = 'Receiver id is required'; 
		
		if (!$request->has('message') || $request->input('message') == "")
            $errors_array['message'] = 'Message value is required';
		
		if (!$request->has('chat_time') || $request->input('chat_time') == "")
            $errors_array['chat_time'] = 'Chat time is required';
		
		if (!$request->has('type') || $request->input('type') == "")
            $errors_array['type'] = 'Type is required';
		
		if (!$request->has('is_sent') || $request->input('is_sent') == "")
            $errors_array['is_sent'] = 'Is sent is required?';
        
		if (!$request->has('is_recieved') || $request->input('is_recieved') == "")
            $errors_array['is_recieved'] = 'Is recieved is required?';
		
		if (count($errors_array) == 0) 
		{
			$Chat = new userchat;
			$sender_id = $request->input('sender_id');
			$receiver_id = $request->input('receiver_id');
			$thread_id_array = userchat::select('id','thread_id')
						->where([['sender_id', '=', $sender_id],['receiver_id', '=', $receiver_id]])
						->orWhere([['receiver_id', '=', $sender_id],['sender_id', '=', $receiver_id]])->first();
			if(null !== $thread_id_array){
				if(null !== $thread_id_array->thread_id)
				{
					$thread_id_pre = $thread_id_array->thread_id;
				}
				$Chat->sender_id = $request->input('sender_id');
				$Chat->receiver_id = $request->input('receiver_id');
				$Chat->message = $request->input('message');
				$Chat->chat_time = $request->input('chat_time');
				$Chat->type = $request->input('type');
				$Chat->is_sent = $request->input('is_sent');
				$Chat->is_recieved = $request->input('is_recieved');
				 $image = $request->file('image');			
			if ($request->hasfile('image'))
			{			
				$image = $request->file('image');
				$name = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/images/chatimages');
				$image->move($destinationPath, $name);
				$imageurl = '/public/images/chatimages/'.$name;
				$Chat->image = $imageurl;		   
			}
				$Chat->save();
				$exist_thread_id = userchat::select('id','thread_id')->orderBy('updated_at', 'desc')->first();
				$thread_id_val = $exist_thread_id->thread_id;	
				$id = $exist_thread_id->id;				
			if(null == $thread_id_val)
			{
				userchat::where('id','=', $id)  
				->limit(1)  
				->update(array('thread_id' => $thread_id_pre));
			}		
			}else{
				
				$Chat->sender_id = $request->input('sender_id');
				$Chat->receiver_id = $request->input('receiver_id');
				$Chat->message = $request->input('message');
				$Chat->chat_time = $request->input('chat_time');
				$Chat->type = $request->input('type');
				$Chat->is_sent = $request->input('is_sent');
				$Chat->is_recieved = $request->input('is_recieved');
				 $image = $request->file('image');
			 if($image)
			 {
				$name = $image->getClientOriginalName();
				$destinationPath = public_path('/uploads/');
				$image->move($destinationPath, $name);
				$imagePath = '/uploads/'.$name;
				$Chat->image = $imagePath;
			}
				$Chat->save();
				$thread_id = userchat::select('id','thread_id')->orderBy('updated_at', 'desc')->first();
				$thread_id_val = $thread_id->thread_id;
				$id = $thread_id->id;				
				if(null == $thread_id_val){
				userchat::where('id','=', $id)  
				->limit(1)  
				->update(array('thread_id' => $id)); 
				}
			}
			return Response::json(array('error' => false, 'requests' => $Chat), 202);
		}
		else {
			 return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}
    }
	
	public function chatList(Request $request)
    {
       $errors_array = array();
	   if (!$request->has('current_user') || $request->input('current_user') == "")
            $errors_array['current_user'] = 'Current user id is required';       
      
		if (count($errors_array) == 0) {
			$Chat = new userchat;
			$user_id = $request->input('current_user');
			$chat_list = userchat::select('*')
						->where('receiver_id','=',$user_id)
						->orwhere('sender_id','=',$user_id)
						->get();
			$chatlistArray = $chat_list->groupby('thread_id');			
			return Response::json(array('error' => false, 'requests' => $chatlistArray), 202);
		}
		else {
			 return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}
    }
	
}
