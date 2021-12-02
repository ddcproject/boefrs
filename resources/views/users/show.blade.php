@extends('layouts.index')
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">User show</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
						<li class="breadcrumb-item active" aria-current="page">Manage</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="d-md-flex align-items-center" style="border-bottom:1px solid #EAEAEA">
						<div>
							<h4 class="card-title">บริหารจัดการผู้ใช้งานระบบ</h4>
							<h5 class="card-subtitle">ID Flu-BOE</h5>
						</div>
					</div>
					<div class="row my-4">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group"><strong>Name:</strong> {{ $user->name }}</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group"><strong>Email:</strong> {{ $user->email }}</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group"><strong>Roles:</strong>
								@if(!empty($user->getRoleNames()))
									@foreach($user->getRoleNames() as $v) 
										<span class="badge badge-success">{{ $v }}</span>
									@endforeach
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
