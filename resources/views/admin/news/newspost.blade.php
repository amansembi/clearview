@extends('layouts/adminlayout')
@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Request detail</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
						<li class="breadcrumb-item">News</li>						
						<input type="hidden" class="open_sub" value="alluser" />
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>	
	<section class="content">
		<div class="row">
		<div class="col-md-12">
@if(Session::get('newsupdate'))			  
										<div class="alert alert-success col-sm-8">
											<button class="close" data-dismiss="alert">x</button>
											<strong>{{Session::get('newsupdate')}}		</strong>
										</div>
									@endif											
				<div class="card card-outline card-info">
							<div class="row">
							  <div class="col-sm-8"><div class="card-header">
							  <h3 class="card-title"> News </h3>  </div></div>
							 <!-- <div class="col-sm-5">
									<div class="alert alert-success ajax_msg" style="display:none;">
										<button class="close" data-dismiss="alert">x</button>
										<strong></strong>
									</div>
									
								</div>-->
								</div>								
								
								
							  <div class="card-body">
							   <form role="form" action="{{route('addnews')}}" enctype="multipart/form-data" method="post">
								@csrf           
								<div class="row">
									 <div class="col-md-8">
									 
									  <div class="form-group">										 
										<label for="posttitle">Title</label>
										<input type="text" class="form-control" id="newstitle" name="newstitle" placeholder="Enter News Title"/>
									  </div>	
									  
									  <div class="form-group">							
										<label for="awardyear">News Dscription</label>										
										<textarea name="news_dscription" class="form-control news_dscription" id="news_dscription" placeholder="Enter news description" rows="5" cols="30"> </textarea>
									  </div>
									  
									  <div class="form-group">
										  <div class="news-post-image">
											<img id="news_image"  class="news_image" src="{{asset('resources/img/dummy-avatar.png')}}" alt="your image" />
										  </div>
									  </div>
									  
									  <div class="form-group">
										<label>Featured Image </label>
										<div class="custom-file">
										  <input type="file" name="news_image"  class="custom-file-input" id="news_image" onchange="readURL(this);">
										  <label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									  </div>
									  
									 <div class="form-group">
										   <div class="row">
												<div class="col-md-4">	
													<div class="card-footer">
													  <button type="submit" class="btn btn-primary">Update</button>
													</div>
												</div>											
											</div>					
									 </div>
									
									 
									  </div>
									  
									   
									 
								
								</div>
						  </form>
						  
						<script>

						function readURL(input) {
							if (input.files && input.files[0]) {
								var reader = new FileReader();
								reader.onload = function (e) {
									$('#news_image')
										.attr('src', e.target.result)
										.width(200)
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