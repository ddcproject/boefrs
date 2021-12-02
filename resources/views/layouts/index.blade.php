<!DOCTYPE html>
<html dir="ltr" lang="th">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Flu Right Size">
	<meta name="author" content="Talek team">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/images/favicon.png') }}">
	<title>Flu Right Site</title>
	@yield('custom-style')
	@include('layouts.main-style')
	@include('layouts.main-top-script')
	@yield('top-script')
	@yield('internal-style')
	@yield('meta-token')
</head>
<body>
	<div class="preloader">
		<div class="lds-ripple">
			<div class="lds-pos"></div>
			<div class="lds-pos"></div>
		</div>
	</div>
	<div id="main-wrapper" class="mini-sidebar">
		@include('layouts.top-sidebar')
		@include('layouts.left-sidebar')
		<div class="page-wrapper">
			@yield('contents')
			@include('layouts.footer')
		</div><!-- page-wrapper -->
	</div><!-- main-wrapper -->
	@include('layouts.main-script')
	@yield('bottom-script')
	@stack('custom-script')
</body>
</html>
