@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/toastr/build/toastr.min.css') }}">
<style>
/* label {font-weight: 600;color: #000000 !important;} */
.page-wrapper {background-color: #ffffff !important;}
input:-moz-read-only {background-color: #fafafa !important;}
input:read-only {background-color: #fafafa !important;}
.frmCode {font-family: 'Fira-code', tahoma !important;}
.select-custom select option {padding: 18px!important;}
.font-fira {font-family: 'Fira-code' !important;}
.input-group .bootstrap-select.form-control {z-index: 0;}
button {cursor: pointer;}
.has-error input[type="text"], .has-error input[type="email"], .has-error select {border: 1px solid #a94442;}
ul.err-msg {list-style-type: none;padding: 0;}
ul.err-msg li {margin-left: 20px;}
ul.err-msg li > i {padding-right: 8px;}
.span-80 {width: 80px !important;display: inline-block;}
.child-box {margin: 5px 0;}
</style>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
<div class="page-breadcrumb">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Form</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Form</li>
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
			@php Session::forget('success'); @endphp
		</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger">
			<i class="fas fa-times-circle"></i> {{ Session::get('error') }}
			@php Session::forget('error'); @endphp
		</div>
	@endif
	@if (count($errors) > 0)
		<div class = "alert alert-danger" style="margin-left:15px;">
			<h4 class="alert-heading"><i class=" fas fa-times-circle text-danger"></i> Error!</h4>
			<ul class="err-msg">
				@foreach ($errors->all() as $error)
					<li><i class="mdi mdi-alert-octagon text-danger"></i> {{ $error }}</li>
				@endforeach
			</ul>
			<hr>
			<p class="text-danger">โปรดตรวจสอบข้อมูลให้ถูกต้องอีกครั้ง ก่อนบันทึกใหม่</p>
		</div>
	@endif
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="d-md-flex align-items-center">
						<div>
							<h4 class="card-title">แบบเก็บข้อมูลโครงการเฝ้าระวังเชื้อไวรัสก่อโรคระบบทางเดินหายใจ</h4>
							<h5 class="card-subtitle">Flu-DOE</h5>
						</div>
					</div>
					<Form method="POST" action="{{ route('addPatient') }}" class="form-horizontal" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="bd-callout bd-callout-info" style="margin-top:0;position:relative">
							<div style="position:absolute;top:10px;right:10px;z-index:1">
								<img src="{{ URL::asset('qrcode/qr'.$patient[0]->lab_code.'.png') }}" />
							</div>
							@include('patients.section1')
						</div><!-- callout-1 -->
						<div class="bd-callout bd-callout-custom-2" style="margin-top:0;">
							@include('patients.section2')
						</div><!-- callout-2 -->
						<div class="bd-callout bd-callout-custom-6" style="margin-top:0;">
							@include('patients.section3')
						</div><!-- callout-3 -->
						<div class="bd-callout bd-callout-danger" style="margin-top:0;">
							@include('patients.section4')
						</div><!-- callout-4 -->
						<div class="border-top">
							<div class="card-body">
								<button type="submit" class="btn btn-info">บันทึกข้อมูล</button>
								<a href="{{ route('code.index') }}" class="btn btn-primary">ปิดหน้านี้</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/jquery-blockUI/jquery.blockUI.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-validate-2.2.0/dist/bootstrap-validate.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(document).ready(function() {
	$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
	@php
	if (Session::has('message')) {
		$message = Session::get('message');
		echo "alertMessage(500, 'ko', 'nok');";
	}
	@endphp
	/* title name */
	$('#title_name_input').change(function() {
		var id = $('#title_name_input').val();
		if (id === '6') {
			$('#other_title_name_input').val('');
			$('#other_title_name_input').prop('disabled', false);
		} else {
			$('#other_title_name_input').val('');
			$('#other_title_name_input').prop('disabled', true);
		}
	});
	/* date of birth */
	$('#cls_date_of_birth').click(function() {
		$('#date_of_birth').val("");
		$('#age_year_input').val("0");
		$('#age_month_input').val("0");
		$('#age_day_input').val("0");
	});
	$('#date_of_birth').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* sick date input */
	$('#cls_sick_date').click(function() {
		$('#sick_date').val("");
	});
	$('#sick_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* treat date input */
	$('#cls_treat_date').click(function() {
		$('#treat_date').val("");
	});
	$('#treat_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* admit date input */
	$('#cls_admit_date').click(function() {
		$('#admit_date').val("");
	});
	$('#admit_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* xray date input */
	$('#cls_lung_date').click(function() {
		$('#xRayDate').val("");
	});
	$('#xRayDate').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* xray check */
	$('input.lungXray').on('change', function() {
		$('input.lungXray').not(this).prop('checked', false);
		if ($('#lungXrayYes').prop('checked') == true) {
			$('#xRayDate').prop('disabled', false);
			$('#xRayRs').prop('disabled', false);
		} else {
			$('#xRayDate').prop('disabled', true);
			$('#xRayRs').prop('disabled', true);
		}
	});
	/* cbc date input */
	$('#cls_cbc_date').click(function() {
		$('#cbcDateInput').val("");
	});
	$('#cbcDateInput').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* influ vaccine date input */
	$('#cls_influVaccineDate').click(function() {
		$('#influVaccineDate').val("");
	});
	$('#influVaccineDate').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* medicine date input */
	$('#cls_medicineDate').click(function() {
		$('#medicineDate').val("");
	});
	$('#medicineDate').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	/* calc age year */
	$('#date_of_birth').datepicker().on('changeDate', function(e){
		var
			getDay = new Date(e.date).getDate(),
			getMonth = new Date(e.date).getMonth() + 1,
			getYear = String(e.date).split(" ")[3];
			date = getYear + '-' + getMonth + '-' + getDay,
			birthday = new Date(date),
			today = new Date(),
			ageInMilliseconds = new Date(today - birthday),
			years = ageInMilliseconds / (24 * 60 * 60 * 1000 * 365.25 ),
			months = 12 * (years % 1),
			days = Math.floor(30 * (months % 1));
			$('#age_year_input').val(Math.floor(years));
			$('#age_month_input').val(Math.floor(months));
			$('#age_day_input').val(Math.floor(days));
	});
	/* nationallity */
	$('#select_nationality').change(function() {
		var id = $('#select_nationality').val();
		if (id === '11') {
			$('#other_nationality_input').prop('disabled', false);
		} else {
			$('#other_nationality_input').val('');
			$('#other_nationality_input').prop('disabled', true);
		}
	});
	/* district */
	$('#select_province').change(function() {
		if ($(this).val() != '') {
			var id = $(this).val();
			$.ajax({
				method: "POST",
				url: "{{ route('districtFetch') }}",
				dataType: "HTML",
				data: {id:id},
				success: function(response) {
					$('#select_district').html(response);
					$('#select_district').selectpicker("refresh");
				},
				error: function(jqXhr, textStatus, errorMessage){
					alert('Error code: ' + jqXhr.status + errorMessage);
				}
			});
		}
	});
	/* sub district */
	$('#select_district').change(function() {
		if ($(this).val() != '') {
			var id = $(this).val();
			$.ajax({
				method: "POST",
				url: "{{ route('subDistrictFetch') }}",
				dataType: "HTML",
				data: {id:id},
				success: function(response) {
					$('#select_sub_district').html(response);
					$('#select_sub_district').selectpicker("refresh");
				},
				error: function(jqXhr, textStatus, errorMessage){
					alert('Error code: ' + jqXhr.status + errorMessage);
				}
			});
		}
	});
	/* select occupation */
	$('#select_occupation').change(function() {
		var id = $('#select_occupation').val();
		if (id === '14') {
			$('#occupation_other_input').prop('disabled', false);
		} else {
			$('#occupation_other_input').val('');
			$('#occupation_other_input').prop('disabled', true);
		}
	});
	/* patient type */
	$('.pt-type').click(function() {
		$('.pt-type').not(this).prop('checked', false);
	});
	@php
	/* symptoms */
	$symptoms->each(function ($item, $key) {
		echo "
			var symt_n = $('.symptom-".$item->id."').filter(':checked').length;
			if (symt_n === 1) {
				var hasClass = $('#symptoms_table_tr".$item->id."').hasClass('highlight');
				if (!hasClass) {
					$('#symptoms_table_tr".$item->id."').addClass('highlight');
				}
			}
		\n";
		echo "
			$('.symptom-".$item->id."').click(function() {
				$('.symptom-".$item->id."').not(this).prop('checked', false);
				var number = $('.symptom-".$item->id."').filter(':checked').length;
				var symp = $('.symptom-".$item->id."').filter(':checked').val();
				if (number === 1) {
					var hasClass = $('#symptoms_table_tr".$item->id."').hasClass('highlight');
					if (!hasClass) {
						$('#symptoms_table_tr".$item->id."').addClass('highlight');
					}
				} else {
					$('#symptoms_table_tr".$item->id."').removeClass('highlight');
				}
				if (symp === 'y') {
					$('#symptom_other').prop('disabled', false);
				} else {
					$('#symptom_other').val('');
					$('#symptom_other').prop('disabled', true);
				}
			});
		\n";
	});
	/* specimen table && checkbox */
	foreach ($specimen_data as $key => $val) {
		echo "
			var spec".$val['sp_id']." = $('.specimen-chk-".$val['sp_id']."').filter(':checked').length;
			if (spec".$val['sp_id']." === 1) {
				var hasClass = $('#specimen_tr".$val['sp_id']."').hasClass('highlight');
				if (!hasClass) {
					$('#specimen_tr".$val['sp_id']."').addClass('highlight');
				}
			}
		\n";
		echo "
		$('.specimen-chk-".$val['sp_id']."').click(function() {
			$('.specimen-chk-".$val['sp_id']."').not(this).prop('checked', false);
			let number = $('.specimen-chk-".$val['sp_id']."').filter(':checked').length;
			if (number == 1) {
				let hasClass = $('#specimen_tr".$val['sp_id']."').hasClass('highlight');
				if (!hasClass) {
					$('#specimen_tr".$val['sp_id']."').addClass('highlight');
				}
			} else {
				$('#specimen_tr".$val['sp_id']."').removeClass('highlight');
			}";
			if ($val['sp_other_field'] == 'Yes') {
				echo "
					if ($('#specimen_chk_".$val['sp_id']."').prop('checked') == true) {
						$('#specimen_oth".$val['sp_id']."').prop('disabled', false);
						$('#specimenDate_".$val['sp_id']."').prop('disabled', false);
					} else {
						$('#specimen_oth".$val['sp_id']."').val('');
						$('#specimen_oth".$val['sp_id']."').prop('disabled', true);
						$('#specimenDate_".$val['sp_id']."').val('');
						$('#specimenDate_".$val['sp_id']."').prop('disabled', true);
					}";
			} else {
				echo "
					if ($('#specimen_chk_".$val['sp_id']."').prop('checked') == true) {
						$('#specimenDate_".$val['sp_id']."').prop('disabled', false);
					} else {
						$('#specimenDate_".$val['sp_id']."').val('');
						$('#specimenDate_".$val['sp_id']."').prop('disabled', true);
					}";
			}
		echo "});\n";
		echo "
			$('#cls_specimenDate_".$val['sp_id']."').click(function() {
				$('#specimenDate_".$val['sp_id']."').val('');
			});
			$('#specimenDate_".$val['sp_id']."').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				todayBtn: true,
				autoclose: true
			});";
	}
	@endphp
	/* influ Rapid test */
	$('.influRapid').on('change', function() {
		$('.influRapid').not(this).prop('checked', false);
		$('#influRapidTestName').val('');
		if ($('#influRaidCheckd').prop('checked') == true) {
			$('#influRapidTestName').prop('disabled', false);
		} else {
			$('#influRapidTestName').prop('disabled', true);
		}
	});
	/* influ Rapid test Result => Nagative */
	$('#rapidTestNagative').on('change', function() {
		if ($("#rapidTestNagative").prop("checked")) {
			$("#rapidTestPositiveFluA").prop('checked', false);
			$("#rapidTestPositiveFluB").prop('checked', false);
		}
	});
	/* influ Rapid test Result => FluA */
	$('#rapidTestPositiveFluA').on('change', function() {
		if ($("#rapidTestPositiveFluA").prop("checked")) {
			$("#rapidTestNagative").prop('checked', false);
		}
	});
	/* influ Rapid test Result => FluB */
	$('#rapidTestPositiveFluB').on('change', function() {
		if ($("#rapidTestPositiveFluB").prop("checked")) {
			$("#rapidTestNagative").prop('checked', false);
		}
	});
	/* influ Vaccine */
	$('.influVaccineRc').on('change', function() {
		$('.influVaccineRc').not(this).prop('checked', false);
		$('#influVaccineDate').val('');
		if ($('#influVaccineYes').prop('checked') == true) {
			$('#influVaccineDate').prop('disabled', false);
		} else {
			$('#influVaccineDate').prop('disabled', true);
		}
	});
	/* influ Rapid Result */
	$('input.virusMedic').on('change', function() {
		$('input.virusMedic').not(this).prop('checked', false);
		$('#medicineName').val('');
		$('#medicineDate').val('');
		if ($('#virusMedicineYes').prop('checked') == true) {
			$('#medicineName').prop('disabled', false);
			$('#medicineDate').prop('disabled', false);
		} else {
			$('#medicineName').prop('disabled', true);
			$('#medicineDate').prop('disabled', true);
		}
	});
	/* chekc health tbl hightlight */
	@php
	for ($i=1; $i<=14; $i++) {
		echo "
		var n = $('.health-".$i."').filter(':checked').length;
		if (n === 1) {
			var hasClass = $('#health_table_tr".$i."').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr".$i."').addClass('highlight');
			}
		}\n";
	}
	@endphp
	/* health checkbox */
	$('.health-1').click(function() {
		$('.health-1').not(this).prop('checked', false);
		let number = $('.health-1').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr1').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr1').addClass('highlight');
			}
		} else {
			$('#health_table_tr1').removeClass('highlight');
		}
		if ($('#pregnant_age_y').prop('checked') == true) {
			$('#pregnant_age_week').prop('disabled', false);
		} else {
			$('#pregnant_age_week').val('');
			$('#pregnant_age_week').prop('disabled', true);
		}
	});
	$('.health-2').click(function() {
		$('.health-2').not(this).prop('checked', false);
		let number = $('.health-2').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr2').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr2').addClass('highlight');
			}
		} else {
			$('#health_table_tr2').removeClass('highlight');
		}
	});
	$('.health-3').click(function() {
		$('.health-3').not(this).prop('checked', false);
		let number = $('.health-3').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr3').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr3').addClass('highlight');
			}
		} else {
			$('#health_table_tr3').removeClass('highlight');
		}
		if ($('#fat-input-y').prop('checked') == true) {
			$('#fat-height-input').prop('disabled', false);
			$('#fat-weight-input').prop('disabled', false);
		} else {
			$('#fat-height-input').val('');
			$('#fat-weight-input').val('');
			$('#fat-height-input').prop('disabled', true);
			$('#fat-weight-input').prop('disabled', true);
		}
	});
	$('.health-4').click(function() {
		$('.health-4').not(this).prop('checked', false);
		let number = $('.health-4').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr4').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr4').addClass('highlight');
			}
		} else {
			$('#health_table_tr4').removeClass('highlight');
		}
	});
	$('.health-5').click(function() {
		$('.health-5').not(this).prop('checked', false);
		let number = $('.health-5').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr5').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr5').addClass('highlight');
			}
		} else {
			$('#health_table_tr5').removeClass('highlight');
		}
		if ($('#immune-y').prop('checked') == true) {
			$('#immune_specify').prop('disabled', false);
		} else {
			$('#immune_specify').val('');
			$('#immune_specify').prop('disabled', true);
		}
	});
	$('.health-6').click(function() {
		$('.health-6').not(this).prop('checked', false);
		let number = $('.health-6').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr6').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr6').addClass('highlight');
			}
		} else {
			$('#health_table_tr6').removeClass('highlight');
		}
		if ($('#preterm-infant-y').prop('checked') == true) {
			$('#preterm-infant-week').prop('disabled', false);
		} else {
			$('#preterm-infant-week').val('');
			$('#preterm-infant-week').prop('disabled', true);
		}
	});
	$('.health-7').click(function() {
		$('.health-7').not(this).prop('checked', false);
		let number = $('.health-7').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr7').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr7').addClass('highlight');
			}
		} else {
			$('#health_table_tr7').removeClass('highlight');
		}
	});
	$('.health-8').click(function() {
		$('.health-8').not(this).prop('checked', false);
		let number = $('.health-8').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr8').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr8').addClass('highlight');
			}
		} else {
			$('#health_table_tr8').removeClass('highlight');
		}
	});
	$('.health-9').click(function() {
		$('.health-9').not(this).prop('checked', false);
		let number = $('.health-9').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr9').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr9').addClass('highlight');
			}
		} else {
			$('#health_table_tr9').removeClass('highlight');
		}
	});
	$('.health-10').click(function() {
		$('.health-10').not(this).prop('checked', false);
		let number = $('.health-10').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr10').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr10').addClass('highlight');
			}
		} else {
			$('#health_table_tr10').removeClass('highlight');
		}
	});
	$('.health-11').click(function() {
		$('.health-11').not(this).prop('checked', false);
		let number = $('.health-11').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr11').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr11').addClass('highlight');
			}
		} else {
			$('#health_table_tr11').removeClass('highlight');
		}
	});
	$('.health-12').click(function() {
		$('.health-12').not(this).prop('checked', false);
		let number = $('.health-12').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr12').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr12').addClass('highlight');
			}
		} else {
			$('#health_table_tr12').removeClass('highlight');
		}
	});
	$('.health-13').click(function() {
		$('.health-13').not(this).prop('checked', false);
		let number = $('.health-13').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr13').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr13').addClass('highlight');
			}
		} else {
			$('#health_table_tr13').removeClass('highlight');
		}
		if ($('#cancer-y').prop('checked') == true) {
			$('#cancer-input').prop('disabled', false);
		} else {
			$('#cancer-input').val('');
			$('#cancer-input').prop('disabled', true);
		}
	});
	$('.health-14').click(function() {
		$('.health-14').not(this).prop('checked', false);
		let number = $('.health-14').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#health_table_tr14').hasClass('highlight');
			if (!hasClass) {
				$('#health_table_tr14').addClass('highlight');
			}
		} else {
			$('#health_table_tr14').removeClass('highlight');
		}
		if ($('#other-disease-y').prop('checked') == true) {
			$('#other-disease-input').prop('disabled', false);
		} else {
			$('#other-disease-input').val('');
			$('#other-disease-input').prop('disabled', true);
		}
	});
	/* chekc risk-history tbl hightlight */
	@php
	for ($i=1; $i<=9; $i++) {
		echo "
		var n = $('.risk-".$i."').filter(':checked').length;
		if (n === 1) {
			var hasClass = $('#risk_table_tr".$i."').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr".$i."').addClass('highlight');
			}
		}\n";
	}
	@endphp
	/* risk-history */
	$('.risk-1').click(function() {
		$('.risk-1').not(this).prop('checked', false);
		let number = $('.risk-1').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr1').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr1').addClass('highlight');
			}
		} else {
			$('#risk_table_tr1').removeClass('highlight');
		}
	});
	$('.risk-2').click(function() {
		$('.risk-2').not(this).prop('checked', false);
		let number = $('.risk-2').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr2').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr2').addClass('highlight');
			}
		} else {
			$('#risk_table_tr2').removeClass('highlight');
		}
		if ($('#pet_touch_direct_y').prop('checked') == true) {
			$('#pet_touch_name').prop('disabled', false);
		} else {
			$('#pet_touch_name').val('');
			$('#pet_touch_name').prop('disabled', true);
		}
	});
	$('.risk-3').click(function() {
		$('.risk-3').not(this).prop('checked', false);
		let number = $('.risk-3').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr3').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr3').addClass('highlight');
			}
		} else {
			$('#risk_table_tr3').removeClass('highlight');
		}
	});
	$('.risk-4').click(function() {
		$('.risk-4').not(this).prop('checked', false);
		let number = $('.risk-4').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr4').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr4').addClass('highlight');
			}
		} else {
			$('#risk_table_tr4').removeClass('highlight');
		}
		if ($('#stay_outbreak_y').prop('checked') == true) {
			$('#stay_outbreak_input').prop('disabled', false);
		} else {
			$('#stay_outbreak_input').val('');
			$('#stay_outbreak_input').prop('disabled', true);
		}
	});
	$('.risk-5').click(function() {
		$('.risk-5').not(this).prop('checked', false);
		let number = $('.risk-5').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr5').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr5').addClass('highlight');
			}
		} else {
			$('#risk_table_tr5').removeClass('highlight');
		}
	});
	$('.risk-6').click(function() {
		$('.risk-6').not(this).prop('checked', false);
		let number = $('.risk-6').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr6').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr6').addClass('highlight');
			}
		} else {
			$('#risk_table_tr6').removeClass('highlight');
		}
	});
	$('.risk-7').click(function() {
		$('.risk-7').not(this).prop('checked', false);
		let number = $('.risk-7').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr7').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr7').addClass('highlight');
			}
		} else {
			$('#risk_table_tr7').removeClass('highlight');
		}
	});
	$('.risk-8').click(function() {
		$('.risk-8').not(this).prop('checked', false);
		let number = $('.risk-8').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr8').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr8').addClass('highlight');
			}
		} else {
			$('#risk_table_tr8').removeClass('highlight');
		}
	});
	$('.risk-9').click(function() {
		$('.risk-9').not(this).prop('checked', false);
		let number = $('.risk-9').filter(':checked').length;
		if (number == 1) {
			let hasClass = $('#risk_table_tr9').hasClass('highlight');
			if (!hasClass) {
				$('#risk_table_tr9').addClass('highlight');
			}
		} else {
			$('#risk_table_tr9').removeClass('highlight');
		}
		if ($('#other_risk_y').prop('checked') == true) {
			$('#other_risk_input').prop('disabled', false);
		} else {
			$('#other_risk_input').val('');
			$('#other_risk_input').prop('disabled', true);
		}
	});
	/* treatment */
	$('.treatment-1').click(function() {
		$('.treatment-1').not(this).prop('checked', false);
		if ($('#treatment_refer').prop('checked') == true) {
			$('#treatment_refer_at').prop('disabled', false);
		} else {
			$('#treatment_refer_at').val('');
			$('#treatment_refer_at').prop('disabled', true);
		}
		if ($('#treatment_other').prop('checked') == true) {
			$('#treatment_other_txt').prop('disabled', false);
		} else {
			$('#treatment_other_txt').val('');
			$('#treatment_other_txt').prop('disabled', true);
		}
	});

	/* report date input */
	$('#report_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		todayBtn: true,
		autoclose: true
	});
	$('#report_date').datepicker('update', new Date());
});
</script>
<script>
function alertMessage(status, message, title) {
	$status = parseInt(status);
	if (status == 200) {
		toastr.success(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '600'
			}
		);
	} else if (status == 204) {
		toastr.warning(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '800'
			}
		);
	} else {
		toastr.error(message, title,
			{
				'closeButton': true,
				'positionClass': 'toast-top-center',
				'progressBar': true,
				'showDuration': '800'
			}
		);
	}
}
</script>
@endsection
