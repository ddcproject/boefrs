@extends('layouts.app')
@section('custom-style')
<link rel="stylesheet" href="{{ URL::asset('dist/css/style.min.css') }}">
@endsection
@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
	<div class="auth-box bg-dark border-top border-secondary">
		<div id="loginform">
			<div class="text-center p-t-20 p-b-20">
				<span class="db" style="display:block;font-size:1.675em;color:white;">Flu Right Site Project</span>
				<span class="db" style="font-size:1.375em;color:white;">DDC MOPH</span>
			</div>
			<!-- Form -->
			<form method="POST" action="{{ route('login') }}" class="form-horizontal m-t-20" id="loginform">
				@csrf
				<div class="row p-b-30">
					<div class="col-12">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
							</div>
							<input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="E-mail" aria-label="E-mail" aria-describedby="basic-addon1" required autocomplete="email" autofocus>
							@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-info text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
							</div>
							<input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"  placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required autocomplete="current-password">
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
					</div>
				</div>
				<div class="row border-top border-secondary">
					<div class="col-12">
						<div class="form-group">
							<div class="p-t-20">
								<button  type="button" class="btn btn-primary" id="to-recover"><i class="fa fa-lock m-r-5"></i> Lost password?</button>
								<button type="submit" class="btn btn-success float-right" >{{ __('Login') }}</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="recoverform">
			<div class="text-center">
				<span class="text-white">Enter your e-mail address below and we will send you instructions how to recover a password.</span>
			</div>
			<div class="row m-t-20">
				<!-- Form -->
				<form class="col-12" action="index.html">
					<!-- email -->
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
						</div>
						<input type="text" class="form-control form-control-lg" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1">
					</div>
					<!-- pwd -->
					<div class="row m-t-20 p-t-20 border-top border-secondary">
						<div class="col-12">
							<a class="btn btn-success" href="#" id="to-login" name="action">Back To Login</a>
							<button class="btn btn-info float-right" type="button" name="action">Recover</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
