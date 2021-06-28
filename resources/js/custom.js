 $(document).ready(function(){
	$(".resetpassword .reset .passOne").keyup(function(){
		var pass = $(this).val();
		var n = pass.length;
		var matches = pass.match(/\d+/g);
		//alert(matches);
		if( n < 8){
			$('.new_password').removeClass('text-danger');
			$('.new_password').addClass('text-danger');
			$('.new_password').text('Password should be of minimum 8 characters');
			//$('.pass_submit').hide();
			$('#pass_submit').css('pointer-events', 'none');
		}else if(matches == null){
			$('.new_password').removeClass('text-danger');
			$('.new_password').addClass('text-danger');
			$('.new_password').text('Password must have atleast one digit.');
			//$('.pass_submit').hide();
			$('#pass_submit').css('pointer-events', 'none');
		}else{
			$('.new_password').removeClass('text-danger');			
			$('.new_password').text('');			
		}			
	});	 
	$(".resetpassword .reset .confirm_pass").keyup(function(){		
		var newpass = $('.resetpassword .reset .passOne').val();
		var confirm_pass = $(this).val();
		if (newpass != confirm_pass) {  
			$('.confirm_password').addClass('text-danger');		
			$('.confirm_password').text('Passwords do not match.');   
			//$('.pass_submit').hide();
			$('#pass_submit').css('pointer-events', 'none');
        }else{
			$('.confirm_password').removeClass('text-danger');		
			$('.confirm_password').text('');       
			//$('.pass_submit').show();
			$('#pass_submit').css('pointer-events', 'auto');
		}			
	});

			// function initMap() {
			  //The location of Uluru
			  // var uluru = {lat: -30.7333, lng: 76.7794};
			  //The map, centered at Uluru
			  // var map = new google.maps.Map(
				  // document.getElementById('map'), {zoom: 4, center: uluru});
			 // The marker, positioned at Uluru
			  // var marker = new google.maps.Marker({position: uluru, map: map});
			// }
	
 });
