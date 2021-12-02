@extends('layouts.app')
@section('custom-style')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('dist/css/style.min.css') }}">
<style>
body * {
	font-family: 'Prompt';
}
input[type=text], input[type=password] {
	font-size: 1em;
}
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
	font-size: .90em;
}
::-moz-placeholder { /* Firefox 19+ */
	font-size: .90em;
}
:-ms-input-placeholder { /* IE 10+ */
	font-size: .90em;
}
:-moz-placeholder { /* Firefox 18- */
	font-size: .90em;
}
/* select 2 dropdown checkbox */
.select2-container--default .select2-selection--multiple:before {
    content: ' ';
    display: block;
    position: absolute;
    border-color: #888 transparent transparent transparent;
    border-style: solid;
    border-width: 5px 4px 0 4px;
    height: 0;
    right: 6px;
    margin-left: -4px;
    margin-top: -2px;top: 50%;
    width: 0;cursor: pointer
}

.select2-container--open .select2-selection--multiple:before {
    content: ' ';
    display: block;
    position: absolute;
    border-color: transparent transparent #888 transparent;
    border-width: 0 4px 5px 4px;
    height: 0;
    right: 6px;
    margin-left: -4px;
    margin-top: -2px;top: 50%;
    width: 0;cursor: pointer
}
/* end select2 icon */
</style>
@endsection
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="d-md-flex align-items-center" style="border-bottom:1px solid #EAEAEA">
						<div>
							<h4 class="card-title">ลงทะเบียนผู้ใช้งานระบบ</h4>
							<h5 class="card-subtitle">Flu Right Site</h5>
						</div>
					</div>
					<div class="my-4">
						@if ($message = Session::get('success'))
							<div class="alert alert-success">
								<p>{{ $message }}</p>
							</div>
						@endif
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<strong>Whoops!</strong> There were some problems with your input.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										@if ($error == 'validation.captcha')
											<li>The captcha validation was not successful.</li>
										@else
											<li>{{ $error }}</li>
										@endif
									@endforeach
								</ul>
							</div>
						@endif
						<form method="POST" action="#" class="mt-4 mb-3">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="province">จังหวัด</label>
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<select name="province" class="form-control form-control-lg select-province" id="select_province" style="width:100%">
											<option value="0">-- เลือกจังหวัด --</option>
											@php
												$provinces->keyBy('province_id');
												$provinces->each(function ($item, $key) {
													echo "<option value=\"".$item->province_id."\">".$item->province_name."</option>\n";
												});
											@endphp
										</select>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="hospital">โรงพยาบาล</label>
										<select name="hospcode" class="form-control form-control-lg select-hospital" id="select_hospital" disabled style="width:100%">
											<option value="0">-- เลือกโรงพยาบาล --</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="titleName">คำนำหน้าชื่อ</label>
										<select name="title_name" class="form-control form-control-lg select-title-name" style="width:100%">
											<option value="0">-- โปรดเลือก --</option>
											@php
												$titleName->each(function ($item, $key) {
													echo "<option value=\"".$item->id."\">".$item->title_name."</option>\n";
												});
											@endphp
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="otherTitleNameInput">อื่นๆ ระบุ</label>
										<input type="text" name="title_name_other" class="form-control form-control-lg other-title-name" placeholder="คำนำหน้าชื่ออื่นๆ" disabled>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="fname">ชื่อจริง:</label>
										<input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" placeholder="ชื่อ">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="fname">นามสกุล:</label>
										<input type="text" name="lastname" class="form-control form-control-lg" value="{{ old('lastname') }}" placeholder="นามสกุล">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="email">อีเมล์:</label>
										<input type="text" name="email" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="อีเมล์">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="phone">โทรศัพท์ :</label>
										<input type="text" name="phone" class="form-control form-control-lg" value="{{ old('phone') }}" placeholder="โทรศัพท์">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="fax">โทรสาร :</label>
										<input type="text" name="fax" class="form-control form-control-lg" value="{{ old('fax') }}" placeholder="โทรสาร">
									</div>
								</div>
							</div>
							<!--
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label for="role">บทบาท:</label>
										<select name="roles" class="form-control form-control-lg role">
											<option value="0">-- เลือกบทบาท --</option>
												<option value="guest">Guest</option>
										</select>
									</div>
								</div>
							</div>
							-->
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label>รหัสผ่าน:</label>
										<input type="password" name="password" class="form-control form-control-lg" placeholder="รหัสผ่าน">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<div class="form-group">
										<label>ยืนยันรหัสผ่าน:</label>
										<input type="password" name="confirm-password" class="form-control form-control-lg" placeholder="ยืนยันรหัสผ่าน">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<div class="captcha">
											<span>{!! captcha_img() !!}</span>
											<button type="button" class="btn btn-success"><i class="fas fa-sync-alt" id="refresh"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<input id="captcha" type="text" class="form-control" placeholder="ผลรวมของรหัส" name="captcha">
									</div>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">ลงทะเบียน</button>
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
<script src="{{ URL::asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
	$('.select-province,.select-hospital,.select-title-name,.role').select2();
	$('#select_province').change(function() {
		var prov_id = $('#select_province').val();
		if (prov_id > 0) {
			$('#select_hospital').prop('disabled', false);
			$.ajax({
				type: "GET",
				url: "{{ route('getHospByProv') }}",
				dataType: 'HTML',
				data: {prov_id: prov_id},
				success: function(data) {
					$('#select_hospital').html(data);
				},
				error: function(data) {
					alert(data.status);
				}
			});
		} else {
			$('#select_hospital').val('');
			$('#select_hospital').prop('disabled', true);
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
<script>
$('#refresh').click(function(){
	$.ajax({
		type:'GET',
		url:'refreshcaptcha',
		success:function(data){
			$(".captcha span").html(data.captcha);
		}
	});
});
</script>
@endsection
