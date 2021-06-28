@extends('layouts.app')
@section('content')
<div class="resetpassword">
	<div class="container">
	<div class="row"><h3 class="card-title"> Change Password  </h3>  </div>
		<div class="row">	
								@if(Session::get('changepass'))			  
										<div class="alert alert-success">
											<button class="close" data-dismiss="alert">x</button>
											<strong>{{Session::get('changepass')}}		</strong>
										</div>
									@endif			
			<div class="col-md-12 reset">		
				<form method="post" action="{{route('changepassword')}}" enctype="multipart/form-data" role="form">
					@csrf			
						<label>Email</label>
					<div class="form-group email"> 
						<input type="hidden" value="{{$email}}" name="email" class="form-control"  placeholder="Current Password"> 
						<span class="email form-control">{{$email}}</span>
					</div> 
					   <label>New Password</label>
					<div class="form-group pass_show"> 
						<input type="password" name="new_password" id="passOne" class="form-control passOne" placeholder="New Password">
							<span class="new_password"></span>
					</div> 
					   <label>Confirm Password</label>
					<div class="form-group pass_show"> 
						<input type="password" name="confirm_password" id="passTwo" class="form-control confirm_pass" placeholder="Confirm Password"> 
						<span class="confirm_password"></span>
					</div> 
					<div class="form-group pass_submit" > 
						<input type="submit" value="Reset password" id="pass_submit"> 
					</div> 
				</form>
			</div>  
		</div>
		
	</div>
</div>
<style>
.pass_submit input{
	 display: block;
    width: 190px;
    height: 44px;
    background: #4E9CAF;
    text-align: center;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    margin: 10px 0px;
    text-decoration: none;
    padding: 0px 0px;
}
.pass_submit input:hover{
	    background: #1c4e5a;
}
</style>
@endsection