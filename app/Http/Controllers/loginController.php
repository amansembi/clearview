<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Response;
use Auth;
class loginController extends Controller
{
   public function login(Request $request){
		
      $errors_array = array();        
        if (!$request->has('email') || $request->input('email') == "")
            $errors_array['email'] = 'Email needs to have a value';
		
		if (!$request->has('password') || $request->input('password') == "")
            $errors_array['password'] = 'Password needs to have a value';
		
		if (count($errors_array) == 0){			
			$user = new User();
			$user_email_exist = $user::where('email', '=', $request->input('email'))->first();
			$user_password_exist = $user::where('password', '=', md5($request->input('password')))->first();
			if(null !== $user_email_exist && null !== $user_password_exist){
                return Response::json(array('error' => false, 'user' => 'User Logged in succefully'), 202);
            }
			else{
				return Response::json(array('error' => true, 'user' => 'Invalid credentials'), 202);
			}			
         }else{            
            return Response::json(array('error' => true, 'errors' => $errors_array), 200);
        }	
		
	}
}
