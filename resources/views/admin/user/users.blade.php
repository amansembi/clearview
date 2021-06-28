@extends('layouts/adminlayout')
@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>All Users</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
						<li class="breadcrumb-item active">All User</li>
						<input type="hidden" class="open_sub" value="alluser" />
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	
	<section class="content">
		<div class="row">
		<div class="col-md-12">
		@if(Session::get('deleteuser'))			  
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert">x</button>
									<strong>{{Session::get('deleteuser')}}		</strong>
								</div>
								@endif
					 <div class="card">
					  <div class="card-body">
						<table id="example1" class="table table-bordered table-striped userdata">
							 <thead> <tr> <th>S. No.</th> <th>Name</th> <th>Image</th> <th>Equipment Id</th> <th>Email</th> <th>User Role</th> </tr> </thead>
							 <tbody>
								@php  $roles = json_decode($userrole) @endphp
								@foreach($alluser as $user)									 
							  <tr class="usertable">
								<td><input type="hidden" value="{{$user->id}}" name="userid" class="userid"><a href="{{url('admin/editusers/'. $user->id)}}">Edit</a>|<a href="javascript:void(0);" class="userdelete" data-toggle="modal" data-target="#deleteModal">Delete</a></td>
								<td><input type="hidden" value="{{$user->name}} {{$user->lname}}" class="username">{{$user->name}}</td>
								<td>@if($user->image) <div class="profile-image-curcle"><img src="{{url($user->image)}}" /></div> @endif </td>										
								<td> {{$user->equipmentId}}</td>
								<td> {{$user->email}}</td>										
								<td class="role"> @foreach($roles as $key=>$role)	@if($user->role == $key) <input type="hidden" class="userrole" value="{{$role}}">{{$role}} @endif @endforeach </td>										
								  </tr>
							  @endforeach
							</tbody>
							<tfoot> <tr> <th>S. No.</th> <th>Name</th> <th>Image</th> <th>Equipment Id</th> <th>Email</th> <th>User Role</th> </tr> </tfoot>
						</table>
				  </div>
						<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  <div class="modal-dialog" role="document">
								<div class="modal-content">
									  <div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Delete User name as <span class="username" style="text-transform: capitalize;"></span></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										  <span aria-hidden="true">&times;</span>
										</button>
									  </div>
									  <form action="{{route('deleteuser')}}" method="post">
									  @csrf
									  <div class="modal-body"><strong style="color:#dc3545;">Do you want to delete user role as <span class="modal_role"></span> !</strong>										
										<input type="hidden" class="form-control deleteuserid" id="deleteuserid" name="deleteuserid" value="amrinder"/>
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Delete</button>
									  </div>
									  </form>
								</div>
							  </div>
						</div>
				</div>
						<script> 
						$('.userdata tr.usertable .userdelete').click(function(){
							 $('.userdata .usertable').removeClass("active");
							$(this).parent().parent().addClass("active");
							var editid = $('.usertable.active .userid').val();
							var username = $('.usertable.active .username').val();
							var role = $('.usertable.active .userrole').val();
							$('.deleteuserid').val(editid);
							$('.modal_role').text(role);							
							$('.username').text(username);							
						 });
						 </script>
				</div>
			</div>
	</section>
</div>
@endsection