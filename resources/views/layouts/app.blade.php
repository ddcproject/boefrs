<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="flu right size">
	<meta name="author" content="talek team">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/images/small-moph-logo.png') }}">
	<title>Flu Rigit Site</title>
	<!-- Custom CSS -->
	@yield('custom-style')
	<style>
		.topbar, #navbarSupportedContent {
			background-color:#343a40 !important;
		}
		#navbarSupportedContent a {
			color: white;
		}
	</style>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="main-wrapper">
	<div class="preloader">
		<div class="lds-ripple">
			<div class="lds-pos"></div>
			<div class="lds-pos"></div>
		</div>
	</div>
	<header class="topbar">
		<nav class="navbar top-navbar navbar-expand-md">
			<div class="navbar-header">
				<!-- This is for the sidebar toggle which is visible on mobile only -->
				<a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="ti-menu ti-close"></i>
				</a>
				<a class="navbar-brand" href="{{ route('init') }}">
					<b class="logo-icon p-l-10">
						<img src="{{ URL::asset('assets/images/small-moph-logo.png') }}" alt="BOE" class="light-logo">
					</b>
					<span class="logo-text" style="display:block;font-size:1.175em;color:white;">Flu Right Site</span>
				</a>
				<!-- Toggle which is visible on mobile only -->
				<a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="ti-more"></i>
				</a>
			</div>
			<!-- End Logo -->
			<div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
				<!-- toggle and nav items -->
				<ul class="navbar-nav float-left mr-auto">
					<li class="nav-item d-none d-md-block">
						<a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar">
							<i class="mdi mdi-menu font-24" style="display:none;"></i>
						</a>
					</li>
				</ul>
				<!-- Right side toggle and nav items -->
				<ul class="navbar-nav float-right">
					<li class="nav-item"><a href="{{ route('register') }}" class="nav-link"><i class="fas fa-user-plus m-r-10"></i>{{ __('Register') }}</a></li>
					<li class="nav-item"><a href="{{ route('login') }}" class="nav-link"><i class="fas fa-lock m-r-10"></i>{{ __('Login') }}</a></li>
				</ul>
			</div>
		</nav>
	</header>
		@yield('content')
	</div>
	<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
	<!-- Bootstrap tether Core JavaScript -->
	<script src="{{ URL::asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
	<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script>
		$('[data-toggle="tooltip"]').tooltip();
		$(".preloader").fadeOut();
		// ==============================================================
		// Login and Recover Password
		// ==============================================================
		$('#to-recover').on("click", function() {
			$("#loginform").slideUp();
			$("#recoverform").fadeIn();
		});
		$('#to-login').click(function(){
			$("#recoverform").hide();
			$("#loginform").fadeIn();
		});
	</script>
	@yield('bottom-script')
</body>
</html>
