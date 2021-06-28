@extends('layouts/adminlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit User</h1>
		  
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/users')}}">All User</a></li>
            <li class="breadcrumb-item active">Edit User</li>
			<input type="hidden" class="open_sub" value="edituser" />
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  
		  <section class="content">
			<div class="row">
			  <div class="col-md-12">
		
						  <div class="card card-outline card-info">
							<div class="row">
							  <div class="col-sm-7"><div class="card-header">
							  <h3 class="card-title"> Edit user data </h3>  </div></div>
							  <div class="col-sm-5">
									<div class="alert alert-success ajax_msg" style="display:none;">
										<button class="close" data-dismiss="alert">x</button>
										<strong></strong>
									</div>
									@if(Session::get('updateuser'))			  
										<div class="alert alert-success">
											<button class="close" data-dismiss="alert">x</button>
											<strong>{{Session::get('updateuser')}}		</strong>
										</div>
										@endif
									
								</div>
								</div>								
								@php  $role = json_decode($userrole,true) @endphp
								
							  <div class="card-body">
							   <form role="form" action="{{route('updateuser')}}" enctype="multipart/form-data" method="post">
								@csrf           
								<div class="row">
									 <div class="col-md-8">
									  <div class="form-group">
										 <input type="hidden" class="form-control" id="userid" value="{{$edituser->id}}" name="userid">
										<label for="posttitle">Name</label>
										<input type="text" class="form-control" id="name" value="{{$edituser->name}}" name="name" placeholder="Enter Name">
									  </div>	
									  <div class="form-group">							
										<label for="awardyear">Last Name</label>
										<input type="text" class="form-control" id="equipmentId" value="{{$edituser->equipmentId}}" name="equipmentId" placeholder="Enter Equipment Id">
									  </div>	
									  <div class="form-group">							
										<label for="awardyear">Email</label>
										<input type="text" class="form-control" id="email" value="{{$edituser->email}}" name="email" placeholder="Enter Email">
									  </div>	
									  <div class="form-group">							
										<label for="awardyear">User Role</label>	
										<select class="custom-select" name="userRole">
											<option>--Select user role--</option>
											@php $count = 1 @endphp										
											@foreach($role as $key => $node)
											@if($edituser->role == $key)
											  <option selected="selected" value="{{$key}}">{{$node}}</option>
												@else														
												<option value="{{$key}}">{{$node}}</option>
												@endif
											  @php $count = $count+1 @endphp
											@endforeach
										</select>											  
									  </div>				  									
									  </div>
									  <div class="col-md-4">
									   <div class="form-group">
									   <div class="row">
												<div class="col-md-4">	
													<div class="card-footer">
													  <button type="submit" class="btn btn-primary">Update</button>
													</div>
												</div>
												<!--<div class="col-md-8">
													<div class="card-footer">
														<a href="{{URL::to('/fullPost', [$edituser->id, $edituser->slug])}}">Preview</a>
													</div>
												</div>-->
											</div>					
									  </div>
									  <div class="form-group">
										  <div class="post-image">
											<img id="editimage" name="edituserimage" class="editimage" src="{{(url($edituser->image))}}" alt="your image" />
										  </div>
									  </div>
									  <div class="form-group">
										<label>Featured Image </label>
										<div class="custom-file">
										  <input type="file" name="edituserimage"  class="custom-file-input" id="customFile" onchange="readURL(this);">
										  <label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									  </div>
								</div>
								</div>
						  </form>
						  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  <div class="modal-dialog" role="document">
								<div class="modal-content">
								  <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Delete category</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								  </div>
								  <form action="" method="post">
								  <div class="modal-body"><strong style="color:red;">Warning!</strong>
									Either assign a different category to videos else
										videos will also be deleted after deleting the particular category
									<input type="hidden" class="form-control deletecatid" id="deletecatid" name="deletecatid" />
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" name="deletesubmit" class="btn btn-primary">Delete</button>
								  </div>
								  </form>
								</div>
							  </div>
							</div>
						<script>

						function readURL(input) {
							if (input.files && input.files[0]) {
								var reader = new FileReader();
								reader.onload = function (e) {
									$('#editimage')
										.attr('src', e.target.result)
										.width(150)
										.height(200);
								};
								reader.readAsDataURL(input.files[0]);
							}
						}
						</script>    
					  </div>
					</div>
			  </div>
			  </div>
		  </section>
		</div>
@endsection