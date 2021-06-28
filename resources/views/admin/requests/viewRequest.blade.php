@extends('layouts/adminlayout')
@section('content')
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
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
						<li class="breadcrumb-item"><a href="{{url('/admin/requests')}}">All Request</a></li>
						<li class="breadcrumb-item active">Request Detail</li>
						<input type="hidden" class="open_sub" value="alluser" />
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
							  <h3 class="card-title"> Request view </h3>  </div></div>
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
								@php  $status = json_decode($reqstatus, true) @endphp
								@php  $reqtype = json_decode($reqtype, true) @endphp
								
							  <div class="card-body">
							   <form role="form" action="" enctype="multipart/form-data" method="post">
								@csrf           
								<div class="row">
									 <div class="col-md-8">
									 
									  <div class="form-group">
										 <input type="hidden" class="form-control" id="userid" value="{{$reqData->id}}" name="userid">
										<label for="posttitle">Name</label>
										<input type="text" class="form-control" id="name" value="{{$reqData->contact_name}}" name="name" disabled>
									  </div>	
									  
									  <div class="form-group">							
										<label for="awardyear">Phone Number</label>
										<input type="text" class="form-control" id="phone_number" value="{{$reqData->phone_number}}" name="phone_number" disabled>
									  </div>
									  
									  <div class="form-group">							
										<label for="awardyear">Equipment ID</label>
										<input type="text" class="form-control" id="equipmentId" value="{{$reqData->equipment_id}}" name="equipmentId" disabled>
									  </div>	
									  
									  <div class="form-group">							
										<label for="awardyear">Email</label>
										<input type="text" class="form-control" id="email" value="{{$reqData->email}}" name="email" placeholder="Enter Email" disabled>
									  </div>	
									  
									  <div class="form-group">							
										<label for="awardyear">Service type</label>
										@foreach($reqtype as $key => $reqty)
										@if($reqData->service_type == $key)
										<input type="text" class="form-control" id="service_type" value="{{$reqty}}" name="service_type" disabled>
										@endif
										@endforeach
									  </div>	
									  
									  <div class="form-group">							
										<label for="status">Status</label>																			
											@foreach($status as $key => $node)
											@if($reqData->status == $key)										
										  <input type="text" class="form-control" id="status" value="{{$node}}" name="status" disabled>													
											@endif											 
											@endforeach																				  
									  </div>
									<div class="form-group">							
										<label for="status">Map</label>									  
										<div id="map" style="width: 100%; height: 300px;"></div>									  
									  
									  </div>
									 
									  </div>
									  <div id="map_canvas" style="width: 100%; height: 300px;"></div>
									  <!--<div class="col-md-4">
									   <div class="form-group">
									   <div class="row">
											<div class="col-md-4">	
												<div class="card-footer">
												  <button type="submit" class="btn btn-primary">Update</button>
												</div>
											</div>											
										</div>					
									 </div>
									 
								</div>-->
								</div>
						  </form>
						  @php
								$lat = array('73.0785729','73.0785729','73.0785729');
								$long = array('73.0785729', '73.0715769', '73.0785729');
						  @endphp
						  @php $lat = json_encode($lat) @endphp
						  @php $long = json_encode($long) @endphp
						  <span class="latitude">{{$lat}}</span><span class="longitude">{{$long}}</span>
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
											
						function initMap() {						
						  var uluru = {
							  lat: 30.7407,
							  lng: 76.6753
							};						
						  var map = new google.maps.Map(
							  document.getElementById('map'), {zoom: 14, center: uluru});						
						  var marker = new google.maps.Marker({position: uluru, map: map});
						}
						
						

        var lat_array = $('.latitude').text();
        var long_array = $('.longitude').text();
		

        function init() {
            var mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(40.6700, -73.9400),
                styles: []
            };
            var mapElement = document.getElementById('map_canvas');
            var map = new google.maps.Map(mapElement, mapOptions);

            // assuming that the length of lat_array and long_array are the same
            for(var i = 0; i < lat_array.length; i++){
				alert(long_array[i]);
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(parseFloat(lat_array[i]), parseFloat(long_array[i])),
                    map: map,
                    title: 'Snazzy!'
                });
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
