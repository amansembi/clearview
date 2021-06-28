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
use App\technician_password_reset;
use App\technicianlatlng;
use App\accept_request;
use App\service_request;
use App\User;
use App\review;
use File;
use Log;
use stdClass;
use DB;

class technicianController extends Controller
{
    public function register(Request $request){
		  $errors_array = array();        
			if (!$request->has('name') || $request->input('name') == "")
				$errors_array['name'] = 'Name needs to have a value';
			
			if (!$request->has('email') || $request->input('email') == "")
			{
				$errors_array['email'] = 'Email is required';
			}
			else
			{
				$email_exist = technician::where('email', '=', $request->input('email'))->first();
				if(null !== $email_exist){
					$errors_array['email'] = 'Email already registered';
				}
			}
			
			if (!$request->has('phonenumber') || $request->input('phonenumber') == "")
				$errors_array['phonenumber'] = 'Phone needs to have a value';
			
			if (count($errors_array) == 0){			
				$technician = new technician();
				$technician['name'] = $request->input('name');
				$technician['email'] = $request->input('email');				
				$technician['phonenumber'] = $request->input('phonenumber');
				$technician['confirm_token'] = str_random(20);
				$technician['status'] = 0;	
				$technician['password'] = Hash::make($request->input('password'));
				$technician['latitude'] = $request->input('latitude');
				$technician['longitude'] = $request->input('longitude');
				$technician->save();
					$email = $request->input('email');
					$send_token_url = $this->confirmregurl($email);						
					Mail::to($email)->send(new SendMailable($send_token_url));
				return Response::json(array('error' => false, 'message' => 'You have been successfully registered.', 'data' =>$technician), 202);
				}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}		
	}
	public function confirmregurl($email){
			$confirm_technician = new technician();
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
			$data = $confirm_technician::where('email', '=', $email)->first();				
			return $protocol . $_SERVER['HTTP_HOST'].'/clearview/confirm_technician/'.$data->confirm_token;							
		}
		
	public function confirm_technician($token){
			$technician = new technician();
			$email_exist = $technician::where('confirm_token', '=', $token)->first();
			if(null != $email_exist){
				$email_exist['status'] = 1;
				$email_exist['confirm_token'] = '';
				$email_exist->save();
				session()->flash('confirmtechnician','Technician confirmed successfully.');
				return redirect(url('/'));
			}else{
				session()->flash('confirmtechnician','Tokan expired .');
				return redirect(url('/'));
			}			
		}
	 public function login(Request $request){
		  $errors_array = array();        
			if (!$request->has('email') || $request->input('email') == "")
				$errors_array['email'] = 'Email needs to have a value';
					
			
			if (count($errors_array) == 0){					
				$technician = new technician();
				$technician_email_exist = $technician::where('email', '=', $request->input('email'))->first();
					if(null !== $technician_email_exist && Hash::check($request->input('password'), $technician_email_exist->password)){
						if($technician_email_exist->status != 0){
						return Response::json(array('error' => false, 'technician' => 'Technician Logged in successfully', 'data'=>$technician_email_exist), 202);
						}else{
							return Response::json(array('error' => true, 'technician' => 'Please confirm the Technician first'), 200);
						}
					}else{
						return Response::json(array('error' => true, 'technician' => 'Invalid credentials'), 202);
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
				$user = new technician();
				$password_reset = new technician_password_reset();
				$user_email_exist = $user::where('email', '=', $request->input('email'))->first();
				if(null !== $user_email_exist){
						$data = $password_reset::where('email', '=', $request->input('email'))->first();
						if(null == $data){
							$password_reset['email'] = $request->input('email');
							$password_reset['token'] = str_random(60);
							$password_reset['created_at'] = Carbon::now();
							$password_reset->save();								
						}else{
							$reset = technician_password_reset::find($data->id);
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
					$resetpass['email'] = 'Email Dose not exist!';
					$error = true;
				}
				return Response::json(array('error' => $error,  'user' => $resetpass), 202);
			 }else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}				
		}
		public function urlgenerate($email){
			$password_reset = new technician_password_reset();
			$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
			$data = $password_reset::where('email', '=', $email)->first();				
			return $protocol . $_SERVER['HTTP_HOST'].'/clearview/technician_reset_password/'.$data->token;							
		}
		public function resetPassword($token){
			$password_reset = new technician_password_reset();
			$email_exist = $password_reset::where('token', '=', $token)->first();
			if(null != $email_exist){
				$emailid = $email_exist->email;
				$startTime = Carbon::parse($email_exist['created_at']);
				$finishTime = Carbon::now();			
				$totalDuration = $finishTime->diffInSeconds($startTime);
				$hours = gmdate('H', $totalDuration);
			if($hours < 23){							
				return view('/technicianresetpassword')->with('email',$emailid);
			}else{
				session()->flash('changepass','Token is expired, Please generate the new token.');
				return redirect(url('login'));
			}
			}else{
				session()->flash('changepass','Token is expired, Please generate the new token.');
				return redirect(url('/'));
			}
		}
		public function changepassword(Request $request){
			$email = $request->email;
			$userdetail = technician::where('email', '=', $email)->first();
			$changepass = technician::find($userdetail->id);
			$changepass->password = Hash::make($request->new_password);
			$changepass->save();			
			$request->session()->flash('techchangepass','Password change successfully');
			return redirect(url('/')); 
		}
		
		 public function technicianOnline(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
			
			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			
			if (count($errors_array) == 0){	
							
					$location = technicianlatlng::where('technician_id', '=', $request->input('technician_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$techlocation = technicianlatlng::find($latlogid);					
					$techlocation->technicianStatus = '1';
					$techlocation->latitude = $request->input('latitude');
					$techlocation->longitude = $request->input('longitude');
					$techlocation->save();
					return Response::json(array('error' => false, 'message'=>'Technician updated successfully', 'technicianLocation' => $techlocation), 202);
				}else{
					$locations = new technicianlatlng();
					$locations->technician_id = $request->input('technician_id');
					$locations->technicianStatus ='1';
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'Technician updated successfully', 'technicianLocation' => $locations), 202);
				}
			
				return Response::json(array('error' => true, 'technician' => 'Invalid credentials'), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		 }	 
		 
		 public function technicianOffline(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
						
			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			
			if (count($errors_array) == 0){	
							
					$location = technicianlatlng::where('technician_id', '=', $request->input('technician_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$techlocation = technicianlatlng::find($latlogid);					
					$techlocation->technicianStatus = '0';
					$techlocation->latitude = $request->input('latitude');
					$techlocation->longitude = $request->input('longitude');
					$techlocation->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $techlocation), 202);
				}else{
					$locations = new technicianlatlng();
					$locations->technician_id = $request->input('technician_id');
					$locations->technicianStatus ='0';
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $locations), 202);
				}
			
				return Response::json(array('error' => true, 'technician' => 'Invalid credentials'), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		 }	
		 
		public function technicianOnSite(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
	
			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			//array("1" => "Pending", "2" => "Open",	"3" => "Completed");
			if (count($errors_array) == 0){	
							
					$location = technicianlatlng::where('technician_id', '=', $request->input('technician_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$techlocation = technicianlatlng::find($latlogid);					
					$techlocation->technicianStatus = '3';
					$techlocation->latitude = $request->input('latitude');
					$techlocation->longitude = $request->input('longitude');
					$techlocation->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $techlocation), 202);
				}else{
					$locations = new technicianlatlng();
					$locations->technician_id = $request->input('technician_id');
					$locations->technicianStatus ='3';
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $locations), 202);
				}
			
				return Response::json(array('error' => true, 'technician' => 'Invalid credentials'), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		 }
		 
		public function technicianEnRoute(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
						
			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';	
		
			if (!$request->has('accept_id') || $request->input('accept_id') == "")
				$errors_array['accept_id'] = 'Accept id needs to have a value';			
		//	array("1" => "Pending", "2" => "Open",	"3" => "Completed");
			if (count($errors_array) == 0){	
							
					$location = technicianlatlng::where('technician_id', '=', $request->input('technician_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$techlocation = technicianlatlng::find($latlogid);					
					$techlocation->technicianStatus = '2';
					$techlocation->latitude = $request->input('latitude');
					$techlocation->longitude = $request->input('longitude');
					$techlocation->request_id = $request->input('request_id');
					$techlocation->accept_id = $request->input('accept_id');
					$techlocation->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $techlocation), 202);
				}else{
					$locations = new technicianlatlng();
					$locations->technician_id = $request->input('technician_id');
					$locations->technicianStatus = '2';
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->request_id = $request->input('request_id');
					$locations->accept_id = $request->input('accept_id');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'Technician status updated successfully', 'technicianLocation' => $locations), 202);
				}			
				return Response::json(array('error' => true, 'technician' => 'Invalid credentials'), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}	
		
		public function technicianById(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';							
			
			if (count($errors_array) == 0){							
					$technicianbyid = technician::where('id', '=', $request->input('technician_id'))->first();
				if($technicianbyid != null){
				return Response::json(array('error' => false, 'message'=>'Technician detail successfully', 'data' => $technicianbyid), 202);
				}else{
				return Response::json(array('error' => true, 'errors' => 'Technician not found!'), 200);
				}				
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function completedRequests(Request $request){
				$errors_array = array();        
				$completeRequests = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';							
			
			if (count($errors_array) == 0){							
					// $technicianbyid = accept_request::where('technician_id', '=', $request->input('technician_id'))->get();
					// foreach($technicianbyid as $allacceptreq){						
						// $completeReq = service_request::where('id', '=', $allacceptreq->request_id)
																					// ->where('status', '=', '3')
																					// ->get();	
				$productCategory = accept_request::where('technician_id', $request->input('technician_id'))
						 ->where('accept_requests.status', '=', '3')
					->join('service_requests', 'accept_requests.request_id', '=', 'service_requests.id')
					->select('accept_requests.*','service_requests.equipment_id')->get();
					
					
			return Response::json(array('error' => false, 'message'=>'Completed Requests', 'data' => $productCategory), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function workStartTime(Request $request){
				$errors_array = array();        
				$completeRequests = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
			
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
			
			if (!$request->has('work_start_time') || $request->input('work_start_time') == "")
				$errors_array['work_start_time'] = 'Work start time needs to have a value';
			
			if (count($errors_array) == 0){			
				$accept_request = accept_request::where('request_id', '=', $request->input('request_id'))->first();
				$accept_request->work_start_time = $request->input('work_start_time');
				$accept_request->save();				
					
				return Response::json(array('error' => false, 'message'=>'Start Work Time', 'data' => $accept_request), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function reachedTime(Request $request){
			
				$errors_array = array();        
				$completeRequests = array();   
					$accept_request = new accept_request();
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';	
			
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
			
			if (!$request->has('reached_time') || $request->input('reached_time') == "")
				$errors_array['reached_time'] = 'Reached time needs to have a value';
			
			if (!$request->has('reached_travel_latLong') || $request->input('reached_travel_latLong') == "")
				$errors_array['reached_travel_latLong'] = 'Reached travel latLong needs to have a value';
			
			if (count($errors_array) == 0){			
				$accept_request = $accept_request::where('request_id', '=', $request->input('request_id'))->first();
				
				$accept_request->reached_time = $request->input('reached_time');
				$accept_request->reached_travel_latLong = $request->input('reached_travel_latLong');
				$start_time = $accept_request->start_travel_latLong;
				$reached_latLong = $request->input('reached_travel_latLong');
				 $startdest = explode(',',$start_time);
					$start_latitude = $startdest[0];
					$start_longitude  = $startdest[1];
					
					$reacheddest = explode(',',$reached_latLong);
					$reach_latitude = $reacheddest[0];
					$reach_longitude  = $reacheddest[1];
				
				$retResult = $this->distance($start_latitude, $start_longitude, $reach_latitude, $reach_longitude, "M");
				
				$accept_request->travel_miles = $retResult;
				
				
				$accept_request->save();				
					
				return Response::json(array('error' => false, 'message'=>'Reached time Added', 'data' => $accept_request), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function totalTravelTime(Request $request){
				$errors_array = array();        
				$completeRequests = array();
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
			
			if (count($errors_array) == 0){			
				$accept_request = accept_request::where('request_id', '=', $request->input('request_id'))->first();
				$startTime = $accept_request->start_time;
				$finishTime = $accept_request->reached_time;
					$start  = new Carbon($startTime);
					$end    = new Carbon($finishTime);
					$total_time = $start->diff($end)->format('%H:%I:%S');
					//print_r($total_time);
					$accept_request->total_time_reach = $total_time;
					$accept_request->save();					
				return Response::json(array('error' => false, 'message'=>'Total Travel Time', 'data' => $accept_request), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function totalWorkTime(Request $request){
				$errors_array = array();        
				$completeRequests = array();  
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
			
			if (count($errors_array) == 0){			
				$accept_request = accept_request::where('request_id', '=', $request->input('request_id'))->first();
				$startTime = $accept_request->work_start_time;
				$finishTime = $accept_request->work_end_time;
					$start  = new Carbon($startTime);
					$end    = new Carbon($finishTime);
					$total_time = $start->diff($end)->format('%H:%I:%S');	
						//print_r($total_time);
					$accept_request->total_time_work = $total_time;
					$accept_request->save();					
				return Response::json(array('error' => false, 'message'=>'Total Work Time.', 'data' => $accept_request), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function distanceByLatLong(Request $request){
				$errors_array = array();        
				$dest = array();  
				$tech_nearby = array();  
			if (!$request->has('user_id') || $request->input('user_id') == "")
				$errors_array['user_id'] = 'User id needs to have a value';
			
			if (count($errors_array) == 0){			
				$accept_request = User::where('id', '=', $request->input('user_id'))->first();
				$technicianOnline = technicianlatlng::where('technicianStatus', '=', '2')->get();
				$origin = $accept_request->location;
				  $userdest = explode(',',$origin);
					$user_latitude = $userdest[0];
					$user_longitude  = $userdest[1];
				
				foreach($technicianOnline as $tech){
					$let = $tech->latitude;
					$long = $tech->longitude;
					$arr = array($let,$long);
					$destination = implode(",",$arr);					
					$retResult = $this->distance($user_latitude, $user_longitude, $let, $long, "M");
					$technicianId = $tech->technician_id;
					$technicians = technician::find($technicianId);
					$tech_nearby['TechId'] = $technicians->id;
					$tech_nearby['techName'] = $technicians->name;
					$tech_nearby['techEmail'] = $technicians->email;					
					$tech_nearby['techStatus'] = $tech->technicianStatus;					
					$tech_nearby['techPhonenumber'] = $technicians->phonenumber;	
					$tech_nearby['userName'] = $accept_request->name;	
					$tech_nearby['userEmail'] = $accept_request->email;	
					$tech_nearby['userPhone'] = $accept_request->phoneNumber;	
					$tech_nearby['userDateOfBirth'] = $accept_request->date_of_birth;						
					$tech_nearby['techLet'] = $let;
					$tech_nearby['techLong'] = $long;
					$tech_nearby['userLet'] = $user_latitude;
					$tech_nearby['userLong'] = $user_longitude;
					$tech_nearby['distance'] = $retResult;
					$dest[] = $tech_nearby;
				}				
				return Response::json(array('error' => false, 'message'=>'Distance calculator successfully', 'data' => $dest), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
		
		public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
				  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
					return 0;
				  }
				  else {
					$theta = $lon1 - $lon2;
					$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
					$dist = acos($dist);
					$dist = rad2deg($dist);
					$miles = $dist * 60 * 1.1515;
					$unit = strtoupper($unit);

					if ($unit == "K") {
					  return ($miles * 1.609344);
					} else if ($unit == "N") {
					  return ($miles * 0.8684);
					} else {
					  return $miles;
					}
				  }
				}
				
		public function reviews(Request $request){
			$errors_array = array();        
			if (!$request->has('star') || $request->input('star') == "")
				$errors_array['star'] = 'Star needs to have a value';
			
			if (!$request->has('note') || $request->input('note') == "")
				$errors_array['note'] = 'Note needs to have a value';
			
			if (!$request->has('on_site_time') || $request->input('on_site_time') == "")
				$errors_array['on_site_time'] = 'On site time needs to have a value';
			
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician id needs to have a value';
			
			if (!$request->has('service_code') || $request->input('service_code') == "")
				$errors_array['service_code'] = 'Service code	needs to have a value';
			if (count($errors_array) == 0){
				$reviews = new review();
				$reviews->on_site_time	= $request->on_site_time;
				$reviews->star	= $request->star;
				$reviews->note	= $request->note;
				$reviews->technician_id	= $request->technician_id;
				$reviews->service_code	= $request->service_code;
				$reviews->save();
				return Response::json(array('error' => false, 'message'=>'Review submited successfully', 'data' => $reviews), 202);
			}else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}		

// echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
// echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
// echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
}
