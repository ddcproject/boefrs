@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Create user</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
						<li class="breadcrumb-item active" aria-current="page">Create</li>
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
							<h5 class="card-subtitle">เพิ่มผู้ใช้ใหม่</h5>
						</div>
					</div>
					<div class="my-4">
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<strong>Whoops!</strong> There were some problems with your input.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						{!! Form::open(array('route'=>'users.store', 'method'=>'POST', 'class'=>'mt-4 mb-3')) !!}
						<!-- form method="POST" action="{ route('users.store') }" class="mt-4 mb-3"> -->
							<div class="row">
								<div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="province">จังหวัด</label>
										<select name="province" class="form-control select-province" style="width:100%">
											<option value="0">-- เลือกจังหวัด --</option>
											@php
												$provinces = Session::get('provinces');
												$provinces->each(function ($item, $key) {
													echo "<option value=\"".$item->province_id."\">".$item->province_name."</option>";
												});
											@endphp
										</select>
									</div>
								</div>
								<div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="hospital">โรงพยาบาล</label>
										<select name="hospcode" class="form-control select-hospital" disabled style="width:100%">
											<option value="0">-- เลือกโรงพยาบาล --</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="titleName">คำนำหน้าชื่อ</label>
										<select name="title_name" class="form-control select-title-name" style="width:100%">
											<option value="0">-- โปรดเลือก --</option>
											@php
												$titleName->each(function ($item, $key) {
													echo "<option value=\"".$item->id."\">".$item->title_name."</option>";
												});
											@endphp
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="otherTitleNameInput">อื่นๆ ระบุ</label>
										<input type="text" name="title_name_other" class="form-control other-title-name" placeholder="คำนำหน้าชื่ออื่นๆ" disabled>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="fname">ชื่อจริง:</label>
										{!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label for="fname">นามสกุล:</label>
										<input type="text" name="lastname" placeholder="Lastname" class="form-control">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label>อีเมล์:</label>
										{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label>รหัสผ่าน:</label>
										{!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<label>ยืนยันรหัสผ่าน:</label>
										{!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
									<div class="form-group">
										<label>Role:</label>
										{!! Form::select('roles[]', $roles, [], array('class' => 'form-control role', 'multiple')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Create</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
	$('.select-province,.select-hospital,.select-title-name,.role').select2();
	$('.select-province').change(function() {
		var prov_id = $('.select-province').val();
		if (prov_id > 0) {
			$('.select-hospital').prop('disabled', false);
			$.ajax({
				type: "GET",
				url: "{{ route('ajaxGetHospByProv') }}",
				dataType: 'html',
				data: {prov_id: prov_id},
				success: function(data) {
					$('.select-hospital').html(data);
				},
				error: function(data) {
					alert(data.status);
				}
			});
		} else {
			$('.select-hospital').val('');
			$('.select-hospital').prop('disabled', true);
		}
	});
});
</script>
<script>
$('.select-title-name').change(function() {
	if ($('.select-title-name').val() === '6') {
		$('.other-title-name').prop('disabled', false);
	} else {
		$('.other-title-name').val('');
		$('.other-title-name').prop('disabled', true);
	}
});
</script>
@endsection
