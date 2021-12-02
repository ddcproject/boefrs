@extends('layouts.index')
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Users</span></h4>
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
					<div class="my-4">
						<a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
					</div>
					@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
					@endif
					<table class="table table-bordered">
						<tr>
							<th>No</th>
							<th>Name</th>
							<th>Email</th>
							<th>Roles</th>
							<th width="280px">Action</th>
						</tr>
						@foreach ($data as $key => $user)
							<tr>
								<td>{{ ++$i }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>
									@if(!empty($user->getRoleNames()))
										@foreach($user->getRoleNames() as $v)
											<label class="badge badge-success">{{ $v }}</label>
										@endforeach
									@endif
								</td>
								<td>
									<a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
									<a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
									{!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
									{!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
									{!! Form::close() !!}
								</td>
							</tr>
						@endforeach
					</table>
					{!! $data->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
