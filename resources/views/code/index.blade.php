@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/DataTables-1.10.20/css/jquery.dataTables.min.css') }}">
<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/libs/toastr/build/toastr.min.css') }}">
@endsection
@section('internal-style')
<style type="text/css">
.page-wrapper {background: white !important;}
.dataTables_wrapper {width: 100% !important;font-family: 'Fira-code' !important;}
#code-table {width: 100% !important;}
table.dataTable tr.odd { background-color: #F6F6F6;  border:1px lightgrey;}
table.dataTable tr.even{ background-color: white; border:1px lightgrey; }
.error {display: none;margin-left: 10px;}
.error_show {color: red;margin-left: 10px;}
input.invalid, textarea.invalid {border: 2px solid red;}
input.valid, textarea.valid {border: 2px solid green;}
.toast {opacity: 1 !important;}
#toast-container > div {opacity: 1 !important;}
</style>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Print</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Code</a></li>
						<li class="breadcrumb-item active" aria-current="page">Create</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	@if(Session::has('success'))
		<div class="alert alert-success">
			<i class="fas fa-check-circle"></i> {{ Session::get('success') }}
			@php
				Session::forget('success');
			@endphp
		</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger">
			<i class="fas fa-times-circle"></i> {{ Session::get('error') }}
			@php
				Session::forget('error');
			@endphp
		</div>
	@endif
	@if(count($errors) > 0)
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	@endif
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="d-md-flex align-items-center">
						<div>
							<h4 class="card-title">สร้างแบบฟอร์มบันทึกข้อมูลใหม่</h4>
							<h5 class="card-subtitle">Create new form</h5>
						</div>
					</div>
					<div class="alert" role="alert" style="border:1px solid #ccc;">
						<div class="card">
							<form id="patient_form" class="mt-4 mb-3">
								@role('admin')
								<div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<div class="form-group">
											<label for="province">จังหวัด <span class="text-danger">&#42;</span></label>
											<select name="province" class="form-control selectpicker show-tick" id="select_province" data-live-search="true" data-style="btn-danger" >
												<option value="0">-- เลือกจังหวัด --</option>
												@php
													$provinces = Session::get('provinces');
													$provinces->each(function ($item, $key) {
														echo "<option value=\"".$item->province_id."\">".$item->province_name."</option>\n";
													});
												@endphp
											</select>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<div class="form-group">
											<label for="hospital">โรงพยาบาล <span class="text-danger">&#42;</span></label>
											<select name="hospcode" class="form-control selectpicker show-tick" id="select_hospital" data-live-search="true" data-style="btn-danger" disabled>
												<option value="0">-- เลือกโรงพยาบาล --</option>
											</select>
										</div>
									</div>
								</div>
								@endrole
								@role('hosp-group')
								<div class="form-row">
									{{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<div class="form-group">
											<label for="province">จังหวัด <span class="text-danger">&#42;</span></label>
											<select name="province" class="form-control selectpicker show-tick" id="select_province" data-live-search="true">
												<option value="0">-- เลือกจังหวัด --</option>
												@foreach ($hospLst as $key => $value)
													<option value="{{ $value['prov_code'] }}">{{ $value['prov_name'] }}</option>
												@endforeach
											</select>
										</div>
									</div> --}}
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<div class="form-group">
											<label for="hospital">โรงพยาบาล <span class="text-danger">&#42;</span></label>
											<select name="hospcode" class="form-control selectpicker show-tick" id="select_hospital" data-live-search="true">
												<option value="0">-- เลือกโรงพยาบาล --</option>
												@foreach ($hospLst as $key1 => $value1)
													<option value="{{ $value1['hospcode'] }}">{{ $value1['hosp_name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								@endrole
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<div class="form-group">
										<label for="titleName">คำนำหน้าชื่อ <span class="text-danger">&#42;</span></label>
										<select name="titleNameInput" class="form-control selectpicker show-tick select-title-name" id="title_name_input">
											<option value="0">-- โปรดเลือก --</option>
											@php
												$titleName->each(function ($item, $key) {
													echo "<option value=\"".$item->id."\">".$item->title_name."</option>";
												});
											@endphp
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<label for="otherTitleNameInput">อื่นๆ ระบุ</label>
									<input type="text" name="otherTitleNameInput" class="form-control" id="other_title_name_input" placeholder="คำนำหน้าชื่ออื่นๆ" disabled>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<label for="firstNameInput">ชื่อจริง <span class="text-danger">&#42;</span></label>
									<input type="text" name="firstNameInput" class="form-control" id="first_name_input" placeholder="ชื่อ">
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<label for="lastNameInput">นามสกุล <span class="text-danger">&#42;</span></label>
									<input type="text" name="lastNameInput" class="form-control" id="last_name_input" placeholder="นามสกุล">
								</div>
							</div>
							<div class="form-row">
							</div>
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<label for="hnInput">HN <span class="text-danger">&#42;</span></label>
									<input type="text" name="hnInput" class="form-control" id="hn_input" placeholder="HN">
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
									<label for="anInput">AN</label>
									<input type="text" name="anInput" class="form-control" id="an_input" placeholder="AN">
								</div>
							</div>
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3 {{ $errors->has('patientType') ? 'border-danger' : '' }}">
									<div class="form-group">
										<label for="patient">ประเภทผู้ป่วย <span class="text-danger">&#42;</span></label>
										<div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="patientType" value="opd" @if (old('patientType') == 'opd') checked @endif class="custom-control-input pt-type" id="opdCheckbox">
												<label for="opdCheckbox" class="custom-control-label normal-label">ผู้ป่วยนอก (OPD)/ILI</label>
											</div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="patientType" value="ipd" @if (old('patientType') == 'ipd') checked @endif class="custom-control-input pt-type" id="ipdCheckbox">
												<label for="ipdCheckbox" class="custom-control-label normal-label">ผู้ป่วยใน (IPD)/SARI</label>
											</div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="patientType" value="icu" @if (old('patientType') == 'icu') checked @endif class="custom-control-input pt-type" id="icuCheckbox">
												<label for="icuCheckbox" class="custom-control-label normal-label">ผู้ป่วยหนัก/ICU</label>
											</div>
										</div>
									</div>
									<span class="text-danger">{{ $errors->first('patientType') }}</span>
								</div>
							</div>
							<!-- specimen -->
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3 mb-3">
									<label for="specimenInput">ชนิดของตัวอย่างที่ส่งตรวจ <span class="text-danger">&#42;</span></label>
									<div class="table-responsive">
										<table class="table" id="specimen_table">
											<thead class="bg-custom-1 text-light">
												<tr>
													<th scope="col">ตัวอย่างส่งตรวจ</th>
													<th scope="col">วันที่เก็บตัวอย่าง</th>
												</tr>
											</thead>
											<tfoot></tfoot>
											<tbody>
											@php
											$oldSpecimenDate = "";
											foreach ($specimen as $key => $val) {
												if (!empty($val->name_th)) {
													$speciman_name = $val->name_th;
												} else {
													$speciman_name = $val->name_en;
												}
												if (!empty($val->abbreviation)) {
													$abbreviation = "&nbsp;(".$val->abbreviation.")";
												} else {
													$abbreviation = null;
												}
												if (!empty($val->note)) {
													$note = "&nbsp;(".$val->note.")";
												} else {
													$note = null;
												}
												if ($val->other_field == 'Yes') {
													$oth_str = "ระบุ";
												} else {
													$oth_str = null;
												}

												$htm = "";
												$htm .= "<tr id=\"specimen_tr".$val->id."\">\n";
													$htm .= "<td>\n";
														$htm .= "<div class=\"form-group row\">\n";
															$htm .= "<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6\">\n";
																$htm .= "<div class=\"custom-control custom-checkbox custom-control-inline\">\n";
																	$htm .= "<input type=\"checkbox\" name=\"specimen".$val->id."\" value=\"".$val->id."\" ";
																	if (old("specimen".$val->id) == $val->id) {
																		$htm .= "checked ";
																	}
																	$htm .= "class=\"custom-control-input form-check-input specimen-chk-".$val->id."\" id=\"specimen_chk".$val->id."\">\n";
																	$htm .= "<label for=\"specimen_chk".$val->id."\" class=\"custom-control-label font-weight-normal\">".$speciman_name." ".$abbreviation."&nbsp;".$note."</label>\n";
																$htm .= "</div>\n";
															$htm .= "</div>\n";
															if ($val->other_field == 'Yes') {
																$htm .= "<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6\">\n";
																	$htm .= "<input type=\"text\" name=\"specimenOth".$val->id."\" value=\"".old("specimenOth".$val->id)."\" class=\"form-control\" id=\"specimen_".$val->id."oth\" placeholder=\"".$oth_str."\"";
																	if (empty(old("specimenOth".$val->id))) {
																		$htm .= " disabled>\n";
																	} else {
																		$htm .= ">\n";
																	}
																$htm .= "</div>\n";
															}
														$htm .= "</div>\n";
													$htm .= "</td>\n";
													$htm .= "<td>\n";
														$htm .= "<div class=\"form-group row\">\n";
															$htm .= "<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12\">\n";
																$htm .= "<div class=\"input-group date\" id=\"specimenDate".$val->id."\">\n";
																	$htm .= "<div class=\"input-group\">\n";
																		$htm .= "<input type=\"text\" name=\"specimenDate".$val->id."\" value=\"".old("specimenDate".$val->id)."\" class=\"form-control\" id=\"specimenDate_".$val->id."\"";
																		if (empty(old("specimenDate".$val->id))) {
																			$htm .= " disabled";
																		} else {
																			$htm .= "";
																			$oldSpecimenDate .= "
																			$('#specimenDate".$val->id."').datepicker({
																				format: 'dd/mm/yyyy',
																				todayHighlight: true,
																				todayBtn: true,
																				autoclose: true
																			});\n";
																		}
																		$htm .= " readonly>\n";
																		$htm .= "<div class=\"input-group-append\">\n";
																			$htm .= "<span class=\"input-group-text\"><i class=\"mdi mdi-calendar\"></i></span>\n";
																		$htm .= "</div>\n";
																	$htm .= "</div>\n";
																$htm .= "</div>\n";
															$htm .= "</div>\n";
														$htm .= "</div>\n";
													$htm .= "</td>\n";
												$htm .= "</tr>\n";
												echo $htm;
											}
											@endphp
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- end specimen -->
							<button type="button" class="btn btn-success" id="btn_submit">สร้างรหัสใหม่</button>
						</form>
					</div>
					<div id="patient_data">
						<!--
						<table class="table display mT-2 mb-4" id="code_table" role="table">
							<thead>
								<tr>
									<th>ลำดับ</th>
									<th>รหัส</th>
									<th>ชื่อ-สกุล</th>
									<th>HN</th>
									<th>สถานะ (รพ.)</th>
									<th>สถานะ (Lab)</th>
									<th>วัน/เวลา</th>
									<th>จัดการ</th>
								</tr>
							</thead>
							<tfoot></tfoot>
							<tbody>

							php
							foreach ($patients as $key => $value) {
								switch ($value->hosp_status) {
									case 'new':
										$hosp_class = 'danger';
										break;
									case 'updated':
										$hosp_class = 'success';
										break;
									default :
										$hosp_class = 'info';
										break;
								}
								switch ($value->lab_status) {
									case 'pending':
										$lab_class = 'warning';
										break;
									case 'updated':
										$lab_class = 'success';
										break;
									default :
										$lab_class = 'info';
										break;
								}
								echo "<tr>";
									echo "<td>".$value->id."</td>";
									echo "<td><span class=\"text-danger\">".$value->lab_code."</span></td>";
									if ($value->title_name != 6) {
										echo "<td>".$titleName[$value->title_name]->title_name.$value->first_name." ".$value->last_name."</td>";
									} else {
										echo "<td>".$value->title_name_other.$value->first_name." ".$value->last_name."</td>";
									}
									echo "<td>".$value->hn."</td>";
									echo "<td><span class=\"badge badge-pill badge-".$hosp_class."\">".ucfirst($value->hosp_status)."</span></td>";
									echo "<td><span class=\"badge badge-pill badge-".$lab_class."\">".ucfirst($value->lab_status)."</span></td>";
									echo "<td>".$value->created_at."</td>";
									echo "<td>";
										echo "<a href=\"".route('createPatient', ['id'=>$value->id])."\" class=\"btn btn-cyan btn-sm\"><i class=\"fas fa-plus-circle\"></i></a>&nbsp;";
										echo "<button type=\"button\" id=\"btn_delete".$value->id."\" class=\"btn btn-danger btn-sm\" value=\"".$value->id."\"><i class=\"fas fa-trash\"></i></button>";
									echo "</td>";
								echo "</tr>";
							}
							endphp
							</tbody>
						</table>
						-->
					</div>
				</div><!-- card body -->
			</div><!-- card -->
		</div><!-- column -->
	</div><!-- row -->
</div>
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/DataTables-1.10.20/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-validate-2.2.0/dist/bootstrap-validate.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(document).ready(function() {
	$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

	/* default get data tot tbl */
	$.ajax({
		type: 'GET',
		url: "{{ route('ajaxRequestTable') }}",
		dataType: "HTML",
		success: function(data) {
			$('#patient_data').html(data);
		}
	});

	/* data table */
	$('#code_table').DataTable({
		"searching": false,
		"paging": false,
		"ordering": true,
		"info": false,
		responsive: true,
		columnDefs: [{
			targets: -1,
			className: 'dt-head-right dt-body-right'
		}]
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
					alertMessage(xhr.status, error, 'Flu Right Site');
				}
			});
		} else {
			$('#select_hospital').empty();
			$('#select_hospital').append('<option val="0">-- เลือกโรงพยาบาล --</option>');
			$('#select_hospital').prop('disabled', true);
			$('#select_hospital').selectpicker("refresh");
		}
	});

	/* title name */
	$('#title_name_input').change(function() {
		if ($('select#title_name_input').val() === '6') {
			$('#other_title_name_input').prop('disabled', false);
		} else {
			$('#other_title_name_input').val('');
			$('#other_title_name_input').prop('disabled', true);
		}
	});

	/* patient type */
	$('.pt-type').click(function() {
		$('.pt-type').not(this).prop('checked', false);
	});

	@php
	$htm = "";
		foreach ($patients as $key => $value) {
			$htm .= "
			$('#btn_delete".$value->id."').click(function(e) {
				toastr.warning(
					'Are you sure to delete? <br><br><button class=\"btn btn-cyan btc\" value=\"0\">Cancel</button> <button class=\"btn btn-danger btk\" value=\"".$value->id."\">Delete</button>',
					'Flu Right Site',
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
	/* specimen table && checkbox */
	foreach ($specimen as $key => $val) {
		echo "
			var spec_n = $('.specimen-chk-".$val->id."').filter(':checked').length;
			if (spec_n === 1) {
				var hasClass = $('#specimen_tr".$val->id."').hasClass('highlight');
				if (!hasClass) {
					$('#specimen_tr".$val->id."').addClass('highlight');
				}
			}
		\n";

		echo "
		$('.specimen-chk-".$val->id."').click(function() {
			$('.specimen-chk-".$val->id."').not(this).prop('checked', false);
			let number = $('.specimen-chk-".$val->id."').filter(':checked').length;
			if (number == 1) {
				let hasClass = $('#specimen_tr".$val->id."').hasClass('highlight');
				if (!hasClass) {
					$('#specimen_tr".$val->id."').addClass('highlight');
				}
			} else {
				$('#specimen_tr".$val->id."').removeClass('highlight');
			}";
			if ($val->other_field == 'Yes') {
				echo "
					if ($('#specimen_chk".$val->id."').prop('checked') == true) {
						$('#specimen_".$val->id."oth').prop('disabled', false);
						$('#specimenDate_".$val->id."').prop('disabled', false);
					} else {
						$('#specimen_".$val->id."oth').val('');
						$('#specimen_".$val->id."oth').prop('disabled', true);
						$('#specimenDate_".$val->id."').val('');
						$('#specimenDate_".$val->id."').prop('disabled', true);
					}";
			} else {
				echo "
					if ($('#specimen_chk".$val->id."').prop('checked') == true) {
						$('#specimenDate_".$val->id."').prop('disabled', false);
					} else {
						$('#specimenDate_".$val->id."').val('');
						$('#specimenDate_".$val->id."').prop('disabled', true);
					}";
			}
			echo "
				$('#specimenDate".$val->id."').datepicker({
					format: 'dd/mm/yyyy',
					todayHighlight: true,
					todayBtn: true,
					autoclose: true
				});";
			echo "});\n";
		}
		if (isset($oldSpecimenDate)) {
			echo $oldSpecimenDate;
		}
	@endphp

	/* submit ajax */
	$("#btn_submit").click(function(e) {
		e.preventDefault();
		var input = ConvertFormToJSON("#patient_form");
		$.ajax({
			type: 'POST',
			url: "{{ route('ajaxRequest') }}",
			data: input,
			success: function(data) {
				if (data.status == 204) {
					toastr.warning(data.msg, "Flu Right Site",
						{
							"closeButton": true,
							"positionClass": "toast-top-center",
							"progressBar": true,
							"showDuration": "500"
						}
					);
				} else if (data.status == 200) {
					$.ajax({
						type: 'GET',
						url: "{{ route('ajaxRequestTable') }}",
						dataType: "HTML",
						success: function(res) {
							$('#patient_data').html(res);
							$("#select_province").val('0').selectpicker("refresh");
							$("#select_hospital").val('0').selectpicker("refresh");
							$("#title_name_input").val('0').selectpicker("refresh");
							$('#patient_form').find('input:text').val('');
							toastr.success(data.msg, "Flu Right Site",
								{
									"closeButton": true,
									"positionClass": "toast-top-center",
									"progressBar": true,
									"showDuration": "500"
								}
							);
							$("#patient_form")[0].reset();
						},
						error: function(jqXhr, textStatus, errorMessage) {
							$("#select_province").val('0').selectpicker("refresh");
							$("#select_hospital").val('0').selectpicker("refresh");
							$("#title_name_input").val('0').selectpicker("refresh");
							$('#patient_form').find('input:text').val('');
							toastr.error(jqXhr.status + " " + textStatus + " " + errorMessage, " Flu Right Site",
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
				} else {
					alert('Error! status ' + data.status + ' ' + data.message);
				}
			},
			error: function(data, status, error) {
				$("#select_province").val('0').selectpicker("refresh");
				$("#select_hospital").val('0').selectpicker("refresh");
				$("#title_name_input").val('0').selectpicker("refresh");
				$('#patient_form').find('input:text').val('');
				toastr.error(data.status + " " + status + " " + error, " Flu Right Site",
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
						method: 'GET',
						url: '{{ route('ajaxRequestTable') }}',
						data: {pj:data.status},
						dataType: 'HTML',
						success: function(data) {
							$('#patient_data').html(data);
						},
						error: function(data, status, error) {
							alertMessage(500);
						}
					});
				}
			},
			error: function(data, status, error) {
				alertMessage(500);
			}
		});
	});
</script>
<script>
function resetForm($form) {
	$form.find('input:text, input:password, input:file, select, textarea').val('');
	$form.find('input:radio, input:checkbox')
	.removeAttr('checked').removeAttr('selected');
}
function ConvertFormToJSON(form){
	var array = jQuery(form).serializeArray();
	var json = {};
	jQuery.each(array, function() {
		json[this.name] = this.value || '';
	});
	return json;
}
function alertMessage(status, message, title) {
	$status = parseInt(status);
	if (status == 200) {
		toastr.success(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-right',
				'progressBar': true,
				'showDuration': '600'
			}
		);
	} else if (status == 204) {
		toastr.warning(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-right',
				'progressBar': true,
				'showDuration': '800'
			}
		);
	} else {
		toastr.error(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-right',
				'progressBar': true,
				'showDuration': '800'
			}
		);
	}
}
function cleartoasts() {toastr.clear();}
</script>
@endsection
