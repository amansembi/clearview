<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Hash;
use App\service_request;
use App\accept_request;
use App\User;
class serviceRequestController extends Controller
{
	public function serviceRequest(Request $request){
			$errors_array = array();
			$driver = array();
			$serviceRequest = new service_request();
		if (!$request->has('equipment_id') || $request->input('equipment_id') == "")
			$errors_array['equipment_id'] = 'Equipment ID needs to have a value';
		
		if (!$request->has('contact_name') || $request->input('contact_name') == "")
			$errors_array['contact_name'] = 'Contact name needs to have a value';
		
		if (!$request->has('phone_number') || $request->input('phone_number') == "")
			$errors_array['phone_number'] = 'Phone number needs to have a value';
		
		if (!$request->has('email') || $request->input('email') == "")
			$errors_array['email'] = 'Email needs to have a value';
		
		if (count($errors_array) == 0){
			$serviceRequest->equipment_id = $request->input('equipment_id');
			$serviceRequest->contact_name = $request->input('contact_name');
			$serviceRequest->phone_number = $request->input('phone_number');
			$serviceRequest->email = $request->input('email');
			$serviceRequest->service_code = $request->input('service_code');
			$serviceRequest->service_type = 'mechanical_problem';			
			$serviceRequest->notes = $request->input('notes');
			$serviceRequest->status = '1';		
			$serviceRequest->address = $request->input('address');
			$serviceRequest->location = $request->input('location');
			$serviceRequest->user_id = $request->input('user_id');
			$serviceRequest->save();				
			return Response::json(array('error' => false, 'message' => 'Request updated successfully', 'request' => $serviceRequest), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
	
	public function tonerorder(Request $request){
			$errors_array = array();
			$driver = array();
			$serviceRequest = new service_request();
		if (!$request->has('equipment_id') || $request->input('equipment_id') == "")
			$errors_array['equipment_id'] = 'Equipment ID needs to have a value';
		
		if (!$request->has('contact_name') || $request->input('contact_name') == "")
			$errors_array['contact_name'] = 'Contact name needs to have a value';
		
		if (!$request->has('phone_number') || $request->input('phone_number') == "")
			$errors_array['phone_number'] = 'Phone number needs to have a value';
		
		if (!$request->has('email') || $request->input('email') == "")
			$errors_array['email'] = 'Email needs to have a value';
		
		if (count($errors_array) == 0){
			$serviceRequest->equipment_id = $request->input('equipment_id');
			$serviceRequest->contact_name = $request->input('contact_name');
			$serviceRequest->phone_number = $request->input('phone_number');
			$serviceRequest->email = $request->input('email');
			$serviceRequest->service_code = $request->input('service_code');
			$serviceRequest->service_type = 'toner_order';			
			$serviceRequest->notes = $request->input('notes');
			$serviceRequest->status = '1';			
				if ($request->hasfile('image')) {			
					$image = $request->file('image');
					$name = time().'.'.$image->getClientOriginalExtension();
					$destinationPath = public_path('/images/tonerorderimage');
					$image->move($destinationPath, $name);
					$imageurl = '/public/images/tonerorderimage/'.$name;
					$serviceRequest->image = $imageurl;		   
				}else{
					$serviceRequest->image = '';
				}			
			$serviceRequest->address = $request->input('address');
			$serviceRequest->location = $request->input('location');
			$serviceRequest->user_id = $request->input('user_id');
			$serviceRequest->save();				
			return Response::json(array('error' => false, 'message' => 'Toner order successfully', 'request' => $serviceRequest), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
	
	public function enterameter(Request $request){
			$errors_array = array();
			
			$serviceRequest = new service_request();
		if (!$request->has('equipment_id') || $request->input('equipment_id') == "")
			$errors_array['equipment_id'] = 'Equipment ID needs to have a value';
		
		if (!$request->has('contact_name') || $request->input('contact_name') == "")
			$errors_array['contact_name'] = 'Contact name needs to have a value';
		
		if (!$request->has('phone_number') || $request->input('phone_number') == "")
			$errors_array['phone_number'] = 'Phone number needs to have a value';
		
		if (!$request->has('email') || $request->input('email') == "")
			$errors_array['email'] = 'Email needs to have a value';
		
		if (count($errors_array) == 0){
			$serviceRequest->equipment_id = $request->input('equipment_id');
			$serviceRequest->contact_name = $request->input('contact_name');
			$serviceRequest->phone_number = $request->input('phone_number');
			$serviceRequest->email = $request->input('email');
			$serviceRequest->service_code = $request->input('service_code');
			$serviceRequest->bvmeter = $request->input('BVmeter');
			$serviceRequest->colormeter = $request->input('colormeter');
			$serviceRequest->service_type = 'enter_a_meter';			
			$serviceRequest->notes = $request->input('notes');
			$serviceRequest->status = '1';
			$serviceRequest->address = $request->input('address');
			$serviceRequest->location = $request->input('location');
			$serviceRequest->user_id = $request->input('user_id');
			$serviceRequest->save();				
			return Response::json(array('error' => false, 'message' => 'Meter added successfully', 'request' => $serviceRequest), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
	public function allrequests(){
			$serviceRequest = new service_request();
			$reqstatus = array("1" => "Pending", "2" => "Open",	"3" => "Completed");
			$allserviceReq = $serviceRequest::all();
			$allservice = array();
			$services = array();
			
			foreach($allserviceReq as $allreq){
				 if ($allreq->id){
					$allservice['id'] = $allreq->id;
					$allservice['equipment_id'] = $allreq->equipment_id;
					$allservice['contact_name'] = $allreq->contact_name;
					$allservice['phone_number'] = $allreq->phone_number;
					$allservice['email'] = $allreq->email;
					$allservice['service_code'] = $allreq->service_code;
					$allservice['bvmeter'] = $allreq->bvmeter;
					$allservice['colormeter'] = $allreq->colormeter;
					$allservice['service_type'] = $allreq->service_type;
					$allservice['address'] = $allreq->address;
					$allservice['location'] = $allreq->location;
					$allservice['notes'] = $allreq->notes;
					$allservice['user_id'] = $allreq->user_id;
					$allservice['status'] = $allreq->status;
					$allservice['arrival_time'] = $allreq->arrival_time;
					$allservice['reached_time'] = $allreq->reached_time;
					$services[] = $allservice;
				 }				 
			}
			
				if($services != null){
					return Response::json(array('error' => false, 'message' => 'All requests', 'requests' => $services), 202);
				}else{            
					return Response::json(array('error' => false, 'message' => 'No Requests available at the moment, Please try again later','requests' => $services), 200);
				}					
		}
		
	public function accept_request(Request $request){
				$errors_array = array();							
		if (!$request->has('request_id') || $request->input('request_id') == "")
			{
				$errors_array['request_id'] = 'Request id is required';
			}
			else
			{
				$request_id_exist = accept_request::where('request_id', '=', $request->input('request_id'))
							->first();
				if(null !== $request_id_exist){
				$errors_array['request_id'] = 'Request already accepted';
				}
			}
		
		if (!$request->has('contact_name') || $request->input('contact_name') == "")
			$errors_array['contact_name'] = 'Contact name needs to have a value';
		
		if (!$request->has('technician_id') || $request->input('technician_id') == "")
			$errors_array['technician_id'] = 'Technician id needs to have a value';
		
		if (!$request->has('phone_number') || $request->input('phone_number') == "")
			$errors_array['phone_number'] = 'Phone number needs to have a value';
		
		if (!$request->has('email') || $request->input('email') == "")
			$errors_array['email'] = 'Email needs to have a value';
		
		if (!$request->has('service_code') || $request->input('service_code') == "")
			$errors_array['service_code'] = 'Service code needs to have a value';
		
		if (!$request->has('start_travel_latLong') || $request->input('start_travel_latLong') == "")
			$errors_array['start_travel_latLong'] = 'Start travel latLong needs to have a value';
		
		if (count($errors_array) == 0){
			$acceptRequest = new accept_request();
				$acceptRequest->request_id  = $request->input('request_id');
				$acceptRequest->contact_name = $request->input('contact_name');
				$acceptRequest->phone_number = $request->input('phone_number');
				$acceptRequest->email = $request->input('email');
				$acceptRequest->service_code = $request->input('service_code');					
				$acceptRequest->notes = $request->input('notes');
				$acceptRequest->technician_id = $request->input('technician_id');
				$acceptRequest->status = '2';
				$acceptRequest->start_time = $request->input('start_time');
				$acceptRequest->start_travel_latLong = $request->input('start_travel_latLong');
				//$acceptRequest->reached_time = $request->input('reached_time');
				$acceptRequest->save();				
			return Response::json(array('error' => false, 'message' => 'Accept request successfully', 'request' => $acceptRequest), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
		
	public function customer_detail(Request $request){
			$errors_array = array();		
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
			
			if (count($errors_array) == 0){							
				$request_user_id = service_request::where('id', '=', $request->input('request_id'))->first();
				$user_id = $request_user_id->user_id;
				$request_user_id = User::where('id', '=', $user_id)->first();
			return Response::json(array('error' => false, 'message' => 'Accept request successfully', 'request' => $request_user_id), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
	public function work_complete(Request $request){
			$errors_array = array();		
			if (!$request->has('request_id') || $request->input('request_id') == "")
				$errors_array['request_id'] = 'Request id needs to have a value';
		
			if (!$request->has('notes') || $request->input('notes') == "")
				$errors_array['notes'] = 'Notes needs to have a value';
			
			if (count($errors_array) == 0){
				$request_detail = service_request::find($request->input('request_id'));				
				$request_detail->notes = $request->input('notes');			
				$request_detail->status = '3';
				$accept_request = accept_request::where('request_id', '=', $request->input('request_id'))->first();
				$accept_request->work_end_time = $request->input('work_end_time');
				$accept_request->status = '3' ;				
				$startTravelTime = $accept_request->start_time;
				$finishTravelTime = $accept_request->reached_time;
				$startTTime  = new Carbon($startTravelTime);
				$finishTTime    = new Carbon($finishTravelTime);
				$total_Travel_time = $startTTime->diff($finishTTime)->format('%H:%I:%S');
				$accept_request->total_time_reach = $total_Travel_time;
				$startTime = $accept_request->work_start_time;				
				$finishTime = $request->input('work_end_time');
				//$finishTime = $accept_request->work_end_time;
				$start  = new Carbon($startTime);
				$end    = new Carbon($finishTime);
				$total_time = $start->diff($end)->format('%H:%I:%S');				
				$accept_request->total_time_work = $total_time;
				$accept_request->save();	
				$request_detail->save();				
			return Response::json(array('error' => false, 'message' => 'Work Completed', 'request' => $request_detail), 202);
		 }else{            
			return Response::json(array('error' => true, 'errors' => $errors_array), 200);
		}			
	}
	public function allorders(Request $request){
			$errors_array = array();		
			$order_data = array();		
			if (!$request->has('userID') || $request->input('userID') == "")
				$errors_array['userID'] = 'UserID needs to have a value';
			if (count($errors_array) == 0){
				$serviceRequest = new service_request();
				$order_by_user_id = $serviceRequest::where('user_id', '=', $request->input('userID'))->get();
				$datacount = count($order_by_user_id);			
				if($datacount != 0){				
					return Response::json(array('error' => false, 'message' => 'Order listed', 'request' => $order_by_user_id), 202);
				}else{
					return Response::json(array('error' => true, 'message' => 'Data not Found!'), 202);
				}			
			}else{
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
	}
}
