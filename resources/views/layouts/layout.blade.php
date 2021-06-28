<?php
$site_url = 'http://'.$_SERVER['SERVER_NAME'].'/mortgages';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Clearview</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
	<!-- <link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" /> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{asset('resources/css/semantic-ui.css')}}">
	<link rel="stylesheet" href="{{asset('resources/css/bootstrap.min.css')}}">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
	<link rel="stylesheet" href="{{asset('resources/css/main.css')}}">
	<!--<link rel="stylesheet" href="{{asset('resources/css/style.css')}}">-->
	<script src="{{asset('resources/js/jquery.min.js')}}"></script>	
	<script src="{{asset('resources/js/app.js')}}"></script>
	<script src="{{asset('resources/js/popper.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
</head>
<body>
	<div class="wrapper">
		@section('header')
		@show
		<div class="content">
			@section('content')			
			@show
		</div>		
		@section('footer')		
		<section class="copyright-bx text-center bg-light-black">
			<div class="container">
				<div class="copyright">© 2019 Clearview. All Rights Reserved by Digimantra Labs</div>
			</div><!-- container -->
		</section><!-- footer-top -->
		@show
	</div><!-- wrapper -->
	<script src="{{asset('resources/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('resources/js/custom.js')}}"></script>
</body>
</html>