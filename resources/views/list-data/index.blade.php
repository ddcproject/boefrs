@extends('layouts.index')
@section('custom-style')
	<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/datatables.min.css') }}">
	<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/Buttons-1.6.1/css/buttons.bootstrap4.min.css') }}">
	<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/css/responsive.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/libs/select2/dist/css/select2.min.css') }}">
	<link rel='stylesheet' href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/libs/toastr/build/toastr.min.css') }}">
@endsection
@section('internal-style')
<style>
.dataTables_wrapper {
	width: 100% !important;
	font-family: 'Fira-code' !important;
}
table {
	width: 100% !important;
}
table.dataTable tr.odd { background-color: #F6F6F6;  border:1px lightgrey;}
table.dataTable tr.even{ background-color: white; border:1px lightgrey; }
.error{
	display: none;
	margin-left: 10px;
}
.error_show{
	color: red;
	margin-left: 10px;
}
input.invalid, textarea.invalid{
	border: 2px solid red;
}

input.valid, textarea.valid{
	border: 2px solid green;
}
</style>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
@php
	//$titleName = $titleName->all();
	$user_hospital_name = Session::get('user_hospital_name');
	$provinces = Session::get('provinces');
@endphp
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Print</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Data</a></li>
						<li class="breadcrumb-item active" aria-current="page">Lists</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div class="d-md-flex align-items-center" style="border-bottom:1px solid #EAEAEA">
				<div>
					<h4 class="card-title">รายการข้อมูล โครงการเฝ้าระวังเชื้อไวรัสก่อโรคระบบทางเดินหายใจ</h4>
					<h5 class="card-subtitle">Flu-BOE</h5>
				</div>
			</div>
			<form name="search_frm" class="mx-4" id="search_frm">
				<div class="form-group row pt-4">
					<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 my-1">
					@role('admin')
						<select name="province" class="form-control selectpicker show-tick" id="select_province" data-live-search="true">
							<option value="0">-- จังหวัด --</option>
							@php
								$provinces->each(function ($item, $key) {
									echo "<option value=\"".$item->province_id."\">".$item->province_name."</option>";
								});
							@endphp
						</select>
					@endrole
					@role('hospital|lab')
						<select name="province" class="form-control selectpicker show-tick" id="select_province" data-live-search="true" readonly>
							<option value="{{ auth()->user()->province }}">{{ $provinces[auth()->user()->province]->province_name }}</option>
						</select>
					@endrole
					</div>
					<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 my-1">
					@role('admin')
						<select name="hospcode" class="form-control selectpicker show-tick" id="select_hospital" disabled>
							<option value="0">-- โรงพยาบาล --</option>
						</select>
					@endrole
					@role('hospital|lab')
						<select name="hospcode" class="form-control selectpicker" id="select_hospital" disabled>
							<option value="{{ auth()->user()->hospcode }}">{{ $user_hospital_name }}</option>
						</select>
					@endrole
					</div>
					<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 my-1">
						@role('admin')
						<select name="lab_status" class="form-control my-1 select-status" id="select_status" multiple="multiple" disabled style="width:100%;">
						@endrole
						@role('hospital|lab')
						<select name="lab_status" class="form-control my-1 select-status" id="select_status" multiple="multiple" style="width:100%;">
						@endrole
							<option value="new">New</option>
							<option value="updated">Updated</option>
						</select>
					</div>
					<div class="col-sm-12 col-md-1 col-lg-1 col-xl-1 mt-1">
						<!-- <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button> -->
						<a href="#" class="btn btn-primary" id="btn_search" style="height:38px;"><i class="fas fa-search"></i> ค้นหา</a>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div id="patient_data">
								<table class="table display mb-4" id="code_table" role="table">
									<thead>
										<tr>
											<th>ลำดับ</th>
											<th>ชื่อ-สกุล</th>
											<th>HN</th>
											<th>รหัส</th>
											<th>รหัส รพ.</th>
											<th>สถานะ [รพ]</th>
											<th>สถานะ [Lab]</th>
											<th>#</th>
										</tr>
									</thead>
									<tfoot></tfoot>
									<tbody>
									@php
										if ($patients) {
											$patients->each(function ($item, $key) use ($titleName) {
												switch ($item->hosp_status) {
													case 'new':
														$hosp_class = 'primary';
														break;
													case 'updated':
														$hosp_class = 'success';
														break;
													default :
														$hosp_class = 'info';
														break;
												}
												switch ($item->lab_status) {
													case 'pending':
														$lab_class = 'primary';
														break;
													case 'updated':
														$lab_class = 'success';
														break;
													default :
														$lab_class = 'info';
														break;
												}
												echo "<tr>";
													echo "<td>".$item->id."</td>";
													if ($item->title_name != 6) {
														echo "<td>".$titleName[$item->title_name]->title_name.$item->first_name." ".$item->last_name."</td>";
													} else {
														echo "<td>".$item->title_name_other.$item->first_name." ".$item->last_name."</td>";
													}
													echo "<td>".$item->hn."</td>";
													echo "<td><span class=\"text-danger\">".$item->lab_code."</span></td>";
													echo "<td>".$item->ref_user_hospcode."</td>";
													echo "<td><span class=\"badge badge-pill badge-".$hosp_class."\">".ucfirst($item->hosp_status)."</span></td>";
													echo "<td><span class=\"badge badge-pill badge-".$lab_class."\">".ucfirst($item->lab_status)."</span></td>";
													echo "<td>";
														echo "<a href=\"".route('viewPatient', ['id'=>$item->id])."\" class=\"btn btn-success btn-sm\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View\"><i class=\"fas fa-eye\"></i></a>&nbsp;";
															if ($item->hosp_status == 'new') {
																echo "<a href=\"".route('createPatient', ['id'=>$item->id])."\" class=\"btn btn-cyan btn-sm\"><i class=\"fas fa-plus-circle\"></i></a>&nbsp;";
															} else {
																echo "<a href=\"".route('editPatient', ['id'=>$item->id])."\" class=\"btn btn-warning btn-sm\"><i class=\"fas fa-edit\"></i></a>&nbsp;";
															}
															echo "<button type=\"button\" id=\"btn_delete".$item->id."\" class=\"btn btn-danger btn-sm\" value=\"".$item->id."\"><i class=\"fas fa-trash-alt\"></i></button>";
													echo "</td>";
												echo "</tr>";
											});
										}
									@endphp
									</tbody>
								</table>
							</div>
						</div><!-- card body -->
					</div><!-- card -->
				</div><!-- column -->
			</div><!-- row -->
		</div><!-- card body -->
	</div><!-- card -->
</div><!-- contrainer -->
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/Buttons-1.6.1/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/js/responsive.bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script>
$(document).ready(function() {
	/* ajax request */
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	/* data table */
	$('#code_table').DataTable({
		"searching": false,
		"paging": true,
		"pageLength": 25,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"ordering": true,
		"info": false,
		responsive: true,
		columnDefs: [{
			targets: -1,
			className: 'dt-head-right dt-body-right'
		}],
		dom: 'frti"<bottom"Bp>',
		buttons: [
			{extend: 'copy', text: '<i class="far fa-copy"></i>', titleAttr: 'Copy', className: 'btn btn-outline-info'},
			{extend: 'csv', text: '<i class="far fa-file-alt"></i>', titleAttr: 'CSV', className: 'btn btn-outline-info'},
			{extend: 'excel', text: '<i class="far fa-file-excel"></i>', titleAttr: 'Excel', className: 'btn btn-outline-info'},
			{extend: 'pdf', text: '<i class="far fa-file-pdf"></i>', titleAttr: 'PDF', className: 'btn btn-outline-info'},
			{extend: 'print', text: '<i class="fas fa-print"></i>', titleAttr: 'Print', className: 'btn btn-outline-info'}
		]
	});

	@php
	if ($patients) {
		$htm = "";
			foreach ($patients as $key => $value) {
				$htm .= "
				$('#btn_delete".$value->id."').click(function(e) {
					toastr.warning(
						'Are you sure to delete? <br><br><button class=\"btn btn-cyan btc\" value=\"0\">Cancel</button> <button class=\"btn btn-danger btk\" value=\"".$value->id."\">Delete</button>',
						'Flu Right Size',
						{
							'closeButton': true,
							'positionClass': 'toast-top-center',
							'progressBar': true,
							'showDuration': '500'
						}
					);
				});";
			}
		echo $htm;
	}
	@endphp

	/* select province */
	$('#select_province').change(function() {
		var prov_id = $('#select_province').val();
		if (prov_id > 0) {
			$('#select_hospital').prop('disabled', false);
			$.ajax({
				type: "GET",
				url: "{{ route('ajaxGetHospByProv') }}",
				dataType: 'HTML',
				data: {prov_id: prov_id},
				success: function(data) {
					$('#select_hospital').empty();
					$('#select_hospital').html(data);
					$('#select_hospital').selectpicker("refresh");
				},
				error: function(xhr, status, error) {
					alertMessage(xhr.status, error, 'Flu Right Size');
				}
			});
		} else {
			$('#select_hospital').empty();
			$('#select_hospital').append('<option value="0">-- เลือกโรงพยาบาล --</option>');
			$('#select_hospital').prop('disabled', true);
			$('#select_hospital').selectpicker("refresh");
			$('#select_status').val(null).trigger('change');
			$('#select_status').prop('disabled', true);
		}
	});

	$('#select_hospital').change(function() {
		var hosp_id = $('#select_hospital').val();
		if (hosp_id != 0) {
			$('#select_status').prop('disabled', false);
		} else {
			$('#select_status').val(null).trigger('change');
			$('#select_status').prop('disabled', true);
		}
	});

	$(".select-status").select2({
		closeOnSelect : false,
		placeholder : "-- สถานะ --",
		allowHtml: true,
		allowClear: true,
		tags: true,
		closeOnSelect : true
	});

	/* search ajax */
	$("#btn_search").click(function(e) {
		var pv = $('#select_province').val();
		var hp = $('#select_hospital').val();
		var st = $('#select_status').val();
		if (pv == "") {
			pv = 0;
		}
		if (hp == "") {
			hp = 0;
		}
		if (st == "") {
			st = 0;
		}
		$.ajax({
			method: 'POST',
			url: '{{ route('ajaxSearchData') }}',
			data: {pv:pv, hp:hp, st:st},
			dataType: 'HTML',
			success: function(data) {
				$('#patient_data').html(data);
			},
			error: function(xhr, status, error){
				alertMessage(xhr.status, error, 'Flu Right Sizex');
			}
		});
	});

	/* alert message */
	@php
		if (Session::has('message')) {
			$message = Session::get('message');
			$message = $message->all();
			Session::forget('message');
			echo "alertMessage('".$message['status']."', '".$message['msg']."', '".$message['title']."')";
		}
	@endphp
});
</script>
<script>
	$('body').on('click', '.btc', function (toast) {
		toastr.clear();
	});
	$('body').on('click', '.btk', function (toast) {
		var val = toast.target.value;
		$.ajax({
			method: 'POST',
			url: '{{ route('codeSoftConfirmDelete') }}',
			data: {val:val},
			dataType: 'JSON',
			success: function(data) {
				if (data.status === '200') {
					$.ajax({
						method: 'POST',
						url: '{{ route('ajaxListDataAfterDel') }}',
						data: {pj:data.status},
						dataType: 'HTML',
						success: function(data) {
							$('#patient_data').html(data);
						},
						error: function(data, status, error) {
							alertMessage(500, error, 'Flu Right Size');
						}
					});
				}
			},
			error: function(data, status, error) {
				alertMessage(500, error, 'Flu Right Size');
			}
		});
	});
</script>
<script>
function alertMessage(status, message, title) {
	if (status == 200) {
		toastr.success(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '600',
				"timeOut": "1000",
			}
		);
	} else if (status == 400) {
		toastr.warning(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '600',
			}
		);
	} else {
		toastr.error(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '600',
			}
		);
	}
}
</script>
@endsection
