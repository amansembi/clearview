@extends('layouts.app')
@section('content')
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
			
        </style>
		<div class="main-content">
			<div class="success_message">
				@if(Session::get('techchangepass'))			 
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">x</button>
					<strong>{{Session::get('techchangepass')}}		</strong>
					</div>
				@endif  
				@if(Session::get('confirmtechnician'))			 
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">x</button>
					<strong>{{Session::get('confirmtechnician')}}		</strong>
					</div>
				@endif  
				@if(Session::get('changepass'))			  
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">x</button>
					<strong>{{Session::get('changepass')}}		</strong>
					</div>
				@endif
				@if(Session::get('confirmuser'))			  
					<div class="alert alert-success">
					<button class="close" data-dismiss="alert">x</button>
					<strong>{{Session::get('confirmuser')}}		</strong>
					</div>
				@endif
			</div>
			<div class="flex-center position-ref full-height">
				<div class="content welcome-page">
					<div class="title m-b-md">
						ClearView             
					</div>		
				</div>
			</div>
		</div>
    @endsection
