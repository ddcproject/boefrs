<div class="card">
	<div class="card-body">
		<h1 class="text-info">1. ข้อมูลทั่วไปของผู้ป่วย</h1>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="formCode" class="text-info">1.1 รหัสแบบฟอร์ม</label>
				<div class="input-group-append">
					<span class="btn btn-info btn-lg frmCode" data-toggle="tooltip" data-placement="top" title="โปรดเขียนรหัสนี้ลงบนแบบฟอร์ม">{{ $patient[0]->lab_code }}</span>
					<input type="hidden" name="pid" value="{{ $patient[0]->id }}">
					<input type="hidden" name="formIndexInput" value="{{ $patient[0]->lab_code }}">
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group {{ $errors->has('titleNameInput') ? 'has-error' : '' }}">
					<label for="titleName" class="text-info">1.2 คำนำหน้าชื่อ</label>
					<input type="hidden" name="title_name_cache" value="{{ $patient[0]->title_name }}">
					<select name="titleNameInput" class="form-control selectpicker show-tick select-title-name" id="title_name_input">
						@if ((!empty(old('titleNameInput')) || !is_null($patient[0]->title_name)) && !empty($patient[0]->title_name) && $patient[0]->title_name != '0')
							<option value="{{ old('titleNameInput') ?? $patient[0]->title_name }}" selected="selected">{{ $titleName[old('titleNameInput')]->title_name ?? $titleName[$patient[0]->title_name]->title_name }}</option>
						@endif
						<option value="0">-- โปรดเลือก --</option>
						@php
						$titleName->each(function ($item, $key) {
							echo "<option value=\"".$item->id."\">".$item->title_name."</option>";
						});
						@endphp
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-9 col-xl-9 mb-3">
				<div class="form-group">
					<label for="otherTitleName" class="text-info">1.3 คำนำหน้าชื่ออื่นๆ ระบุ</label>
					@php
					if ($patient[0]->title_name == 6) {
						$title_name_oth_txt = $patient[0]->title_name_other;
						$disbled = null;
					} else {
						$title_name_oth_txt = null;
						$disbled = "disabled";
					}
					@endphp
					<input type="text" name="otherTitleNameInput" value="{{ old('otherTitleNameInput') ?? $title_name_oth_txt }}" class="form-control" id="other_title_name_input" placeholder="คำนำหน้าชื่ออื่นๆ" {{ $disbled }}>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="firstName" class="text-info">1.4 ชื่อจริง</label>
					<input type="text" name="firstNameInput" value="{{ old('firstNameInput') ?? $patient[0]->first_name }}" class="form-control" id="first_name_input" placeholder="ชื่อ" required>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="lastName" class="text-info">1.5 นามสกุล</label>
					<input type="text" name="lastNameInput" value="{{ old('lastNameInput') ?? $patient[0]->last_name }}" class="form-control" id="last_name_input" placeholder="นามสกุล" required>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="HN" class="text-info">1.6 HN</label>
					<input type="text" name="hnInput" value="{{ old('hnInput') ?? $patient[0]->hn }}" class="form-control" id="hn_input" placeholder="HN" required>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="AN" class="text-info">1.7 AN</label>
					<input type="text" name="anInput" value="{{ old('anInput') ?? $patient[0]->an }}" class="form-control" id="an_input" placeholder="AN">
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="sex" class="text-info">1.8 เพศ</label>
					<select name="sexInput" class="form-control selectpicker show-tick" id="select_sex">
						@if ((!empty(old('sexInput')) || !is_null($patient[0]->gender)) && !empty($patient[0]->gender) && $patient[0]->gender != '0')
							<option value="{{ old('sexInput') ?? $patient[0]->gender }}" selected="selected">{{ old('sexInput') ?? $refGender[$patient[0]->gender] }}</option>
						@endif
						<option value="0">-- โปรดเลือก --</option>
						<option value="male">ชาย</option>
						<option value="female">หญิง</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="nationality" class="text-info">1.9 สัญชาติ</label>
					<select name="nationalityInput" class="form-control selectpicker show-tick" id="select_nationality">
						@if ((!empty(old('nationalityInput')) || !is_null($patient[0]->nationality)) && !empty($patient[0]->nationality) && $patient[0]->nationality != '0'))
							<option value="{{ old('nationalityInput') ?? $patient[0]->nationality }}" selected="selected">{{ $nationality[old('nationalityInput')]->name_th ?? $nationality[$patient[0]->nationality]->name_th }}</option>
						@endif
						<option value="0">-- โปรดเลือก --</option>
						@foreach ($nationality as $key => $value)
							<option value="{{ $value->id }}" @if (old('nationalityInput') == $value->id) selected="selected" @endif>{{ $value->name_th }}</option>
							@endforeach
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<div class="form-group">
					<label for="otherNationality" class="text-info">1.10 สัญชาติ อื่นๆ ระบุ</label>
					<input type="text" name="otherNationalityInput" value="{{ old('otherNationalityInput') ?? $patient[0]->nationality_other }}" class="form-control" id="other_nationality_input" placeholder="สัญชาติอื่นๆ" @if (empty(old('otherNationalityInput')) && empty($patient[0]->nationality_other)) disabled @else "" @endif>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<div class="form-group">
					<label for="birthDate" class="text-info">1.11 วัน/เดือน/ปี เกิด</label>
					<div class="input-group date">
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
							</div>
							<input  type="text" name="birthDayInput" value="{{ old('birthDayInput') ?? $data['date_of_birth'] }}" data-provide="datepicker" class="form-control" id="date_of_birth" readonly>
							<div class="input-group-append">
								<button type="button" class="input-group-text text-danger" id="cls_date_of_birth"><i class="fas fa-times"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3">
				<div class="form-group">
					<label for="ageYear" class="text-info">1.12 อายุ/ปี</label>
					<input type="text" name="ageYearInput" value="{{ old('ageYearInput') ?? $patient[0]->age_year }}" class="form-control" id="age_year_input" size="3" maxlength="3" min="0" max="110">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3">
				<div class="form-group">
					<label for="ageMonth" class="text-info">1.13 อายุ/เดือน</label>
					<input type="text" name="ageMonthInput" value="{{ old('ageMonthInput') ?? $patient[0]->age_month }}" class="form-control" id="age_month_input" size="2" maxlength="2" min="0" max="12">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3">
				<div class="form-group">
					<label for="ageMonth" class="text-info">1.14 อายุ/วัน</label>
					<input type="text" name="ageDayInput" value="{{ old('ageDayInput') ?? $patient[0]->age_day }}" class="form-control" id="age_day_input" size="2" maxlength="2" min="0" max="31">
				</div>
			</div>
			<!--
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="calc_age">อายุ/วัน</label>
					<button class="btn btn-danger" id="calc_new_age">คำนวณอายุใหม่</button>
				</div>
			</div>
			-->
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
				<div class="form-group {{ $errors->has('hospitalInput') ? 'has-error' : '' }}">
					<label for="hospital" class="text-info">1.15 โรงพยาบาล</label>
					<select name="hospitalInput" class="form-control selectpicker show-tick" id="select_hospital" data-live-search="true">
						@role('admin')
							@if ((!empty(old('hospitalInput')) || !is_null($patient[0]->hospital)) && !empty($patient[0]->hospital) && $patient[0]->hospital != '0'))
								<option value="{{ old('hospitalInput') ?? $patient[0]->hospital }}") selected="selected">{{ $hospital[old('hospitalInput')]->hosp_name ?? $hospital[$patient[0]->hospital]->hosp_name }}</option>
							@endif
							<option value="">-- เลือกโรงพยาบาล --</option>
							@foreach ($hospital as $key => $val)
								<option value="{{ $val->hospcode }}">{{ $val->hosp_name }}</option>
							@endforeach
						@endrole
						@role('hospital|lab')
							<option value="{{ old('hospitalInput') ?? $user_hospital[0]->hospcode }}" selected="selected">{{ $hospital[old('hospitalInput')]->hosp_name ?? $user_hospital[0]->hosp_name }}</option>
						@endrole
						@role('hosp-group')
							<option value="{{ old('hospitalInput') ?? $patient[0]->hospital }}" selected="selected">{{ $hospital[old('hospitalInput')]->hosp_name ?? $hospital[$patient[0]->hospital]->hosp_name }}</option>
						@endrole
					</select>
				</div>
				<span class="text-danger">{{ $errors->first('hospitalInput') }}</span>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="houseNo" class="text-info">1.16 ที่อยู่ปัจจุบัน/ขณะป่วย เลขที่</label>
					<input type="text" name="houseNoInput" value="{{ old('houseNoInput') ?? $patient[0]->house_no }}" class="form-control" placeholder="บ้านเลขที่">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="villageNo" class="text-info">1.17 หมู่ที่</label>
					<input type="text" name="villageNoInput" value="{{ old('villageNoInput') ?? $patient[0]->village_no }}" class="form-control" placeholder="หมู่ที่">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="village" class="text-info">1.18 หมู่บ้าน</label>
				<input type="text" name="villageInput" value="{{ old('villageInput') ?? $patient[0]->village }}" class="form-control" placeholder="หมู่บ้าน">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="lane" class="text-info">1.19 ซอย</label>
					<input type="text" name="laneInput" value="{{ old('laneInput') ?? $patient[0]->lane }}" class="form-control" placeholder="ซอย">
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group {{ $errors->has('provinceInput') ? 'has-error' : '' }}">
					<label for="province" class="text-info">1.20 จังหวัด</label>
					<select name="provinceInput" class="form-control selectpicker show-tick" data-live-search="true" id="select_province">
						@if ((!empty(old('provinceInput'))) || (!is_null($patient[0]->province) && !empty($patient[0]->province) && $patient[0]->province != '0'))
							<option value="{{ old('provinceInput') ?? $patient[0]->province }}" selected="selected">{{ $provinces[old('provinceInput')]['province_name'] ?? $provinces[$patient[0]->province]['province_name'] }}</option>
						@endif
						<option value="0">-- เลือกจังหวัด --</option>
						@foreach($provinces as $key => $val)
							<option value="{{ $val['province_id'] }}">{{ $val['province_name'] }}</option>
						@endforeach
					</select>
				</div>
				<span class="text-danger">{{ $errors->first('provinceInput') }}</span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group {{ $errors->has('districtInput') ? 'has-error' : '' }}">
					<label for="district" class="text-info">1.21 อำเภอ</label>
					<select name="districtInput" class="form-control selectpicker show-tick" id="select_district">
						@if (!empty($patient[0]->district) && !is_null($patient[0]->district) && $patient[0]->district != '0')
							<option value="{{ $district[0]['district_id'] }}" selected="selected">{{ $district[0]['district_name'] }}</option>
						@endif
						<option value="0">-- โปรดเลือก --</option>
					</select>
				</div>
				<span class="text-danger">{{ $errors->first('districtInput') }}</span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group {{ $errors->has('subDistrictInput') ? 'has-error' : '' }}">
					<label for="subDistrict" class="text-info">1.22 ตำบล</label>
					<select name="subDistrictInput" class="form-control selectpicker show-tick" id="select_sub_district">
						@if (!empty($patient[0]->sub_district) && !is_null($patient[0]->sub_district) && $patient[0]->sub_district != '0')
							<option value="{{ $patient[0]->sub_district }}" selected="selected">{{ $sub_district[0]['sub_district_name'] }}</option>
						@endif
						<option value="">-- โปรดเลือก --</option>
					</select>
				</div>
				<span class="text-danger">{{ $errors->first('subDistrictInput') }}</span>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="occupation" class="text-info">1.23 อาชีพ</label>
				<select name="occupationInput" class="form-control selectpicker show-tick" id="select_occupation">
					@if ((!empty(old('occupationInput'))) || (!is_null($patient[0]->occupation) && !empty($patient[0]->occupation) && $patient[0]->occupation != '0'))
						<option value="{{ old('occupationInput') ?? $patient[0]->occupation }}" selected="selected">{{ $occupation[old('occupationInput')]['occu_name_th'] ?? $occupation[$patient[0]->occupation]['occu_name_th'] }}</option>
					@endif
					<option value="0">-- โปรดเลือก --</option>
					@foreach ($occupation as $key => $val)
						<option value="{{ $val['id'] }}">{{ $val['occu_name_th'] }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<label for="occupationOtherInput" class="text-info">1.24 อาชีพ อื่นๆ ระบุ</label>
				<input type="text" name="occupationOtherInput" value="{{ old('occupationOtherInput') ?? $patient[0]->occupation_other }}" class="form-control" id="occupation_other_input" placeholder="อาชีพ อื่นๆ" @if (empty(old('occupationOtherInput')) && empty($patient[0]->occupation_other)) disabled @else "" @endif>
			</div>
		</div>
	</div>
</div>
