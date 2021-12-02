@extends('layouts.index')
@section('topScript')
@endsection
@section('contents')
<?php //unset($type_specimen); ?>
<div class="page-breadcrumb" style="padding-bottom:5px;background-color:#ffffff;">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Lab</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Sample Submission</li>
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
					<div class="d-md-flex align-items-center">
						<div>
							<h4 class="card-title">แบบส่งตัวอย่างโครงการเฝ้าระวังเชื้อไวรัสก่อโรคระบบทางเดินหายใจ</h4>
							<h5 class="card-subtitle">ID Flu-BOE</h5>
						</div>
					</div>
					<Form action="#" method="POST" class="needs-validation custom-form-legend" novalidate>
            {{ csrf_field() }}
						<div class="bd-callout bd-callout-custom-3">
							<h1>1. ชื่อและที่อยู่ของผู้นำส่งตัวอย่าง</h1>
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
									<label for="firstNameInput">ชื่อ</label>
									<input type="text" name="name_healthwork" class="form-control" id="name_healthworker" placeholder="ชื่อ-นามสกุล" required>
									<div class="valid-feedback">Looks good!</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
									<label for="lastNameInput">โรงพยาบาล</label>
									<input type="text" name="hoscode_healthwork" class="form-control" id="hoscode_healthwork" placeholder="ชื่อโรงพยาบาล" required>
									<div class="valid-feedback">Looks good!</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-2 mb-3">
									<label for="hnInput">จังหวัด</label>
									<input type="text" name="hnInput" class="form-control" id="hn_input" placeholder="HN" required>
									<div class="valid-feedback">Looks good!</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-2 mb-3">
									<label for="anInput">โทรศัพท์</label>
									<input type="text" name="anInput" class="form-control" id="an_input" placeholder="AN">
									<div class="valid-feedback">Looks good!</div>
								</div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-2 mb-3">
									<label for="anInput">โทรสาร</label>
									<input type="text" name="anInput" class="form-control" id="an_input" placeholder="AN">
									<div class="valid-feedback">Looks good!</div>
								</div>
							</div>
						</div>
						<div class="bd-callout bd-callout-info">
							<h1>2. ข้อมูลผู้ป่วย</h1>
              <div class="form-row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
									<label for="title_name">คำนำหน้าชื่อ</label>
									<select name="title_name" class="custom-select">
										<option value="">-- โปรดเลือก --</option>
                    @foreach ($ref_title_name as $title_name)
										<option value="{{ $title_name->id_title_name }}">{{ $title_name->title_name }}</option>
                    @endforeach
									</select>
								</div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
									<label for="sexInput">เพศ</label>
									<select name="sexInput" class="custom-select">
										<option value="null">-- โปรดเลือก --</option>
										<option value="male">ชาย</option>
										<option value="female">หญิง</option>
									</select>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
									<label for="firstNameInput">ชื่อ</label>
									<input type="text" name="firstNameInput" class="form-control" id="first_name_input" placeholder="First name" required>
									<div class="valid-feedback">Looks good!</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
									<label for="lastNameInput">นามสกุล</label>
									<input type="text" name="lastNameInput" class="form-control" id="last_name_input" placeholder="Last name" required>
									<div class="valid-feedback">Looks good!</div>
								</div>
							</div>
								<div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="DateOfBirth">วันที่เกิด</label>
										<div class="input-group date" data-provide="datepicke" id="DateOfBirth">
											<div class="input-group">
												<input type="text" name="DateOfBirth" class="form-control" required>
												<div class="input-group-append">
													<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="sickDateInput">วันที่เริ่มป่วย</label>
										<div class="input-group date" data-provide="datepicke" id="sickDate">
											<div class="input-group">
												<input type="text" name="sickDateInput" class="form-control" required>
												<div class="input-group-append">
													<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="treatDateInput">วันที่รักษาครั้งแรก</label>
										<div class="input-group date" data-provide="datepicke" id="treatDate">
											<div class="input-group">
												<input type="text" name="treatDateInput" class="form-control" required>
												<div class="input-group-append">
													<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
								</div>
                <div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="houseNoInput">ที่อยู่ปัจจุบัน/ขณะป่วย เลขที่</label>
										<input type="text" name="houseNoInput" class="form-control" placeholder="บ้านเลขที่">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-1 col-xl-1 mb-3">
										<label for="villageNoInput">หมู่ที่</label>
										<input type="text" name="villageNoInput" class="form-control" placeholder="หมู่ที่">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<label for="villageInput">หมู่บ้าน</label>
										<input type="text" name="villageInput" class="form-control" placeholder="หมู่บ้าน">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
										<label for="laneInput">ซอย</label>
										<input type="text" name="laneInput" class="form-control" placeholder="ซอย">
									</div>
								</div>
                <div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="subDistrictInput">ตำบล</label>
										<select name="subDistrictInput" class="custom-select">
											<option value="null">-- โปรดเลือก --</option>
										</select>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="districtInput">อำเภอ</label>
										<select name="districtInput" class="custom-select">
											<option value="null">-- โปรดเลือก --</option>
										</select>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="provinceInput">จังหวัด</label>
										<select name="provinceInput" class="custom-select">
											<option value="null">-- โปรดเลือก --</option>
										</select>
									</div>
								</div>

								<div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-12 mb-3">
										<label for="sickDateInput">อาการและอาการแสดง</label>
										<div class="table-responsive">
											<table class="table" id="symptoms_table">
												<thead>
													<tr>
														<th scope="col">อาการ</th>
														<th scope="col">มี</th>
														<th scope="col">ไม่มี</th>
														<th scope="col">ไม่ทราบ</th>
													</tr>
												</thead>
												<tbody>
													@php
														$symptoms->each(function ($item, $key) {
															if ($item->id == 19) {
																$other_symptom = "<input type=\"text\" name=\"symptom_other\" class=\"form-control\" id=\"symptom_other\" disabled>";
															} else {
																$other_symptom = null;
															}
															echo "<tr id=\"symptoms_table_tr".$item->id."\">";
																echo "<td>".$item->symptom_name_th."&nbsp;&#40;".$item->symptom_name_en."&#41;".$other_symptom."</td>";
																echo "<td>";
																	echo "<div class=\"custom-control custom-checkbox\">";
																		echo "<input type=\"checkbox\" name=\"symptom_".$item->id."_Input\" value=\"N\" class=\"custom-control-input symptom-".$item->id."\" id=\"symptom_".$item->id."_yes\">";
																		echo "<label for=\"symptom_".$item->id."_yes\" class=\"custom-control-label\">&nbsp;</label>";
																	echo "</div>";
																echo "</td>";
																echo "<td>";
																	echo "<div class=\"custom-control custom-checkbox\">";
																		echo "<input type=\"checkbox\" name=\"symptom_".$item->id."_Input\" value=\"N\" class=\"custom-control-input symptom-".$item->id."\" id=\"symptom_".$item->id."_no\">";
																		echo "<label for=\"symptom_".$item->id."_no\" class=\"custom-control-label\"></label>";
																	echo "</div>";
																echo "</td>";
																echo "<td>";
																	echo "<div class=\"custom-control custom-checkbox\">";
																		echo "<input type=\"checkbox\" name=\"symptom_".$item->id."_Input\" value=\"N\" class=\"custom-control-input symptom-".$item->id."\" id=\"symptom_".$item->id."_unknown\">";
																		echo "<label for=\"symptom_".$item->id."_unknown\" class=\"custom-control-label\"></label>";
																	echo "</div>";
																echo "</td>";
															echo "</tr>\n";
														})
													@endphp
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
										<label for="influenzaRapidInput">มีการตรวจ Influenza rapid test</label>
										<div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="influenzaRapidCheckbox" class="custom-control-input" id="influRapidCheckboxNo">
												<label for="influRapidCheckboxNo" class="custom-control-label normal-label">ไม่ตรวจ</label>
											</div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="influenzaRapidCheckbox" class="custom-control-input" id="influRapidCheckboxYes">
												<label for="influRapidCheckboxYes" class="custom-control-label normal-label">ตรวจ</label>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
										<label for="rapidTestResult">ผล Rapid test</label>
										<div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="rapidTestResultCheckbox" class="custom-control-input" id="rapidTestNagative">
												<label for="rapidTestNagative" class="custom-control-label normal-label">Nagative</label>
											</div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="rapidTestResultCheckbox" class="custom-control-input" id="rapidTestPositiveFluA">
												<label for="rapidTestPositiveFluA" class="custom-control-label normal-label">Positive Flu A</label>
											</div>
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="rapidTestResultCheckbox" class="custom-control-input" id="rapidTestPositiveFluB">
												<label for="rapidTestPositiveFluB" class="custom-control-label normal-label">Positive Flu B</label>
											</div>
										</div>
									</div>
								</div>
                <div class="form-row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
										<label for="firstDiagnosisInput">การวินิจฉัยของแพทย์</label>
										<input type="text" name="firstDiagnosisInput" class="form-control" placeholder="การวินิจฉัยเบื้องต้น">
									</div>
								</div>
							</div>
              <div class="bd-callout bd-callout-custom-5">
								<h1>3. ตัวอย่างส่งตรวจเพื่อหาสารพันธุกรรมหรือแยกเชื้อ</h1>

								<div class="form-row after-add-more" >
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
                    <label for="ref_specimen">ชื่อตัวอย่าง</label>
										<select name="ref_specimen" class="custom-select" id="ref_specimen">
											<option value="">-- โปรดเลือก --</option>
                      @foreach ($ref_specimen as $type_specimen)
                      <option value="{{ $type_specimen->id_specimen }}">{{ $type_specimen->specimen_name }}</option>
                      @endforeach
										</select>
  								</div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3 other_specimen_hs">
										<label for="other_specimen">ระบุชื่อตัวอย่าง</label>
										<input type="text" name="other_specimen" class="form-control" id="other_specimen" placeholder="ระบุชื่อตัวอย่าง">
									</div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
										<label for="specimenDateInput">วันที่เริ่มป่วย</label>
										<div class="input-group date" data-provide="datepicke" id="sickDate">
											<div class="input-group">
												<input type="text" name="specimenDateInput" class="specimenDateInput" class="form-control" required>
												<div class="input-group-append">
													<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
                    <label for="other_specimen" style="color:#FFFFFF;">ระบุชื่อตัวอย่าง</label>
                    <div class="input-group">
                        <button class="btn btn-success add-more" type="button"><i class="fa fa-plus w-30px m-t-5"></i> Add</button>
                    </div>
                  </div>
								</div>

                <!-- Copy -->
                <div class="copy hide">
                  <div class="form-row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
                      <label for="ref_specimen">ชื่อตัวอย่าง</label>
  										<select name="ref_specimen" class="custom-select" id="ref_specimen">
  											<option value="">-- โปรดเลือก --</option>
                        @foreach ($ref_specimen as $type_specimen)
                        <option value="{{ $type_specimen->id_specimen }}">{{ $type_specimen->specimen_name }}</option>
                        @endforeach
  										</select>
    								</div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3 other_specimen_hs">
  										<label for="other_specimen">ระบุชื่อตัวอย่าง</label>
  										<input type="text" name="other_specimen" class="form-control" id="other_specimen" placeholder="ระบุชื่อตัวอย่าง">
  									</div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
  										<label for="specimenDateInput">วันที่เริ่มป่วย</label>
  										<div class="input-group date" data-provide="datepicke" id="sickDate">
  											<div class="input-group">
  												<input type="text" name="specimenDateInput" class="specimenDateInput" class="form-control" required>
  												<div class="input-group-append">
  													<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
  												</div>
  											</div>
  										</div>
  									</div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-3">
                      <label for="other_specimen" style="color:#FFFFFF;">ระบุชื่อตัวอย่าง</label>
                      <div class="input-group">
                          <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                      </div>
                    </div>
  								</div>
                </div>
                <!-- End Copy -->
							</div>
						<button class="btn btn-primary mt-3" type="submit">Submit form</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function() {
	@php
	$symptoms->each(function ($item, $key) {
		echo "
			$('.symptom-".$item->id."').click(function() {
				$('.symptom-".$item->id."').not(this).prop('checked', false);
				let number = $('.symptom-".$item->id."').filter(':checked').length;
				if (number === 1) {
					let hasClass = $('#symptoms_table_tr".$item->id."').hasClass('highlight');
					if (!hasClass) {
						$('#symptoms_table_tr".$item->id."').addClass('highlight');
					}
					if (".$item->id." == 19) {
						$('#symptom_other').prop('disabled', false);
					}
				} else {
					$('#symptoms_table_tr".$item->id."').removeClass('highlight');
					if (".$item->id." == 19) {
						$('#symptom_other').prop('disabled', true);
					}
				}
			});
		\n";
	});
	@endphp

	$('#xx').click(function(){alert('ok เลย')});

  $(".other_specimen_hs").hide();
  $("#ref_specimen").change(function(){
    var last_option_ref_specimen = $( "#ref_specimen option:last-child" ).val();
    var selected_option_ref_specimen = $( "#ref_specimen option:selected" ).val();
    if(selected_option_ref_specimen === last_option_ref_specimen){
      $(".other_specimen_hs").show();
    }else{
      $(".other_specimen_hs").hide();
      $("#other_specimen").val('');
    }
  });

  $(".add-more").click(function(){
          var html = $(".copy").html();
          $(".after-add-more").after(html);
      });


      $("body").on("click",".remove",function(){
          $(this).parents(".form-row").remove();
      });


});
</script>
<script>
$('#DateOfBirth,#sickDate,#treatDate,.specimenDateInput').datepicker({
	format: 'dd/mm/yyyy',
	todayHighlight: true,
	todayBtn: true
});

</script>
@endsection
