@extends('layouts/adminlayout')
@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>All Request</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
						<li class="breadcrumb-item active">All request</li>
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
							 <thead> <tr> <th>S. No.</th> <th>Name</th> <th>Equipment Id</th> <th>Service ID</th> <th>Status</th><th></th> </tr> </thead>
							 <tbody>
								@php  $reqs = json_decode($reqstatus, true) @endphp
								@php  $count = 1 @endphp
								@foreach($service_request as $request)									 
							  <tr class="usertable">
								<td>{{$count}}</td>							
								<td><input type="hidden" value="{{$request->contact_name}}" class="username">{{$request->contact_name}}</td>
								<td> {{$request->equipment_id}}</td>
								<td> {{$request->service_code}}</td>		
						

						@foreach($reqs as $key=>$req)	@if($request->status == $key) 								
								<td class="role" @if($key == 1) style="color:orange;" @endif @if($key == 2) style="color:red;" @endif @if($key == 3) style="color:green;" @endif> <input type="hidden" class="userrole" value="{{$req}}" />{{$req}}  </td>	@endif 								
								@endforeach
								
								
								<td><input type="hidden" value="{{$request->id}}" name="userid" class="userid"><a href="{{url('admin/request/'. $request->id)}}">View</a>|<a href="{{url('admin/AssignToTechnician/'. $request->id)}}" class="userdelete">Assign to Technician</a></td>								
								  </tr>								  
								  @php  $count = $count+1 @endphp
							  @endforeach
							</tbody>
							<tfoot> <tr> <th>S. No.</th> <th>Name</th><th>Equipment Id</th> <th>Service ID</th> <th>Status</th><th></th> </tr> </tfoot>
						</table>
				  </div>
						
				</div>
					 
					 
						<script> 
						// $('.userdata tr.usertable .userdelete').click(function(){
							 // $('.userdata .usertable').removeClass("active");
							// $(this).parent().parent().addClass("active");
							// var editid = $('.usertable.active .userid').val();
							// var username = $('.usertable.active .username').val();
							// var role = $('.usertable.active .userrole').val();
							// $('.deleteuserid').val(editid);
							// $('.modal_role').text(role);							
							// $('.username').text(username);							
						 // });
						 </script>
				</div>
			</div>
	</section>
</div>
@endsection