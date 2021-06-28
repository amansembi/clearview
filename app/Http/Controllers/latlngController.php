<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Hash;
use App\userlatlng;
use App\technicianlatlng;

class latlngController extends Controller
{
    public function userlatlngs(Request $request){
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('user_id') || $request->input('user_id') == "")
				$errors_array['user_id'] = 'User ID needs to have a value';

			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			
			if (count($errors_array) == 0){				
				$user_id = $request->input('user_id');				
				$location = userlatlng::where('user_id', '=', $request->input('user_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$userlocation = userlatlng::find($latlogid);					
					$userlocation->latitude = $request->input('latitude');
					$userlocation->longitude = $request->input('longitude');
					$userlocation->save();
					return Response::json(array('error' => false, 'message'=>'User Latitude and Longitude updated successfully', 'user' => $userlocation), 202);
				}else{
					$locations = new userlatlng();
					$locations->user_id = $request->input('user_id');
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'User Latitude and Longitude Added successfully', 'user' => $locations), 202);
				}				
			 }else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}		
		
	}
	public function technicianlatlng(Request $request)
		{		
				$errors_array = array();        
				$resetpass = array();        
			if (!$request->has('technician_id') || $request->input('technician_id') == "")
				$errors_array['technician_id'] = 'Technician ID needs to have a value';

			if (!$request->has('latitude') || $request->input('latitude') == "")
				$errors_array['latitude'] = 'Latitude needs to have a value';	
			
			if (!$request->has('longitude') || $request->input('longitude') == "")
				$errors_array['longitude'] = 'Longitude needs to have a value';	
			
				if (count($errors_array) == 0){								
				$location = technicianlatlng::where('technician_id', '=', $request->input('technician_id'))->first();
				if($location != null){
					$latlogid = $location['id'];
					$techlocation = technicianlatlng::find($latlogid);					
					$techlocation->latitude = $request->input('latitude');
					$techlocation->longitude = $request->input('longitude');
					$techlocation->save();
					return Response::json(array('error' => false, 'message'=>'Technician Latitude and Longitude updated successfully', 'technicianLocation' => $techlocation), 202);
				}else{
					$locations = new technicianlatlng();
					$locations->technician_id = $request->input('technician_id');
					$locations->latitude = $request->input('latitude');
					$locations->longitude = $request->input('longitude');
					$locations->save();
					return Response::json(array('error' => false, 'message'=>'Technician Latitude and Longitude Added successfully', 'technicianLocation' => $locations), 202);
				}				
			 }else{            
				return Response::json(array('error' => true, 'errors' => $errors_array), 200);
			}
		}
}
