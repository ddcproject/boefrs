@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-table/dist/bootstrap-table.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/toastr/build/toastr.min.css') }}">
<style>
input:-moz-read-only { /* For Firefox */
	background-color: #fafafa !important;
}
input:read-only {
	background-color: #fafafa !important;
}
.table-wrapper {
	width: 100%;
	margin: 10px auto 30px auto;
	background: #fff;
	box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {
	padding-bottom: 10px;
	margin: 0 0 10px;
}
.table-title h2 {
	margin: 6px 0 0;
	font-size: 1.875em;
	}
.table-title .add-new {
	float: right;
	height: 30px;
	font-weight: bold;
	font-size: 12px;
	text-shadow: none;
	min-width: 100px;
	border-radius: 50px;
	line-height: 13px;
}
.table-title .add-new i {
	margin-right: 4px;
}
/*table.table {
	table-layout: fixed;
} */
table.table tr th, table.table tr td {
	border-color: #e9e9e9;
}
table.table th i {
	font-size: 13px;
	margin: 0 5px;
	cursor: pointer;
}
table.table th:last-child {
	width: 110px;
}
table.table td a {
	cursor: pointer;
	display: inline-block;
	margin: 0 5px;
	min-width: 24px;
}
table.table td a.add {
	color: #27C46B;
}
table.table td a.edit {
	color: #FFC107;
}
table.table td a.delete {
	color: #E34724;
}
table.table td i {
	font-size: 19px;
}
table.table td a.add i {
	font-size: 24px;
	margin-right: -1px;
	position: relative;
	top: 3px;
}
table.table .form-control {
	height: 32px;
	line-height: 32px;
	box-shadow: none;
	border-radius: 2px;
}
table.table .form-control.error {
	border-color: #f50000;
}
table.table td .add {
	display: none;
}
</style>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Form</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Form</li>
						<li class="breadcrumb-item active" aria-current="page">Lab</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
			<div class="card">
				<div class="card-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
							</ul>
						</div>
					@endif
					<div class="d-md-flex align-items-center">
						<div>
							<h4 class="card-title">แบบเก็บข้อมูลโครงการเฝ้าระวังเชื้อไวรัสก่อโรคระบบทางเดินหายใจ</h4>
							<h5 class="card-subtitle">ID Flu-BOE</h5>
						</div>
					</div>

					<!--<form action="/lab" method="POST" class="needs-validation custom-form-legend" novalidate>
						{ csrf_field() }} -->
					<form id="lab_form" class="mt-4 mb-3">
						<div class="bd-callout" style="margin-top:0;position:relative">
							<div class="card">
								<div class="card-body">
									<div style="position:absolute; top:2px; right:2px;">
										<img src="{{ URL::asset('qrcode/qr'.$patient[0]->lab_code.'.png') }}" />
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
											<div class="input-group-append">
												<span class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="top" title="รหัสแบบฟอร์ม">{{ $patient[0]->lab_code }}</span>
											</div>
										</div>
									</div>
								</div>
							</div><!-- end card#1 -->
							<div class="card">
								<div class="card-body border-top">
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="analyze_id">หมายเลขวิเคราะห์</label>
											<input type="text" name="analyzeId" value="{{ $analyze_id }}" class="form-control" style="border:1px solid red;" placeholder="หมายเลขวิเคราะห์" readonly required autofocus>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="hospital">โรงพยาบาล</label>
											<input type="hidden" name="patientId" value="{{ $patient[0]->id }}">
											<input type="hidden" name="userIdInput" value="{{ auth()->user()->id }}">
											<input type="hidden" name="patientHospitalCode" value="{{ $user_hospital != null ? $user_hospital[0]->hospcode : null }}">
											<input type="text" name="patientHospitalName" value="{{ $user_hospital != null ? $user_hospital[0]->hosp_name : null }}" class="form-control" placeholder="Hospital" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="receive_date">วันที่รับตัวอย่าง</label>
												<div class="input-group date" data-provide="datepicke" id="receiveDateInput">
													<div class="input-group">
														<input type="text" name="receiveDate" class="form-control">
														<div class="input-group-append">
															<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="analyze_date">วันที่ทำการวิเคราะห์</label>
												<div class="input-group date input-daterange" data-provide="datepicke" id="analyzeDateInput">
													<div class="input-group">
														<input type="text" name="analyzeDateStart" class="form-control">
														<div class="input-group-append">
															<span class="input-group-text">to</span>
														</div>
														<input type="text" name="analyzeDateEnd" class="form-control">
														<div class="input-group-append">
															<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="resultDate">วันที่รายงานผล</label>
												<div class="input-group date" data-provide="datepicke" id="resultDateInput">
													<div class="input-group">
														<input type="text" name="resultDate" class="form-control">
															<div class="input-group-append">
															<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- end card#2 -->
								<div class="card">
									<div class="card-body border-top">
										<div class="form-row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<div class="table-wrapper">
													<div class="table-title">
														<div class="row">
															<!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><h4>เพิ่มข้อมูลผลตรวจ</h4></div>-->
															<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
																<button type="button" class="btn btn-success add-new"><i class="fas fa-plus"></i> เพิ่มข้อมูล</button>
															</div>
														</div>
													</div>
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>ชนิดตัวอย่าง</th>
																<th>ชื่อเชื้อ</th>
																<th>สายพันธุ์</th>
																<th>หมายเหตุ</th>
																<th>จัดการ</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div><!-- end card#3 -->
							<div class="card">
								<div class="card-body border-top">
									<!-- <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button> -->
									<button type="button" class="btn btn-primary" id="btn_submit">บันทึกข้อมูล</button>
								</div>
							</div><!-- end card#3 -->
						</div><!-- bd -collout -->
					</form>
				</div><!-- card-body -->
			</div><!-- card -->
		</div><!-- col -->
	</div><!-- row -->
</div><!-- container -->
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/datatables-1.10.18/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.18/Responsive-2.2.2/js/responsive.bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-validate-2.2.0/dist/bootstrap-validate.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
<script>
$(document).ready(function() {
	/* ajax request */
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	/* receiveDate date input */
	$('#receiveDateInput').datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		immediateUpdates: true,
		todayBtn: true,
		todayHighlight: true,
	}).datepicker("setDate", "0");

	/* Analysis date range */
	$('.input-daterange input').each(function() {
		$(this).datepicker({
			'clearDates': true,
			autoclose: true,
			format: 'dd/mm/yyyy',
			immediateUpdates: true,
			todayBtn: true,
			todayHighlight: true,
		}).datepicker("setDate", "0");
	});

	/* resultDate date input */
	$('#resultDateInput').datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		immediateUpdates: true,
		todayBtn: true,
		todayHighlight: true,
	}).datepicker("setDate", "0");

	/* submit ajax */
	$("#btn_submit").click(function(e) {
		//e.preventDefault();
		//var input = ConvertFormToJSON("#lab_form");
		var input = $("#lab_form").serialize();
		$.ajax({
			method: 'POST',
			url: "{{ route('lab-store') }}",
			data: input,
			success: function(data) {
				if (data.status == 204) {
					toastr.warning(data.msg, "Flu Right Size",
						{
							"closeButton": true,
							"positionClass": "toast-top-center",
							"progressBar": true,
							"showDuration": "500"
						}
					);
				} else if (data.status == 200) {
					toastr.success(data.msg, "Flu Right Size",
						{
							"closeButton": false,
							"positionClass": "toast-top-center",
							"progressBar": true,
							"showDuration": "500"
						}
					);
					window.setTimeout(function() {
						window.location.replace("{{ route('lab') }}");
					}, 5000);
				}
			},
			error: function(data, status, error) {
				toastr.error(data.status + " " + status + " " + error, " Flu Right Size",
					{
						"closeButton": true,
						"positionClass": "toast-top-center",
						"progressBar": true,
						"timeOut": 0,
						"extendedTimeOut": 0
					}
				);
			}
		});
	});
});
</script>
<script>
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();

	var actions = '<a class="add" title="Add" data-toggle="tooltip"><i class="mdi mdi-playlist-plus"></i></a>' +
	'<a class="edit" title="Edit" data-toggle="tooltip"><i class="mdi mdi-pencil"></i></a>' +
	'<a class="delete" title="Delete" data-toggle="tooltip"><i class="mdi mdi-delete"></i></a>';

	/* Append table with add row form on add new button click */
	$(".add-new").click(function(){
		$(this).attr("disabled", "disabled");
		var index = $("table tbody tr:last-child").index();
		var row = '<tr>' +
			'<td>' +
				'<select name="pt_specimen[]" class="form-control" id="pathogen">' +
					'<option value="0">-- โปรดเลือก --</option>' +
					@php
						foreach($patient_specimen as $key => $value) {
								$specimen_val = $value['specimen_type_id'];
							if ($specimen_val == 9) {
								$specimen_name = $specimen[$specimen_val]->name_en." [".$value['specimen_other']."]";
							} else {
								$specimen_name = $specimen[$specimen_val]->name_en;
							}
							echo "'<option value=\"".$specimen_val."\">".$specimen_name."</option>' + \n";
						}
					@endphp
				'</select>' +
			'</td>' +
			'<td>' +
				'<select name="pathogen[]" class="form-control" id="pathogen">' +
					'<option value="0">-- โปรดเลือก --</option>' +
					@php
						$pathogen->each(function ($item, $key) {
							echo "'<option value=\"".$key."\">".$item->patho_name_en."</option>' + \n";
						});
					@endphp
				'</select>' +
			'</td>' +
			'<td><input type="text" class="form-control" name="pathogen_strain[]" id="pathogen_m"></td>' +
			'<td><input type="text" class="form-control" name="pathogen_note[]" id="note"></td>' +
			'<td>' + actions + '</td>' +
		'</tr>';
		$("table").append(row);
		$("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
		$('[data-toggle="tooltip"]').tooltip();
	});

	/* Add row on add button click */
	$(document).on("click", ".add", function() {
		var sempty = false;
		var empty = false;
		var select = $(this).parents("tr").find('select[name="pathogen[]"]');
		select.each(function() {
			if ($(this).val() == '0') {
				$(this).addClass("error");
				sempty = true;
			} else {
				$(this).removeClass("error");
			}
		});
		var input = $(this).parents("tr").find('input[type="text"]');
		input.each(function() {
			if (!$(this).val()) {
				$(this).addClass("error");
				empty = true;
			} else{
				$(this).removeClass("error");
			}
		});
		$(this).parents("tr").find(".error").first().focus();
		if(!sempty && !empty) {
			input.each(function() {
				$(this).parent("td").html("<input type='text' name='" + $(this).attr("name") + "' class='form-control' value='" + $(this).val() + "'>");
			});
			$(this).parents("tr").find(".add, .edit").toggle();
			$(".add-new").removeAttr("disabled");
		}
	});

	/* Edit row on edit button click */
	$(document).on("click", ".edit", function(){
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"]');
		input.each(function(){
			$(this).parent("td").html("<input type='text' name='" + $(this).attr("name") + "' class='form-control' value='" + $(this).val() + "'>");
		});
		$(this).parents("tr").find(".add, .edit").toggle();
		$(".add-new").attr("disabled", "disabled");
	});

	// Delete row on delete button click
	$(document).on("click", ".delete", function(){
		$(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
	});
});
</script>
<script>
function ConvertFormToJSON(form){
	var array = jQuery(form).serializeArray();
	var json = {};
	jQuery.each(array, function() {
		json[this.name] = this.value || '';
	});
	return json;
}
</script>
@endsection
