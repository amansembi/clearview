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

class contactusController extends Controller
{
    public function contactus(Request $request){
		  $errors_array = array();     
				$messageval ="";  
			if (!$request->has('name') || $request->input('name') == "")
				$errors_array['name'] = 'Name needs to have a value';
			
			if (!$request->has('email') || $request->input('email') == "")
				$errors_array['email'] = 'Email needs to have a value';
			
			if (!$request->has('phone') || $request->input('phone') == "")
				$errors_array['phone'] = 'Phone needs to have a value';
			
			if (count($errors_array) == 0){			
				$contactus = new contact();
				$mytime = Carbon::now();
				$mydate=  Carbon::today()->toDateString();					
				$format = $mytime->format('H:i:s');											
				$contactus['name'] = $request->input('name');
				$contactus['email'] = $request->input('email');
				$contactus['subject'] = $request->input('subject');
				$contactus['phone'] = $request->input('phone');
				$contactus['message'] = $request->input('message');
				$details = array(
								   'name' => $request->input('name'),
								   'email' => $request->input('email'),
								   'subject' => $request->input('subject'),
								   'phone' => $request->input('phone'),
								   'messageval' => $request->input('message'),
								   'date' => $mydate,
								   'time' => $format,
							   );					
				 Mail::send('emails.thanks', $details, function($message) {
				 $message->to('digimantralabmohali@gmail.com', 'Contact us')->subject('Contact form mail');				 
			  }); 		 
				$contactus->save();
				return Response::json(array('error' => false, 'message' => 'We will contact you soon.', 'data' =>$contactus), 202);
				}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}		
	}
}
