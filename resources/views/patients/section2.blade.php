<div class="card">
	<div class="card-body">
		<h1 class="text-color-custom-1">2. ข้อมูลทางคลินิก</h1>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3 {{ $errors->has('patientType') ? 'border-danger' : '' }}">
				<div class="form-group">
					<label for="patient" class="text-info">2.1 ประเภทผู้ป่วย</label>
					<div>
						<div class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" name="patientType" value="opd" class="custom-control-input pt-type" id="opdCheckbox" @if (old('patientType') == 'opd' || $clinical['pt_type'] == 'opd') checked @endif>
							<label for="opdCheckbox" class="custom-control-label normal-label">ผู้ป่วยนอก (OPD)/ILI</label>
						</div>
						<div class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" name="patientType" value="ipd" class="custom-control-input pt-type" id="ipdCheckbox" @if (old('patientType') == 'ipd' || $clinical['pt_type'] == 'ipd') checked @endif>
							<label for="ipdCheckbox" class="custom-control-label normal-label">ผู้ป่วยใน (IPD)/SARI</label>
						</div>
						<div class="custom-control custom-checkbox custom-control-inline">
							<input type="checkbox" name="patientType" value="icu" class="custom-control-input pt-type" id="icuCheckbox" @if (old('patientType') == 'icu' || $clinical['pt_type'] == 'icu') checked @endif>
							<label for="icuCheckbox" class="custom-control-label normal-label">ผู้ป่วยหนัก/ICU</label>
						</div>
					</div>
				</div>
				<span class="text-danger">{{ $errors->first('patientType') }}</span>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="date" class="text-info">2.2 วันที่เริ่มป่วย</label>
					<div class="input-group date">
						<div class="input-group-append">
							<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
						</div>
						<input type="text" name="sickDateInput" value="{{ old('sickDateInput') ?? $data['date_sick'] }}" data-provide="datepicker" id="sick_date" class="form-control {{ $errors->has('sickDateInput') ? 'border-danger' : '' }}" required readonly>
						<div class="input-group-append">
							<button type="button" class="input-group-text text-danger" id="cls_sick_date"><i class="fas fa-times"></i></button>
						</div>
					</div>
				</div>
				<span class="text-danger">{{ $errors->first('sickDateInput') }}</span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="date" class="text-info">2.3 วันที่รักษาครั้งแรก</label>
					<div class="input-group date">
						<div class="input-group-append">
							<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
						</div>
						<input type="text" name="treatDateInput" value="{{ old('treatDateInput') ?? $data['date_define'] }}" data-provide="datepicker" id="treat_date" class="form-control {{ $errors->has('treatDateInput') ? 'border-danger' : '' }}" readonly>
						<div class="input-group-append">
							<button type="button" class="input-group-text text-danger" id="cls_treat_date"><i class="fas fa-times"></i></button>
						</div>
					</div>
				</div>
				<span class="text-danger">{{ $errors->first('treatDateInput') }}</span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="date" class="text-info">2.4 วันที่นอนโรงพยาบาล</label>
					<div class="input-group date">
						<div class="input-group-append">
							<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
						</div>
						<input type="text" name="admitDateInput" value="{{ old('admitDateInput') ?? $data['date_admit'] }}" data-provide="datepicker" id="admit_date" class="form-control" readonly>
						<div class="input-group-append">
							<button type="button" class="input-group-text text-danger" id="cls_admit_date"><i class="fas fa-times"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<div class="form-group">
					<label for="sickDateInput" class="text-info">2.5 อุณหภูมิร่างกายแรกรับ</label>
					<div class="input-group">
						<input type="text" name="temperatureInput" value="{{ old('temperatureInput') ?? $clinical['pt_temperature'] }}" class="form-control">
						<div class="input-group-append">
							<span class="input-group-text">C&#176;</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="sickDateInput" class="text-info">2.6 อาการและอาการแสดง</label>
				<div class="table-responsive">
					<table class="table" id="symptoms_table">
						<thead class="bg-custom-1 text-light">
							<tr>
								<th scope="col">อาการ</th>
								<th scope="col">มี</th>
								<th scope="col">ไม่มี</th>
								<th scope="col">ไม่ทราบ</th>
							</tr>
						</thead>
						<tbody>
						@foreach($symptoms as $key => $val)
							<tr id="{{ 'symptoms_table_tr'.$key }}">
								<td>
									{!! $val->symptom_name_th.'&nbsp;&#40;'.$val->symptom_name_en.'&#41;' !!}
									@if ($val->id == 21)
										<input type="text" name="other_symptom_input" value="{{ old('other_symptom_input') ?? $clinical['other_symptom_specify'] }}" class="form-control" id="symptom_other">
									@endif

								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="{{ 'symptom_'.$val->id.'_Input' }}" value="y" @if ($clinical[$val->ref_clinical_field] == 'y' || old('symptom_'.$val->id.'_Input') == 'y') checked @endif class="{{ 'custom-control-input symptom-'.$val->id }}" id="{{ 'symptom_'.$val->id.'_yes' }}">
										<label for="{{ "symptom_".$val->id."_yes" }}" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name=" {{'symptom_'.$val->id.'_Input' }}" value="n" @if ($clinical[$val->ref_clinical_field] == 'n' || old('symptom_'.$val->id.'_Input') == 'n') checked @endif class="{{ 'custom-control-input symptom-'.$val->id }}" id="{{ 'symptom_'.$val->id.'_no' }}">
										<label for="{{ 'symptom_'.$val->id.'_no' }}" class="custom-control-label"></label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="{{ 'symptom_'.$val->id.'_Input' }}" value="u" @if ($clinical[$val->ref_clinical_field] == 'u' || old('symptom_'.$val->id.'_Input') == 'u') checked @endif class="{{ 'custom-control-input symptom-'.$val->id }}" id="{{ 'symptom_'.$val->id.'_un' }}">
										<label for="{{ 'symptom_'.$val->id.'_un' }}" class="custom-control-label"></label>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="form-row border-top pt-3">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="firstXrayInput" class="text-info">2.7 เอกซเรย์ปอด (ครั้งแรก)</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="lungXrayInput" value="n" @if ($clinical['lung'] == 'n' || old('lungXrayInput') == 'n') checked @endif class="custom-control-input lungXray" id="lungXrayNo">
						<label for="lungXrayNo" class="custom-control-label normal-label">ไม่ได้ทำ</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="lungXrayInput" value="y" @if ($clinical['lung'] == 'y' || old('lungXrayInput') == 'y') checked @endif class="custom-control-input lungXray" id="lungXrayYes">
						<label for="lungXrayYes" class="custom-control-label normal-label">ทำ</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl- mb-3">
				<label for="date" class="text-info">2.8 ระบุวันที่เอกซเรย์ปอด</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="xRayDateInput" value="{{ old('xRayDateInput') ?? $data['lung_date'] }}" data-provide="datepicker" class="form-control" id="xRayDate" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_lung_date"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<label for="xRayResult" class="text-info">2.9 ระบุผลเอกซเรย์ปอด</label>
				<input type="text" name="xRayResultInput" value="{{ old('xRayResultInput') ?? $clinical['lung_result'] }}" class="form-control" id="xRayRs">
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="date" class="text-info">2.10 CBC (ครั้งแรก): วันที่</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="cbcDateInput" value="{{ old('cbcDateInput') ?? $data['cbc_date'] }}" data-provide="datepicker" class="form-control" id="cbcDateInput" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_cbc_date"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="hbInput" class="text-info">2.11 ผล Hb</label>
				<div class="input-group">
					<input type="text" name="hbInput" value="{{ old('hbInput') ?? $clinical['hb'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">mg%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="htcInput" class="text-info">2.12 Hct</label>
				<div class="input-group">
					<input type="text" name="htcInput" value="{{ old('htcInput') ?? $clinical['hct'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="plateletCountInput" class="text-info">2.13 Plate count</label>
				<div class="input-group">
					<input type="text" name="plateletInput" value="{{ old('plateletInput') ?? $clinical['platelet_count'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">x10<sup>3</sup></span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="wbcInput" class="text-info">2.14 WBC</label>
				<input type="text" name="wbcInput" value="{{ old('wbcInput') ?? $clinical['wbc'] }}" class="form-control">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="nInput" class="text-info">2.15 N</label>
				<div class="input-group">
					<input type="text" name="nInput" value="{{ old('nInput') ?? $clinical['n'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="lInput" class="text-info">2.16 L</label>
				<div class="input-group">
					<input type="text" name="lInput" value="{{ old('lInput') ?? $clinical['l'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="atypLymphInput" class="text-info">2.17 Atyp lymph</label>
				<div class="input-group">
					<input type="text" name="atypLymphInput" value="{{ old('atypLymphInput') ?? $clinical['atyp_lymph'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="monoInput" class="text-info">2.18 Mono</label>
				<div class="input-group">
					<input type="text" name="monoInput" value="{{ old('monoInput') ?? $clinical['mono'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="basoInput" class="text-info">2.19 Baso</label>
				<div class="input-group">
					<input type="text" name="basoInput" value="{{ old('basoInput') ?? $clinical['baso'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="eoInput" class="text-info">2.20 Eo</label>
				<div class="input-group">
					<input type="text" name="eoInput" value="{{ old('eoInput') ?? $clinical['eo'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="bandInput" class="text-info">2.21 Band</label>
				<div class="input-group">
					<input type="text" name="bandInput" value="{{ old('bandInput') ?? $clinical['band'] }}" class="form-control">
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="firstDiagnosis" class="text-info">2.22 การวินิจฉัยเบื้องต้น</label>
				<input type="text" name="firstDiagnosisInput" value="{{ old('firstDiagnosisInput') ?? $clinical['first_diag'] }}" class="form-control" placeholder="การวินิจฉัยเบื้องต้น">
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="influenzaRapid" class="text-info">2.23 มีการตรวจ Influenza Rapid test</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="influRapidInput" value="n" @if ($clinical['rapid_test'] == 'n' || old('influRapidInput') == 'n') checked @endif class="custom-control-input influRapid" id="influRapidUnChecked">
						<label for="influRapidUnChecked" class="custom-control-label normal-label">ไม่ตรวจ</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="influRapidInput" value="y" @if ($clinical['rapid_test'] == 'y' || old('influRapidInput') == 'y') checked @endif class="custom-control-input influRapid" id="influRaidCheckd">
						<label for="influRaidCheckd" class="custom-control-label normal-label">ตรวจ</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="influTestName" class="text-info">2.24 กรณีตรวจ ระบุชื่อ test</label>
				<input type="text" name="influRapidtestName" value="{{ old('influRapidtestName') ?? $clinical['rapid_test_name'] }}" class="form-control" placeholder="ระบุชื่อ test" id="influRapidTestName" @if (empty(old('influRapidtestName')) && empty($clinical['rapid_test_name']) ) disabled @else "" @endif>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="rapidTestResult" class="text-info">2.25 ผล Rapid test</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="rapidTestResultInput[]" value="nagative" @if((is_array(old('rapidTestResultInput')) && in_array('nagative', old('rapidTestResultInput'))) || in_array('nagative', $rapid_result)) checked @endif class="custom-control-input influRapidRs" id="rapidTestNagative">
						<label for="rapidTestNagative" class="custom-control-label normal-label">Nagative</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="rapidTestResultInput[]" value="positive-flu-a" @if((is_array(old('rapidTestResultInput')) && in_array('positive-flu-a', old('rapidTestResultInput'))) || in_array('positive-flu-a', $rapid_result)) checked @endif class="custom-control-input influRapidRs" id="rapidTestPositiveFluA">
						<label for="rapidTestPositiveFluA" class="custom-control-label normal-label">Positive Flu A</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="rapidTestResultInput[]" value="positive-flu-b" @if((is_array(old('rapidTestResultInput')) && in_array('positive-flu-b', old('rapidTestResultInput'))) || in_array('positive-flu-b', $rapid_result)) checked @endif class="custom-control-input influRapidRs" id="rapidTestPositiveFluB">
						<label for="rapidTestPositiveFluB" class="custom-control-label normal-label">Positive Flu B</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="influVaccineReceive" class="text-info">2.26 ผู้ป่วยเคยได้รับวัคซีนไข้หวัดใหญ่หรือไม่</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="influVaccineInput" value="n" @if ($clinical['flu_vaccine'] == 'n'|| old('influVaccineInput') == 'n') checked @endif class="custom-control-input influVaccineRc" id="influVaccineNo">
						<label for="influVaccineNo" class="custom-control-label normal-label">ไม่เคย</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="influVaccineInput" value="u" @if ($clinical['flu_vaccine'] == 'u'|| old('influVaccineInput') == 'u') checked @endif class="custom-control-input influVaccineRc" id="influVaccineUnknown">
						<label for="influVaccineUnknown" class="custom-control-label normal-label">เคยได้รับแต่ไม่ทราบวันที่</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="influVaccineInput" value="y" @if ($clinical['flu_vaccine'] == 'y'|| old('influVaccineInput') == 'y') checked @endif class="custom-control-input influVaccineRc" id="influVaccineYes">
						<label for="influVaccineYes" class="custom-control-label normal-label">เคย</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<label for="date" class="text-info">2.27 เคยได้รับเมื่อวันที่</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="influVaccineDateInput" value="{{ old('influVaccineDateInput') ?? $data['flu_vaccine_date'] }}" data-provide="datepicker" class="form-control" id="influVaccineDate" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_influVaccineDate"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
		</div>
		{{-- <div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="influVaccineReceive">ผู้ป่วยเคยได้รับวัคซีนไข้หวัดใหญ่หรือไม่</label>
				<div>
					<div class="custom-control custom-checkbox custom-control-inline">
						<input type="checkbox" name="influVaccineInput" value="n" @if ($clinical['flu_vaccine'] == 'n'|| old('influVaccineInput') == 'n') checked @endif class="custom-control-input influVaccineRc" id="influVaccineNo">
						<label for="influVaccineNo" class="custom-control-label normal-label">ไม่เคย</label>
					</div>
					<div class="custom-control custom-checkbox custom-control-inline">
						<input type="checkbox" name="influVaccineInput" value="y" @if ($clinical['flu_vaccine'] == 'y'|| old('influVaccineInput') == 'y') checked @endif class="custom-control-input influVaccineRc" id="influVaccineYes">
						<label for="influVaccineYes" class="custom-control-label normal-label">เคย</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3">
				<label for="date">เคยได้รับเมื่อ</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="influVaccineDateInput" value="{{ old('influVaccineDateInput') ?? $data['flu_vaccine_date'] }}" data-provide="datepicker" class="form-control" id="influVaccineDate" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_influVaccineDate"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
		</div> --}}
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="virusMedicine" class="text-info">2.28 การให้ยาต้านไวรัส</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="virusMedicineInput" value="n" @if ($clinical['antiviral'] == 'n' || old('virusMedicineInput') == 'n') checked @endif class="custom-control-input virusMedic" id="virusMedicineNo">
						<label for="virusMedicineNo" class="custom-control-label normal-label">ไม่ให้</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="virusMedicineInput" value="y" @if ($clinical['antiviral'] == 'y' || old('virusMedicineInput') == 'y') checked @endif class="custom-control-input virusMedic" id="virusMedicineYes">
						<label for="virusMedicineYes" class="custom-control-label normal-label">ให้</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<label for="medicineName" class="text-info">2.29 ชื่อยา</label>
				<input type="text" name="medicineNameInput" value="{{ old('medicineNameInput') ?? $clinical['antiviral_name']}}" class="form-control" id="medicineName" placeholder="ชื่อยา">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
				<label for="date" class="text-info">2.30 วันที่เริ่มให้ยา</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="medicineGiveDateInput" value="{{ old('medicineGiveDateInput') ?? $data['antiviral_date'] }}" data-provide="datepicker" class="form-control" id="medicineDate" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_medicineDate"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3 mb-3">
				<label for="specimenInput" class="text-info">2.31 ชนิดของตัวอย่างที่ส่งตรวจ</label>
				<div class="table-responsive">
					<table class="table" id="specimen_table">
						<thead class="bg-danger text-light">
							<tr>
								<th scope="col">ตัวอย่างส่งตรวจ</th>
								<th scope="col">วันที่เก็บตัวอย่าง</th>
							</tr>
						</thead>
						<tfoot></tfoot>
						<tbody>
						@foreach ($specimen_data as $key => $val)
							<tr id="specimen_tr{{ $val['sp_id'] }}">
								<td>
									<div class="form-group row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
											<div class="custom-control custom-checkbox custom-control-inline">
												<input type="checkbox" name="specimen{{ $val['sp_id'] }}" value="{{ $val['psp_id'] }}" @if (!is_null($val['psp_id'])) checked @endif class="custom-control-input form-check-input specimen-chk-{{ $val['sp_id'] }}" id="specimen_chk_{{ $val['sp_id'] }}">
												<label for="specimen_chk_{{ $val['sp_id'] }}" class="custom-control-label font-weight-normal">{{ $val['sp_name_en'] }}</label>
											</div>
										</div>
										@if ($val['sp_other_field'] == 'Yes')
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
											<input type="text" name="specimenOth{{ $val['sp_id'] }}" value="{{ $val['psp_specimen_other'] }}" id="specimen_oth{{$val['sp_id'] }}" class="form-control">
										</div>
										@endif
									</div>
								</td>
								<td>
									<div class="form-group row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
											<div class="input-group date">
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
													</div>
													<input type="text" name="specimenDate{{ $val['sp_id'] }}" value="{{ $val['psp_specimen_date'] }}" data-provide="datepicker" data-id="specimenDate{{ $val['sp_id'] }}" class="form-control" id="specimenDate_{{ $val['sp_id'] }}" readonly>
													<div class="input-group-append">
														<button type="button" class="input-group-text text-danger" id="cls_specimenDate_{{ $val['sp_id'] }}"><i class="fas fa-times"></i></button>
													</div>
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
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="sickDateInput" class="text-info">2.32 ภาวะสุขภาพ หรือ โรคประจำตัว</label>
				<div class="table-responsive">
					<table class="table" id="health_table">
						<thead class="bg-info text-light">
							<tr>
								<th scope="col">โรคประจำตัว</th>
								<th scope="col">มี</th>
								<th scope="col">ไม่มี</th>
								<th scope="col">ไม่ทราบ</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="4"><div data-class="bg-danger"><i class="fa fa-circle text-danger m-r-10"></i><strong>หมายเหตุ</strong> <span class="text-primary">ภาวะทุพโภชการ/ขาดสารอาหาร ต้องมีการวินิจฉัยโดยแพทย์ ไม่ประเมินด้วยสายตา</span></div></th>
							</tr>
						</tfoot>
						<tbody>
							<tr id="health_table_tr1">
								<td>
									<div class="form-group row">
										<label for="pregnant" class="mt-2 font-normal">หญิงตั้งครรภ์ ระบุ อายุครรภ์</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
											<div class="input-group">
												<input type="text" name="pregnantWeekInput" value="{{ old('pregnantWeekInput') ?? $clinical['pregnant_wk'] }}" class="form-control" id="pregnant_age_week" @if (empty(old('pregnantWeekInput')) && empty($clinical['pregnant_wk'])) disabled @else "" @endif>
												<div class="input-group-append">
													<span class="input-group-text">สัปดาห์</span>
												</div>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="pregnantInput" value="y" @if ($clinical['pregnant'] =='y' || old('pregnantInput') == 'y') checked @endif class="custom-control-input health-1" id="pregnant_age_y">
										<label for="pregnant_age_y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="pregnantInput" value="n" @if ($clinical['pregnant'] =='n' || old('pregnantInput') == 'n') checked @endif class="custom-control-input health-1" id="pregnant_age_n">
										<label for="pregnant_age_n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="pregnantInput" value="u" @if ($clinical['pregnant'] =='u' || old('pregnantInput') == 'u') checked @endif class="custom-control-input health-1" id="pregnant_age_u">
										<label for="pregnant_age_u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr2">
								<td>
									<div class="form-group row">
										<label for="give_birth" class="mt-2 font-normal">หญิงหลังคลอด ในช่วง 2 สัปดาห์แรก</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="postPregnantInput" value="y" @if ($clinical['post_pregnant'] == 'y' || old('postPregnantInput') == 'y') checked @endif class="custom-control-input health-2" id="after_give_birth_y">
										<label for="after_give_birth_y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="postPregnantInput" value="n" @if ($clinical['post_pregnant'] == 'n' || old('postPregnantInput') == 'n') checked @endif class="custom-control-input health-2" id="after_give_birth_n">
										<label for="after_give_birth_n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="postPregnantInput" value="u" @if ($clinical['post_pregnant'] == 'u' || old('postPregnantInput') == 'u') checked @endif class="custom-control-input health-2" id="after_give_birth_u">
										<label for="after_give_birth_u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr3">
								<td>
									<div class="form-group row">
										<label for="fat" class="mt-2 font-normal">อ้วน ระบุส่วนสูง</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
											<div class="input-group">
												<input type="text" name="fatHeightInput" value="{{ old('fatHeightInput') ?? $clinical['fat_high'] }}" class="form-control" id="fat-height-input" @if (empty(old('fatHeightInput')) && empty($clinical['fat_high'])) disabled @else "" @endif>
												<div class="input-group-append">
													<span class="input-group-text">cm</span>
												</div>
											</div>
										</div>
										<label for="fat_weight" class="mt-2 font-normal">น้ำหนัก</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
											<div class="input-group">
												<input type="text" name="fatWeightInput" value="{{ old('fatWeightInput') ?? $clinical['fat_weight'] }}" class="form-control" id="fat-weight-input" @if (empty(old('fatWeightInput')) && empty($clinical['fat_weight'])) disabled @else "" @endif>
												<div class="input-group-append">
													<span class="input-group-text">kg</span>
												</div>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="fatInput" value="y" @if ($clinical['fat'] == 'y' || old('fatInput') == 'y') checked @endif class="custom-control-input health-3" id="fat-input-y">
										<label for="fat-input-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="fatInput" value="n" @if ($clinical['fat'] == 'n' || old('fatInput') == 'n') checked @endif  class="custom-control-input health-3" id="fat-input-n">
										<label for="fat-input-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="fatInput" value="u" @if ($clinical['fat'] == 'u' || old('fatInput') == 'u') checked @endif class="custom-control-input health-3" id="fat-input-u">
										<label for="fat-input-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr4">
								<td>
									<div class="form-group row">
										<label for="diabetes" class="mt-2 font-normal">เบาหวาน</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="diabetesInput" value="y" @if ($clinical['diabetes'] == 'y' || old('diabetesInput') == 'y') checked @endif class="custom-control-input health-4" id="diabetes-y">
										<label for="diabetes-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="diabetesInput" value="n" @if ($clinical['diabetes'] == 'n' || old('diabetesInput') == 'n') checked @endif class="custom-control-input health-4" id="diabetes-n">
										<label for="diabetes-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="diabetesInput" value="u" @if ($clinical['diabetes'] == 'u' || old('diabetesInput') == 'u') checked @endif class="custom-control-input health-4" id="diabetes-u">
										<label for="diabetes-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr5">
								<td>
									<div class="form-group row">
										<label for="immune" class="mt-2 font-normal">ภูมิคุ้มกันบกพร่อง ระบุ</label>
										<div class="col-xs-8 col-sm-8 col-md-6 col-lg-4 col-xl-4">
											<div class="input-group">
												<input type="text" name="immuneSpecifyInput" value="{{ old('immuneSpecifyInput') ?? $clinical['immune_specify'] }}" class="form-control" id="immune_specify" @if (empty(old('immuneSpecifyInput')) && empty($clinical['immune_specify'])) disabled @else "" @endif>
											</div>
										</div>

									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="immuneInput" value="y" @if ($clinical['immune'] == 'y' || old('immuneInput') == 'y') checked @endif class="custom-control-input health-5" id="immune-y">
										<label for="immune-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="immuneInput" value="n" @if ($clinical['immune'] == 'n' || old('immuneInput') == 'n') checked @endif class="custom-control-input health-5" id="immune-n">
										<label for="immune-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="immuneInput" value="u" @if ($clinical['immune'] == 'u' || old('immuneInput') == 'u') checked @endif class="custom-control-input health-5" id="immune-u">
										<label for="immune-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr6">
								<td>
									<div class="form-group row">
										<label for="preterm_infant" class="mt-2 font-normal">คลอดก่อนกำหนด อายุครรภ์</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
											<div class="input-group">
												<input type="text" name="earlyBirthWeekInput" value="{{ old('earlyBirthWeekInput') ?? $clinical['early_birth_wk'] }}" class="form-control" id="preterm-infant-week" @if (empty(old('earlyBirthWeekInput')) && empty($clinical['early_birth_wk'])) disabled @else "" @endif>
												<div class="input-group-append">
													<span class="input-group-text">สัปดาห์</span>
												</div>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="earlyBirthInput" value="y" @if ($clinical['early_birth'] == 'y' || old('earlyBirthInput') == 'y') checked @endif class="custom-control-input health-6" id="preterm-infant-y">
										<label for="preterm-infant-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="earlyBirthInput" value="n" @if ($clinical['early_birth'] == 'n' || old('earlyBirthInput') == 'n') checked @endif class="custom-control-input health-6" id="preterm-infant-n">
										<label for="preterm-infant-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="earlyBirthInput" value="u" @if ($clinical['early_birth'] == 'u' || old('earlyBirthInput') == 'u') checked @endif class="custom-control-input health-6" id="preterm-infant-u">
										<label for="preterm-infant-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr7">
								<td>
									<div class="form-group row">
										<label for="malnutrition" class="mt-2 font-normal">ภาวะทุพโภชนาการ/ขาดสารอาหาร</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="malnutritionInput" value="y" @if ($clinical['malnutrition'] == 'y' || old('malnutritionInput') == 'y') checked @endif class="custom-control-input health-7" id="malnutrition-y">
										<label for="malnutrition-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="malnutritionInput" value="n" @if ($clinical['malnutrition'] == 'n' || old('malnutritionInput') == 'n') checked @endif class="custom-control-input health-7" id="malnutrition-n">
										<label for="malnutrition-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="malnutritionInput" value="u" @if ($clinical['malnutrition'] == 'u' || old('malnutritionInput') == 'u') checked @endif class="custom-control-input health-7" id="malnutrition-u">
										<label for="malnutrition-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr8">
								<td>
									<div class="form-group row">
										<label for="copd" class="mt-2 font-normal">โรคปอดเรื้อรัง (COPD)</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="copdInput" value="y" @if ($clinical['copd'] == 'y' || old('copdInput') == 'y') checked @endif class="custom-control-input health-8" id="copd-y">
										<label for="copd-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="copdInput" value="n" @if ($clinical['copd'] == 'n' || old('copdInput') == 'n') checked @endif class="custom-control-input health-8" id="copd-n">
										<label for="copd-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="copdInput" value="u" @if ($clinical['copd'] == 'u' || old('copdInput') == 'u') checked @endif class="custom-control-input health-8" id="copd-u">
										<label for="copd-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr9">
								<td>
									<div class="form-group row">
										<label for="asthma" class="mt-2 font-normal">หอบหืด</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="asthmaInput" value="y" @if ($clinical['asthma'] == 'y' || old('asthmaInput') == 'y') checked @endif class="custom-control-input health-9" id="asthma-y">
										<label for="asthma-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="asthmaInput" value="n" @if ($clinical['asthma'] == 'n' || old('asthmaInput') == 'n') checked @endif class="custom-control-input health-9" id="asthma-n">
										<label for="asthma-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="asthmaInput" value="u" @if ($clinical['asthma'] == 'u' || old('asthmaInput') == 'u') checked @endif class="custom-control-input health-9" id="asthma-u">
										<label for="asthma-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr10">
								<td>
									<div class="form-group row">
										<label for="heart_disease" class="mt-2 font-normal">โรคหัวใจ</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="heartDiseaseInput" value="y" @if ($clinical['heart_disease'] == 'y' || old('heartDiseaseInput') == 'y') checked @endif class="custom-control-input health-10" id="heart-disease-y">
										<label for="heart-disease-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="heartDiseaseInput" value="n" @if ($clinical['heart_disease'] == 'n' || old('heartDiseaseInput') == 'n') checked @endif class="custom-control-input health-10" id="heart-disease-n">
										<label for="heart-disease-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="heartDiseaseInput" value="u" @if ($clinical['heart_disease'] == 'u' || old('heartDiseaseInput') == 'u') checked @endif class="custom-control-input health-10" id="heart-disease-u">
										<label for="heart-disease-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr11">
								<td>
									<div class="form-group row">
										<label for="stroke" class="mt-2 font-normal">โรคหลอดเลือกสมอง</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cerebralInput" value="y" @if ($clinical['cerebral'] == 'y' || old('cerebralInput') == 'y') checked @endif class="custom-control-input health-11" id="stroke-y">
										<label for="stroke-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cerebralInput" value="n" @if ($clinical['cerebral'] == 'n' || old('cerebralInput') == 'n') checked @endif class="custom-control-input health-11" id="stroke-n">
										<label for="stroke-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cerebralInput" value="u" @if ($clinical['cerebral'] == 'u' || old('cerebralInput') == 'u') checked @endif class="custom-control-input health-11" id="stroke-u">
										<label for="stroke-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr12">
								<td>
									<div class="form-group row">
										<label for="kidney_disease" class="mt-2 font-normal">โรคไตวาย</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="kidneyFailInput" value="y" @if ($clinical['kidney_fail'] == 'y' || old('kidneyFailInput') == 'y') checked @endif class="custom-control-input health-12" id="kidney-disease-y">
										<label for="kidney-disease-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="kidneyFailInput" value="n" @if ($clinical['kidney_fail'] == 'n' || old('kidneyFailInput') == 'n') checked @endif class="custom-control-input health-12" id="kidney-disease-n">
										<label for="kidney-disease-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="kidneyFailInput" value="u" @if ($clinical['kidney_fail'] == 'u' || old('kidneyFailInput') == 'u') checked @endif class="custom-control-input health-12" id="kidney-disease-u">
										<label for="kidney-disease-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr13">
								<td>
									<div class="form-group row">
										<label for="cancer" class="mt-2 font-normal">มะเร็ง ระบุ</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
											<div class="input-group">
												<input type="text" name="cancerSpecifyInput" value="{{ old('cancerSpecifyInput') ?? $clinical['cancer_specify'] }}" class="form-control" id="cancer-input" @if (empty(old('cancerSpecifyInput')) && empty($clinical['cancer_specify'])) disabled @else "" @endif>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cancerInput" value="y" @if ($clinical['cancer'] == 'y' || old('cancerInput') == 'y') checked @endif class="custom-control-input health-13" id="cancer-y">
										<label for="cancer-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cancerInput" value="n" @if ($clinical['cancer'] == 'n' || old('cancerInput') == 'n') checked @endif class="custom-control-input health-13" id="cancer-n">
										<label for="cancer-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="cancerInput" value="u" @if ($clinical['cancer'] == 'u' || old('cancerInput') == 'u') checked @endif class="custom-control-input health-13" id="cancer-u">
										<label for="cancer-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
							<tr id="health_table_tr14">
								<td>
									<div class="form-group row">
										<label for="other_disease" class="mt-2 font-normal">อื่นๆ ระบุ</label>
										<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
											<div class="input-group">
												<input type="text" name="otherCongenitalSpecifyInput" value="{{ old('otherCongenitalSpecifyInput') ?? $clinical['other_congenital_specify'] }}" class="form-control" id="other-disease-input" @if (empty(old('otherCongenitalSpecifyInput')) && empty($clinical['other_congenital_specify'])) disabled @else "" @endif>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="otherCongenitalInput" value="y" @if ($clinical['other_congenital'] == 'y' || old('otherCongenitalInput') == 'y') checked @endif class="custom-control-input health-14" id="other-disease-y">
										<label for="other-disease-y" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="otherCongenitalInput" value="n" @if ($clinical['other_congenital'] == 'n' || old('otherCongenitalInput') == 'n') checked @endif class="custom-control-input health-14" id="other-disease-n">
										<label for="other-disease-n" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="otherCongenitalInput" value="u" @if ($clinical['other_congenital'] == 'u' || old('otherCongenitalInput') == 'u') checked @endif class="custom-control-input health-14" id="other-disease-u">
										<label for="other-disease-u" class="custom-control-label">&nbsp;</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div><!-- card body -->
</div><!-- card -->
