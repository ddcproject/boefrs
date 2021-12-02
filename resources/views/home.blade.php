@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Dashboard</div>
				<div class="card-body">
					@php
						$user = Auth::user();
					@endphp
					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif
					@if (Auth::user()->hasRole('Admin'))
						You are logged in Admin {{ auth()->user()->name }} !!
					@else
						{{ __('You are guest') }}
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
