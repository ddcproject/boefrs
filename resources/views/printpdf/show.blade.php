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

/* inner table */
.table1 {
	width: 100%;
	max-width: 100%;
	margin: 16px 5px;
}
.table1 thead tr th {
	border-top: none;
	border-right: none;
	border-left: none;
	font-weight: bold;
	border-bottom: 1px solid #ccc;
}
.table1>tbody>tr>td {
	padding: 2px 4px;
	vertical-align: middle;
	border-top: none;
	border-right: none;
	border-left: none;
	border-bottom: 1px solid #eee;
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
						<li class="breadcrumb-item active" aria-current="page">Patient</li>
						<li class="breadcrumb-item active" aria-current="page">View</li>
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
					<Form action="/lab" method="POST" class="needs-validation custom-form-legend" novalidate>
						@method('POST')
						<div class="card">
							<div class="card-body">
								<div class="bd-callout bd-callout-info" style="margin-top:0;position:relative">
									<div style="position:absolute; top:2px; right:2px;">
										<img src="{{ URL::asset('qrcode/qr'.$data['patient_lab_code'].'.png') }}" />
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
											<div class="input-group-append">
												<span class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="top" title="โปรดเขียนรหัสนี้ลงบนแบบฟอร์ม">{{ $data['patient_lab_code'] }}</span>
												{{ csrf_field() }}
											</div>
										</div>
									</div>
									<h1 class="text-info">1. ข้อมูลผู้ป่วย</h1>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<label for="patientName">ชื่อ-นามสกุล</label>
											<input type="hidden" name="pid" value="{{ $data['patient_id'] }}">
											<input type="hidden" name="lab_code" value="{{ $data['patient_lab_code'] }}">
											<input type="text" name="pNameInput" class="form-control" value="{{ $data['patient_fullname'] }}" placeholder="ชื่อ-สกุล" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<label for="HN">HN</label>
											<input type="text" name="hnInput" value="{{ $data['patient_hn'] }}" class="form-control" placeholder="HN" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<label for="HN">AN</label>
											<input type="text" name="anInput" value="{{ $data['patient_an'] }}" class="form-control" placeholder="HN" readonly>
										</div>
									</div>
								</div>
								<div class="bd-callout bd-callout-custom-6" style="margin-top:0;position:relative">
									<h1 class="text-color-custom-2">2. ตัวอย่างส่งตรวจเพื่อหาสารพันธุกรรมหรือแยกเชื้อ</h1>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3 mb-3">
											<label for="specimenInput" style="display:none;">ชนิดของตัวอย่างที่ส่งตรวจ</label>
											<div class="table-responsive">
												<table class="table" data-show-columns="true" data-search="true" data-mobile-responsive="true" data-check-on-init="true" id="specimen_table">
													<thead class="bg-custom-2 text-light">
														<tr>
															<th>ตัวอย่างส่งตรวจ</th>
															<th>วันที่เก็บตัวอย่าง</th>
															<th>ผลการตรวจ</th>
														</tr>
													</thead>
													<tfoot></tfoot>
													<tbody>
													@foreach ($data['patient_specimen'] as $key => $val)
														@if ($val['s_id'] == null)
															@continue
														@endif
														<tr id="specimen_tr{{ $val['rs_id'] }}">
															<td>
																<div class="form-group row">
																	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-12">
																		<div class="custom-control custom-checkbox custom-control-inline">
																			<input type="checkbox" name="specimen{{ $val['rs_id'] }}" value="{{ $val['rs_id'] }}" class="custom-control-input form-check-input specimen-chk-{{ $val['rs_id'] }}" id="specimen_chk{{ $val['rs_id'] }}" @if ($val['s_id'] != null) checked @endif disabled>
																			<label for="specimen_chk{{ $val['rs_id'] }}" class="custom-control-label font-weight-normal">{{ $val['rs_name_en'] }}  {{ $val['rs_abbreviation'] != null ? " (".$val['rs_abbreviation'].")" : "" }} {{ $val['rs_note'] }}</label>
																		</div>
																	</div>
																	@if ($val['rs_other_field'] == 'Yes')
																		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
																			<input type="text" name="specimenOth{{ $val['rs_id'] }}" value="{{ $val['s_specimen_other'] }}" class="form-control" id="specimen_{{ $val['rs_id']."oth" }}" readonly>
																		</div>
																	@endif
																</div>
															</td>
															<td>
																<div class="form-group row">
																	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
																		<div class="input-group date" id="specimenDate{{ $val['rs_id'] }}">
																			<div class="input-group">
																				<input type="text" name="specimenDate{{ $val['rs_id'] }}" value="{{ $val['s_specimen_date'] }}" class="form-control" id="specimenDate_{{ $val['rs_id'] }}" @if ($val['s_specimen_date'] == null) disabled @endif readonly>
																				<div class="input-group-append">
																					<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</td>
															<td>
																<table class="table1 fixed">
																	<thead>
																		<tr>
																			<th scope="col">ชื่อเชื้อ </th>
																			<th scope="col">สายพันธุ์</th>
																			<th scope="col">หมายเหตุ</th>
																			<th scope="col">Print</th>
																		</tr>
																	</thead>
																	<tbody>
																		@foreach ($patient_lab as $lab_key => $lab_val)
																			@if ($lab_val['ref_specimen_id'] == $val['rs_id'])
																				<tr>
																					<td class="text-danger">{{ $pathogen[$lab_val['ref_pathogen_id']]->patho_name_en }}</td>
																					<td class="text-success">{{ $lab_val['pathogen_strain'] }}</td>
																					<td>{{ $lab_val['pathogen_note'] }}</td>
																					<td><a href="{{ route('viewprintpdfforlab',$lab_val['id']) }}" target="_blank"><i class="mdi mdi-printer"></i></a></td>
																				</tr>
																			@endif
																		@endforeach
																	</tbody>
																</table>
															</td>
														</tr>
													@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div><!-- bd-collout-3 -->

							</div><!-- card-body -->
						</div><!-- card -->
						<div class="border-top">
							<div class="card-body">
								<a href="{{ route('lab') }}" type="button" class="btn btn-primary">ปิดหน้านี้</a>
							</div>
						</div>
					</form>
				</div><!-- card-body -->
			</div><!-- card -->
		</div><!-- col -->
	</div><!-- row -->
</div><!-- container -->
@endsection
@section('bottom-script')
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

	$(function() {
		$('#specimen_table').bootstrapTable()
	})

	/* title name */
	$('#title_name_input').change(function() {
		if ($('select#title_name_input').val() === '6') {
			$('#other_title_name_input').prop('disabled', false);
		} else {
			$('#other_title_name_input').val('');
			$('#other_title_name_input').prop('disabled', true);
		}
	});

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
			$('#select_hospital').append('<option val="0">-- เลือกโรงพยาบาล --</option>');
			$('#select_hospital').prop('disabled', true);
			$('#select_hospital').selectpicker("refresh");
		}
	});

	/* Other symptom textbox */
	$('#other_sym_chk').click(function() {
		$('#other_sym_chk').not(this).prop('checked', false);
		if ($('#other_sym_chk').prop('checked') == true) {
			$('#other_sym_text').prop('disabled', false);
		} else {
			$('#other_sym_text').val('');
			$('#other_sym_text').prop('disabled', true);
		}
	});

	@php
	/* specimen tbl hilight on load */
	foreach ($data['patient_specimen'] as $key => $val) {
		echo "
		var n = $('.specimen-chk-".$val['rs_id']."').filter(':checked').length;
		if (n === 1) {
			var hasClass = $('#specimen_tr".$val['rs_id']."').hasClass('highlight');
			if (!hasClass) {
				$('#specimen_tr".$val['rs_id']."').addClass('highlight');
			}
		}\n";
	}
	/* specimen hilight switch*/
	/*$htm = "";
	foreach ($data['patient_specimen'] as $key => $val) {
		$htm .= "
		$('.specimen-chk-".$val['rs_id']."').click(function() {
			$('.specimen-chk-".$val['rs_id']."').not(this).prop('checked', false);
			let number = $('.specimen-chk-".$val['rs_id']."').filter(':checked').length;
			if (number == 1) {
				let hasClass =  $('#specimen_tr".$val['rs_id']."').hasClass('highlight');
				if (!hasClass) {
					$('#specimen_tr".$val['rs_id']."').addClass('highlight');
				}
			} else {
				$('#specimen_tr".$val['rs_id']."').removeClass('highlight');
			}\n";
			if ($val['rs_other_field'] == 'Yes') {
				$htm .= "
				if ($('#specimen_chk".$val['rs_id']."').prop('checked') == true) {
					$('#specimen_".$val['rs_id']."oth').prop('disabled', false);
					$('#specimenDate_".$val['rs_id']."').prop('disabled', false);
				} else {
					$('#specimen_".$val['rs_id']."oth').val('');
					$('#specimen_".$val['rs_id']."oth').prop('disabled', true);
					$('#specimenDate_".$val['rs_id']."').val('');
					$('#specimenDate_".$val['rs_id']."').prop('disabled', true);
				}\n";
			} else {
				$htm .= "
				if ($('#specimen_chk".$val['rs_id']."').prop('checked') == true) {
					$('#specimenDate_".$val['rs_id']."').prop('disabled', false);
				} else {
					$('#specimenDate_".$val['rs_id']."').val('');
					$('#specimenDate_".$val['rs_id']."').prop('disabled', true);
				}\n";
			}
		$htm .= "});\n";
	}
	echo $htm;
	/
	/* specimen date picker */
	/*
	foreach ($data['patient_specimen'] as $key => $val) {
		echo "
		$('#specimenDate".$val['rs_id']."').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			todayBtn: true,
			autoclose: true
		});\n";
	}*/
	@endphp
});
</script>
@endsection
