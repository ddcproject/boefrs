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
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2 mb-3">
											<label for="patientName">ชื่อ-สกุล</label>
											<input type="hidden" name="pid" value="{{ $data['patient_id'] }}">
											<input type="hidden" name="lab_code" value="{{ $data['patient_lab_code'] }}">
											<input type="text" name="pNameInput" class="form-control" value="{{ $data['patient_fullname'] }}" placeholder="ชื่อ-สกุล" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2 mb-3">
											<label for="patientGender">เพศ</label>
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="cerebralInput" value="male" @if ($data['patient_gender'] == 'male') checked @endif class="custom-control-input form-check-input" id="male_input" disabled>
													<label for="male_input" class="custom-control-label">&nbsp;ชาย</label>
												</div>
												<div class="form-check form-check-inline">
													<input type="checkbox" name="cerebralInput" value="female" @if ($data['patient_gender'] == 'female') checked @endif class="custom-control-input form-check-input" id="male_input" disabled>
													<label for="female_input" class="custom-control-label">&nbsp;หญิง</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2 mb-3">
											<label for="HN">HN</label>
											<input type="text" name="hnInput" value="{{ $data['patient_hn'] }}" class="form-control" placeholder="HN" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2 mb-3">
											<label for="age">อายุ (ปี-เดือน-วัน)</label>
											<input type="text" name="ageInput"  value="{{ $data['patient_age'] }}" class="form-control" placeholder="อายุ" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
											<div class="form-group">
												<label for="houseNo">ที่อยู่ปัจจุบัน/ขณะป่วย เลขที่</label>
												<input type="text" name="houseNoInput" value="{{ $data['patient_house_no'] }}" class="form-control" placeholder="บ้านเลขที่" readonly>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 mb-3">
											<div class="form-group">
												<label for="villageNo">หมู่ที่</label>
												<input type="text" name="villageNoInput" value="{{ $data['patient_village_no'] }}" class="form-control" placeholder="หมู่ที่" readonly>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="village">หมู่บ้าน</label>
											<input type="text" name="villageInput" value="{{ $data['patient_village'] }}" class="form-control" placeholder="หมู่บ้าน" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-4 mb-3">
											<div class="form-group">
												<label for="lane">ซอย</label>
												<input type="text" name="laneInput" value="{{ $data['patient_lane'] }}" class="form-control" placeholder="ซอย" readonly>
											</div>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="patientProvince">จังหวัด</label>
											<input type="text" name="pProvince" value="{{ $data['patient_province'] }}" class="form-control" placeholder="จังหวัด" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="patientDistrict">อำเภอ</label>
											<input type="text" name="pDistrict" value="{{ $data['patient_district'] }}" class="form-control" placeholder="อำเภอ" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="patientSubDistrict">ตำบล</label>
											<input type="text" name="pSubDistrict" value="{{ $data['patient_sub_district'] }}" class="form-control" placeholder="ตำบล" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="sickDateInput">วันที่เริ่มป่วย</label>
												<div class="input-group date" data-provide="datepicke" id="sickDateInput">
													<div class="input-group">
														<input type="text" name="sickDateInput" value="{{ $data['patient_sickDate'] }}" class="form-control" readonly>
														<div class="input-group-append">
															<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="hospital">โรงพยาบาล</label>
												<input type="text" name="hospitalNoInput" value="{{ $data['patient_hospital'] }}" class="form-control" placeholder="โรงพยาบาล" readonly>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<div class="form-group">
												<label for="dateDefine">วันที่เข้ารับการรักษา</label>
												<div class="input-group date" data-provide="datepicke" id="dateDefineInput">
													<div class="input-group">
														<input type="text" name="dateDefineInput" value="{{ $data['patient_dateDefine'] }}" class="form-control" readonly>
														<div class="input-group-append">
															<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bd-callout bd-callout-info" style="margin-top:0;position:relative">
									<h1 class="text-info">2. อาการ</h1>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
											<div class="form-group">
												<label for="temperature">อุณหภูมิร่างกาย</label>
												<div class="input-group">
													<input type="text" name="temperatureInput" value="{{ $data['patient_temperature'] }}" class="form-control" readonly>
													<div class="input-group-append">
														<span class="input-group-text">C&#176;</span>
													</div>
												</div>
											</div>
										</div>

										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
											<div class="form-group">
												<label for="feverDay">จำนวนวันที่เป็นไข้</label>
												<div class="input-group">
													<input type="number" name="feverDayInput" value="{{ $data['patient_fever_day'] == '' ? '' : $data['patient_fever_day'] }}" min="0" max="90" class="form-control" readonly>
													<div class="input-group-append">
														<span class="input-group-text">วัน</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row pt-3 pb-3">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="fever_sym" @if ($data['patient_fever_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="fever" disabled>
													<label for="fever" class="custom-control-label">&nbsp;ไข้</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="cough_sym" @if ($data['patient_cough_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="cough" disabled>
													<label for="cough" class="custom-control-label">&nbsp;ไอ (Cough)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="sore_throat_sym" @if ($data['patient_sore_throat_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="sore_throat" disabled>
													<label for="sore_throat" class="custom-control-label">&nbsp;เจ็บคอ (Sore throat)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="runny_stuffy_sym" @if ($data['patient_runny_stuffy_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="runny_stuffy" disabled>
													<label for="runny_stuffy" class="custom-control-label">&nbsp;มีน้ำมูก/คัดจมูก (Runny or stuffy nose)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="sputum_sym" @if ($data['patient_sputum_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="sputum" disabled>
													<label for="sputum" class="custom-control-label">&nbsp;มีเสมหะ (Sputum)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="headache_sym" @if ($data['patient_headache_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="headache" disabled>
													<label for="headache" class="custom-control-label">&nbsp;ปวดศรีษะ (Headache)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="myalgia_sym" @if ($data['patient_myalgia_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="myalgia" disabled>
													<label for="myalgia" class="custom-control-label">&nbsp;ปวดเมื่อยกล้ามเนื้อ (Myalgia)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="fatigue_sym" @if ($data['patient_fatigue_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="fatigue" disabled>
													<label for="fatigue" class="custom-control-label">&nbsp;อ่อนเพลีย (Fatigue)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="dyspnea_sym" @if ($data['patient_dyspnea_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="dyspnea" disabled>
													<label for="dyspnea" class="custom-control-label">&nbsp;หอบเหนื่อย (Dyspnea)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="tachypnea_sym" @if ($data['patient_tachypnea_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="tachypnea" disabled>
													<label for="tachypnea" class="custom-control-label">&nbsp;หายใจเร็ว (Tachpnea)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="wheezing_sym" @if ($data['patient_wheezing_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="wheezing" disabled>
													<label for="wheezing" class="custom-control-label">&nbsp;หายใจมีเสียงวี๊ด (Weezing)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="conjunctivitis_sym" @if ($data['patient_conjunctivitis_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="conjunctivitis" disabled>
													<label for="conjunctivitis" class="custom-control-label">&nbsp;เยื่อบุตาอักเสบ/ตาแดง (Conjunctivitis)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="vomiting_sym" @if ($data['patient_vomiting_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="vomiting" disabled>
													<label for="vomiting" class="custom-control-label">&nbsp;อาเจียน (Vomiting)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="diarrhea_sym" @if ($data['patient_diarrhea_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="diarrhea" disabled>
													<label for="diarrhea" class="custom-control-label">&nbsp;ท้องเสีย (Diarrhea)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="apnea_sym" @if ($data['patient_apnea_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="apnea" disabled>
													<label for="apnea" class="custom-control-label">&nbsp;Apnea (เด็ก อายุ 0-6 เดือน)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="sepsis_sym" @if ($data['patient_sepsis_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="sepsis" disabled>
													<label for="sepsis" class="custom-control-label">&nbsp;Sepsis</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="encephalitis_sym" @if ($data['patient_encephalitis_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="encephalitis" disabled>
													<label for="encephalitis" class="custom-control-label">&nbsp;สมองอักเสบ (Encephalitis)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="intubation_sym" @if ($data['patient_intubation_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="intubation" disabled>
													<label for="intubation" class="custom-control-label">&nbsp;ใส่ท่อช่วยหายใจ (Intubation)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="pneumonia_sym" @if ($data['patient_pneumonia_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="pneumonia" disabled>
													<label for="pneumonia" class="custom-control-label">&nbsp;ปอดบวม/ปอดอักเสบ (Pneumonia)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="kidney_sym" @if ($data['patient_kidney_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="kidney" disabled>
													<label for="kidney" class="custom-control-label">&nbsp;ไตวาย (Kidney)</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-3">
											<div class="custom-control custom-checkbox">
												<div class="form-check form-check-inline">
													<input type="checkbox" name="other_sym" @if ($data['patient_other_sym'] == 'y') checked @endif class="custom-control-input form-check-input" id="other_sym_chk" disabled>
													<label for="other_sym_chk" class="custom-control-label">&nbsp;อื่นๆ โปรดระบุ</label>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
											<input type="text" name="other_sym_text" value="{{ $data['patient_other_sym_text'] }}" class="form-control" id="other_sym_text" placeholder="อาการอื่นๆ ระบุ" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
											<label for="rapidTestResult">ผลการตรวจ Rapid test (ถ้ามีการตรวจ)</label>
											<div>
												<div class="custom-control custom-checkbox custom-control-inline">
													<input type="checkbox" name="rapidTestResultInput" value="nagative" @if ($data['patient_rapid_nagative'] == 'nagative') checked @endif class="custom-control-input influRapidRs" id="rapidTestNagative" disabled>
													<label for="rapidTestNagative" class="custom-control-label normal-label">Nagative</label>
												</div>
												<div class="custom-control custom-checkbox custom-control-inline">
													<input type="checkbox" name="rapidTestResultInput" value="positive-flu-a" @if ($data['patient_rapid_flu_a'] == 'positive-flu-a') checked @endif class="custom-control-input influRapidRs" id="rapidTestPositiveFluA" disabled>
													<label for="rapidTestPositiveFluA" class="custom-control-label normal-label">Positive Flu A</label>
												</div>
												<div class="custom-control custom-checkbox custom-control-inline">
													<input type="checkbox" name="rapidTestResultInput" value="positive-flu-b" @if ($data['patient_rapid_flu_b'] == 'positive-flu-b') checked @endif class="custom-control-input influRapidRs" id="rapidTestPositiveFluB" disabled>
													<label for="rapidTestPositiveFluB" class="custom-control-label normal-label">Positive Flu B</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
											<label for="firstDiagnosis">การวินิจฉัยของแพทย์</label>
											<input type="text" name="firstDiagnosisInput" value="{{ $data['patient_first_diag'] }}" class="form-control" placeholder="การวินิจฉัยเบื้องต้น" readonly>
										</div>
									</div>
								</div>
								<div class="bd-callout bd-callout-custom-6" style="margin-top:0;position:relative">
									<h1 class="text-color-custom-2">3. ตัวอย่างส่งตรวจเพื่อหาสารพันธุกรรมหรือแยกเชื้อ</h1>
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
																<div class="form-group row">
																	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
																		<div class="input-group">
																			<div class="input-group">
																				<input type="text" name="specimenResult{{ $val['rs_id'] }}" value="" class="form-control" id="specimenResult_{{ $val['rs_id'] }}">
																			</div>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
													@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div><!-- bd-collout-3 -->
								<div class="bd-callout bd-callout-info" style="margin-top:0;position:relative">
									<h1 class="text-info">4. ชื่อและที่อยู่ของผู้นำส่งตัวอย่าง</h1>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="userNameInput">ชื่อ-สกุล</label>
											<input type="text" name="uFullNameInput" class="form-control" value="{{ $data['user_fullname'] }}" placeholder="ชื่อ-สกุล" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="userOffice">โรงพยาบาล/หน่วยงาน</label>
											<input type="text" name="uOffice" class="form-control" value="{{ $data['user_office'] }}" placeholder="หน่วยงาน" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="userProvince">จังหวัด</label>
											<input type="text" name="uProvince" value="{{ $data['user_province'] }}" class="form-control" placeholder="จังหวัด" readonly>

										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="phoneInput">โทรศัพท์</label>
											<input type="text" name="phoneInput" value="{{ $data['user_phone'] }}" class="form-control" placeholder="โทรศัพท์" readonly>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
											<label for="faxInput">โทรสาร</label>
											<input type="text" name="faxInput" value="{{ $data['user_fax'] }}" class="form-control" placeholder="โทรสาร" readonly>
										</div>
									</div>
								</div><!-- bd-collout 4 -->
							</div><!-- card-body -->
						</div><!-- card -->
						<div class="border-top">
							<div class="card-body">
								<a href="{{ route('lab.index') }}" type="button" class="btn btn-primary">ปิดหน้านี้</a>
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
