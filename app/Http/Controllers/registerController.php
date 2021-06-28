<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\password_reset;
use Response;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Hash;
class registerController extends Controller
	{
		//register API
		public function register(Request $request){			
		  $errors_array = array();
		  $driver = array();
			if (!$request->has('name') || $request->input('name') == "")
				$errors_array['name'] = 'Name needs to have a value';

			if (!$request->has('email') || $request->input('email') == "")
			{
				$errors_array['email'] = 'Email is required';
			}
			else
			{
				$driver_email_exist = User::where('email', '=', $request->input('email'))
							->first();
				if(null !== $driver_email_exist){
					$errors_array['email'] = 'Email already registered';
				}
			}		
			if (!$request->has('equipmentId') || $request->input('equipmentId') == "")
			{
				$errors_array['equipmentId'] = 'Equipment Id is required';
			}
			else
			{
				$driver_email_exist = User::where('equipmentId', '=', $request->input('equipmentId'))->first();
				if(null !== $driver_email_exist){
					$errors_array['equipmentId'] = 'Equipment id already registered';
				}
			}
			if (!$request->has('phoneNumber') || $request->input('phoneNumber') == "")
			{
				$errors_array['phoneNumber'] = 'Phone Number is required';
			}
			else
			{
				$driver_email_exist = User::where('phoneNumber', '=', $request->input('phoneNumber'))->first();
				if(null !== $driver_email_exist){
					$errors_array['phoneNumber'] = 'Phone Number already registered';
				}
			}
				
			if (count($errors_array) == 0) {
				$user = new User();
				$user['name'] = $request->input('name');
				$user['email'] = $request->input('email');
				$user['role'] = '2';
				$user['image'] = '/public/images/dummy.png';
				$user['phoneNumber'] = $request->input('phoneNumber');
				$user['equipmentId'] = $request->input('equipmentId');	
				$user['confirm_token'] = str_random(20);
				$user['status'] = 0;				
				$user['login_counter'] = 0;				
				$user['password'] = Hash::make($request->input('password'));
				$user->save();
				
				$email = $request->input('email');
					$send_token_url = $this->confirmregurl($email);						
					Mail::to($email)->send(new SendMailable($send_token_url));
								
				return Response::json(array('error' => false, 'registration' => 'Account created successfully and verification email is sent to your Account', 'user' => $user), 202);
			 }  else {            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}	
			
		}
		
		//Login API
		public function login(Request $request){		
		  $errors_array = array();        
			if (!$request->has('email') || $request->input('email') == "")
				$errors_array['email'] = 'Email needs to have a value';
				
			if (count($errors_array) == 0){			
				$user = new User();
				$userdata = array();
				$user_email_exist = $user::where('email', '=', $request->input('email'))->first();				
				if(null !== $user_email_exist  && Hash::check($request->input('password'), $user_email_exist->password)){					
					if($user_email_exist->status != 0){
					$usercount = $user_email_exist->login_counter;					
					$userdata['id'] = $user_email_exist->id;
					$userdata['name'] = $user_email_exist->name;
					$userdata['email'] = $user_email_exist->email;
					$userdata['image'] = $user_email_exist->image;
					$userdata['phoneNumber'] = $user_email_exist->phoneNumber;
					$userdata['role'] = $user_email_exist->role;
					$userdata['location'] = $user_email_exist->location;
					$userdata['address'] = $user_email_exist->address;
					$userdata['date_of_birth'] = $user_email_exist->date_of_birth;
					$userdata['gender'] = $user_email_exist->gender;					
					$logintime = User::find($user_email_exist->id);					
					if($logintime->login_counter < 1){
							$confirm_user = new user();
							$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
							$data = $confirm_user::where('id', '=', $user_email_exist->id)->first();				
							$send_token_url = $protocol . $_SERVER['HTTP_HOST'].'/login_counter/'.$data->name;			
							Mail::to($data->email)->send(new SendMailable($send_token_url));						
							$logintime['login_counter'] = 1;
							$logintime->save();
					}else{
						$counter = $logintime->login_counter;
						$logintime['login_counter'] = $counter+1;
						$logintime->save();
					}
					return Response::json(array('error' => false, 'user' => 'User Logged in successfully', 'data'=> $userdata), 202);
					}else{
						return Response::json(array('error' => true, 'user' => 'Please confirm the user first'), 202);
					}
				}else{
					return Response::json(array('error' => true, 'user' => 'Invalid credentials'), 202);
				}			
			 }else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}				
		}		
	
		public function forgotpassword(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('email') || $request->input('email') == "")
				$errors_array['email'] = 'Email needs to have a value';			
			if (count($errors_array) == 0){					
				$user = new User();
				$password_reset = new password_reset();
				$user_email_exist = $user::where('email', '=', $request->input('email'))->first();
				if(null !== $user_email_exist){
						$data = $password_reset::where('email', '=', $request->input('email'))->first();
						if(null == $data){
							$password_reset['email'] = $request->input('email');
							$password_reset['token'] = str_random(60);
							$password_reset['created_at'] = Carbon::now();
							$password_reset->save();								
						}else{
							$reset = password_reset::find($data->id);
							$reset['email'] = $request->input('email');
							$reset['token'] = str_random(60);
							$reset['created_at'] = Carbon::now();
							$reset->save();						
						}
				$email = $request->input('email');
				$send_token_url = $this->urlgenerate($email);						
				Mail::to($email)->send(new SendMailable($send_token_url));
				$resetpass['token_link'] = $send_token_url;
				$error = false;
				}else{
					$resetpass['email'] = 'Email Does not exist!';
					$error = true;
				}
				return Response::json(array('error' => $error,  'user' => $resetpass), 202);
			 }else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}				
		}
		
		// Created link for fogot password with token
			public function urlgenerate($email){
			$password_reset = new password_reset();
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
			$data = $password_reset::where('email', '=', $email)->first();				
			return $protocol . $_SERVER['HTTP_HOST'].'/reset_password/'.$data->token;							
		}	
		public function confirmregurl($email){
			$confirm_user = new user();
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
			$data = $confirm_user::where('email', '=', $email)->first();				
			return $protocol . $_SERVER['HTTP_HOST'].'/confirm_user/'.$data->confirm_token;							
		}
		public function firsttimelogin($userid){
			$logintime = User::find($userid);
			if($logintime->login_counter < 1){
			$confirm_user = new user();
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
			$data = $confirm_user::where('id', '=', $userid)->first();				
			$testurl = $protocol . $_SERVER['HTTP_HOST'].'/login_counter'.$data->name;			
				Mail::to($data->email)->send(new SendMailable($testurl));
				$counter = $logintime->login_counter +1;
				$logintime['login_counter'] = $counter;
				$logintime->save();
			}
										
		}	
		
		//Reset Password function
		public function resetPassword($token){
			$password_reset = new password_reset();
			$driver_email_exist = $password_reset::where('token', '=', $token)->first();
			if(null != $driver_email_exist){
				$emailid = $driver_email_exist->email;
				$startTime = Carbon::parse($driver_email_exist['created_at']);
				$finishTime = Carbon::now();			
				$totalDuration = $finishTime->diffInSeconds($startTime);
				$hours = gmdate('H', $totalDuration);
					if($hours < 23){						
						return view('/resetpassword')->with('email',$emailid);
					}else{
						session()->flash('changepass','The token has been expired.');
						return redirect(url('login'));
					}
			}else{
				session()->flash('changepass','The token has been expired.');
				return redirect(url('/'));
			}
		}
		public function confirm_user($token){
			$user = new user();
			$email_exist = $user::where('confirm_token', '=', $token)->first();
			if(null != $email_exist){
				$email_exist['status'] = 1;
				$email_exist['confirm_token'] = '';
				$email_exist->save();
				session()->flash('confirmuser','Your account has been confirmed successfully.');
				return redirect(url('/'));
			}else{
				session()->flash('confirmuser','This token has been expired.');
				return redirect(url('/'));
			}			
		}
		
		//Change password function
		public function changesuccessfullypass($id){
			$userdetail = User::where('id', '=', $id)->first();
			session()->flash('changepass','Password changed successfully.');
			return view('resetpassword')->with('email',$userdetail->email);
		}
		public function passexpired($id){
			$userdetail = User::where('id', '=', $id)->first();
			session()->flash('changepass','This token has been expired.');
			return view('resetpassword')->with('email',$userdetail->email);
		}
		public function changepassword(Request $request){
			$email = $request->email;
			$userdetail = User::where('email', '=', $email)->first();
			$id = $userdetail->id;
			$checktoken = password_reset::where('email', '=', $email)->first();
			$changepass = User::find($userdetail->id);
			$tokencheck = $checktoken->token;
			$token = $checktoken->token;
			if($tokencheck != ""){
				$changepass->password = Hash::make($request->new_password);
				$changepass->save();
				$removetoken = password_reset::find($checktoken->id);
				$removetoken['token']= "";
				$removetoken->save();			
				return redirect(url('/changesuccessfullypass/'.$id.''));
			}else{				
				return redirect(url('/passexpired/'.$id.''));
			}			
		}
		
		//Register final function
		public function registerfinal(Request $request){
				$errors_array = array();        				
			if (!$request->has('user_id') || $request->input('user_id') == "")
				$errors_array['user_id'] = 'User id needs to have a value';
			
			if (!$request->has('date_of_birth') || $request->input('date_of_birth') == "")
				$errors_array['date_of_birth'] = 'Date of birth needs to have a value';	
			
			if (!$request->has('gender') || $request->input('gender') == "")
				$errors_array['gender'] = 'Gender needs to have a value';
			
			if (!$request->has('location') || $request->input('location') == "")
				$errors_array['location'] = 'Location needs to have a value';
			
			if (!$request->has('address') || $request->input('address') == "")
				$errors_array['address'] = 'Address needs to have a value';
			
			if (count($errors_array) == 0){				
				$user_id = $request->input('user_id');
				$userfinal = User::find($user_id);
				if($userfinal != null){
					$userfinal['date_of_birth'] = $request->input('date_of_birth');
					$userfinal['gender'] = $request->input('gender');
					$userfinal['location'] = $request->input('location');
					$userfinal['address'] = $request->input('address');
					if ($request->hasfile('image')) {			
						$image = $request->file('image');
						$name = time().'.'.$image->getClientOriginalExtension();
						$destinationPath = public_path('/usersimage');
						$image->move($destinationPath, $name);
						$imageurl = '/public/usersimage/'.$name;
						$userfinal['image'] = $imageurl;		   
					}
				$userfinal->save();
				return Response::json(array('error' => false, 'registration' => 'You have been successfully final registration', 'user' => $userfinal), 202);
				}else{
					return Response::json(array('error' => true, 'errors' => 'User is not exist! '), 200);
				}
			 }  else {            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}			
	}
