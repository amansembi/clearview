<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\service_request;

class AdminController extends Controller
{
	public function _construct(){
		$this->middleware('auth');
	}
    public function index(){
		$user = new User;
		$alluser = $user::all();
		$allusercount = array('allusercount' => count($alluser));
		
		return view('admin.admin')->with($allusercount);	
		//return count($alluser);
	} 
	public function allUsers()
   {
	   $user = new User;
	   $alluser = $user::all();
	   $userrole = array("1" => "Administrator",	"2" => "Subscriber", "3" => "Editor", "4" => "Author");
	   $alluser = array('alluser' => $alluser, 'userrole' => json_encode($userrole, true));
	   return view('admin/user/users')->with($alluser);
	  // return  $alluser;
   } 
 public function editusers($id)
   {	   
		$edituser = User::find($id);
		$userrole = array("1" => "Administrator",	"2" => "Subscriber", "3" => "Editor", "4" => "Author");
		$edituser = array('edituser' => $edituser, 'userrole' => json_encode($userrole, true));
		return view('admin/user/edituser')->with($edituser);
	   //return  $id;
   }  
    public function updateuser(Request $request)
   {	   
		$userid =  $request->userid;
		$edituser = User::find($userid);
		$edituser->name = $request->name;
		$edituser->equipmentId = $request->equipmentId;
		$edituser->email = $request->email;
		$edituser->role = $request->userRole;
		
		if ($request->hasfile('edituserimage')) {			
			$image = $request->file('edituserimage');
			$name = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = public_path('/images');
			$image->move($destinationPath, $name);
			$imageurl = '/public/images/'.$name;
			$edituser->image = $imageurl;		   
		}
		$edituser->save();
		$request->session()->flash('updateuser','Update data successfully');			
    	return redirect('/admin/editusers/'.$userid.'');
		// return  $imageurl;
   }
    public function deleteuser(Request $request){
	   $deleteuserid = $request->deleteuserid;
	   $edituser = User::find($deleteuserid);
	   if($edituser->role != '1')
		{
		   $edituser->delete();
		   $request->session()->flash('deleteuser','Deleted successfully');			
		}
	   else
		{
		   $request->session()->flash('deleteuser','You can not delete user as Administrator');
		}
    	return redirect('/admin/users');
	}
	
	public function requests()
	{
	   $service_request = new service_request;
	   $service_request = $service_request::all();
		$reqstatus = array("1" => "Pending", "2" => "Open",	"3" => "Completed");
		$data = array('service_request' => $service_request, 'reqstatus' => json_encode($reqstatus));
	   return view('admin/requests/request')->with($data);
	}
   
	public function viewRequest($id)
	{
	   $reqData = service_request::find($id);
	   $reqstatus = array("1" => "Pending", "2" => "Open",	"3" => "Completed");
	   $reqtype = array("machenical_problem" => "Machenical Problem", "toner_order" => "Toner Order",	"enter_a_meter" => "Enter Meter");
	   $data = array('reqData' => $reqData, 'reqstatus' => json_encode($reqstatus), 'reqtype' => json_encode($reqtype));
	   return view('admin/requests/viewRequest')->with($data);
	}
}
