<div class="card">
	<div class="card-body">
		<h1 class="text-info">3. ประวัติเสี่ยง</h1>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-primary text-light">
							<tr>
								<th scope="col">รายการ</th>
								<th scope="col">#</th>
								<th scope="col">#</th>
							</tr>
						</thead>
						<tfoot>
							<tr><td colspan="3">&nbsp;</td></tr>
						</tfoot>
						<tbody>
							<tr id="risk_table_tr1">
								<td>ช่วง 7 วันก่อนป่วยได้สัมผัสสัตว์ปีกป่วย/ตายโดยตรง</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactPoultry7Input" value="n" @if ($clinical['contact_poultry7'] == 'n' || old('contactPoultry7Input') == 'n') checked @endif class="custom-control-input risk-1" id="pet_touch_n">
										<label for="pet_touch_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactPoultry7Input" value="y" @if ($clinical['contact_poultry7'] == 'y' || old('contactPoultry7Input') == 'y') checked @endif class="custom-control-input risk-1" id="pet_touch_y">
										<label for="pet_touch_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr2">
								<td>
									<div class="form-group row mt-0 mb-0">
										<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
											<div class="input-group">
												<label for="pet_touch_range" class="font-normal">ช่วง 14 วันก่อนป่วยได้สัมผัสสัตว์ป่วยโดยตรงหรือไม่ ระบุชนิดสัตว์</label>
												<input type="text" name="contactPoultry14SpecifyInput" value="{{ old('contactPoultry14SpecifyInput') ?? $clinical['contact_poultry14_specify'] }}" class="form-control ml-2" id="pet_touch_name" @if (empty(old('contactPoultry14SpecifyInput')) && empty($clinical['contact_poultry14_specify'])) disabled @else "" @endif>
											</div>
										</div>
									</div>
								</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactPoultry14Input" value="n" @if ($clinical['contact_poultry14'] == 'n' || old('contactPoultry14Input') == 'n') checked @endif class="custom-control-input risk-2" id="pet_touch_direct_n">
										<label for="pet_touch_direct_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactPoultry14Input" value="y" @if ($clinical['contact_poultry14'] == 'y' || old('contactPoultry14Input') == 'y') checked @endif class="custom-control-input risk-2" id="pet_touch_direct_y">
										<label for="pet_touch_direct_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr3">
								<td>ช่วง 14 วันก่อนป่วยได้พักอาศัยอยู่ในพื้นที่ที่มีสัตว์ปีกป่วย/ตายผิดปกติ</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="stayPoultry14Input" value="n" @if ($clinical['stay_poultry14'] == 'n' || old('stayPoultry14Input') == 'n') checked @endif class="custom-control-input risk-3" id="stay_pet_death_n">
										<label for="stay_pet_death_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="stayPoultry14Input" value="y" @if ($clinical['stay_poultry14'] == 'y' || old('stayPoultry14Input') == 'y') checked @endif class="custom-control-input risk-3" id="stay_pet_death_y">
										<label for="stay_pet_death_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr4">
								<td>
									<div class="form-group row mt-0 mb-0">
										<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
											<div class="input-group">
												<label for="stay_outbreak" class="font-normal">ช่วง 14 วันก่อนป่วยได้พักอาศัยอยู่หรือเดินทางมาจากพื้นที่ที่ไข้หวัดใหญ่/ปอดอักเสบระบาด <span class="text-info">ระบุพื้นที่</span></label>
												<input type="text" name="stayFlu14PlaceSpecifyInput" value="{{ old('stayFlu14PlaceSpecifyInput') ?? $clinical['stay_flu14_place_specify'] }}" class="form-control ml-2" id="stay_outbreak_input" @if (empty(old('stayFlu14PlaceSpecifyInput')) && empty($clinical['stay_flu14_place_specify'])) disabled @else "" @endif>
											</div>
										</div>
									</div>
								</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="stayFlu14Input" value="n" @if ($clinical['stay_flu14'] == 'n' || old('stayFlu14Input') == 'n') checked @endif class="custom-control-input risk-4" id="stay_outbreak_n">
										<label for="stay_outbreak_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="stayFlu14Input" value="y" @if ($clinical['stay_flu14'] == 'y' || old('stayFlu14Input') == 'y') checked @endif class="custom-control-input risk-4" id="stay_outbreak_y">
										<label for="stay_outbreak_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr5">
								<td>ช่วง 14 วันก่อนป่วยได้ดูแลหรือสัมผัสใกล้ชิดกับผู้ป่วยอาการคล้ายไข้หวัดใหญ่/ปอดอักเสบ</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactFlu14Input" value="n" @if ($clinical['contact_flu14'] == 'n'|| old('contactFlu14Input') == 'n') checked @endif class="custom-control-input risk-5" id="close_up_n">
										<label for="close_up_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="contactFlu14Input" value="y" @if ($clinical['contact_flu14'] == 'y' || old('contactFlu14Input') == 'y') checked @endif class="custom-control-input risk-5" id="close_up_y">
										<label for="close_up_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr6">
								<td>ช่วง 14 วันก่อนป่วยไปเยี่ยมผู้ป่วยไข้หวัดใหญ่/ปอดอักเสบ</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="visitFlu14Input" value="n" @if ($clinical['visit_flu14'] == 'n' || old('visitFlu14Input') == 'n') checked @endif class="custom-control-input risk-6" id="patient_visit_n">
										<label for="patient_visit_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="visitFlu14Input" value="y" @if ($clinical['visit_flu14'] == 'y' || old('visitFlu14Input') == 'y') checked @endif class="custom-control-input risk-6" id="patient_visit_y">
										<label for="patient_visit_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr7">
								<td>เป็นบุคลากรทางการแพทย์และสาธารณสุขหรือเจ้าหน้าที่ห้องปฏิบัติการ</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="healthcareWorkerInput" value="n" @if ($clinical['health_care_worker'] =='n' || old('healthcareWorkerInput') == 'n') checked @endif class="custom-control-input risk-7" id="healthcare_n">
										<label for="healthcare_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="healthcareWorkerInput" value="y" @if ($clinical['health_care_worker'] == 'y' || old('healthcareWorkerInput') == 'y') checked @endif class="custom-control-input risk-7" id="healthcare_y">
										<label for="healthcare_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr8">
								<td>เป็นผู้ป่วยสงสัยไข้หวัดใหญ่/ปอดอักเสบ ที่เข้ารับการรักษาเป็นกลุ่มก้อน</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="suspectFluInput" value="n" @if ($clinical['suspect_flu'] == 'n' || old('suspectFluInput') == 'n') checked @endif class="custom-control-input risk-8" id="suspect_patient_n">
										<label for="suspect_patient_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="suspectFluInput" value="y" @if ($clinical['suspect_flu'] == 'y' || old('suspectFluInput') == 'y') checked @endif class="custom-control-input risk-8" id="suspect_patient_y">
										<label for="suspect_patient_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
							<tr id="risk_table_tr9">
								<td>
									<div class="form-group row mt-0 mb-0">
										<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
											<div class="input-group">
												<label for="other_risk" class="font-normal">อื่นๆ ระบุ</label>
												<input type="text" name="otherRiskInputSpecify" value="{{ old('otherRiskInputSpecify') ?? $clinical['other_risk_specify'] }}" class="form-control ml-2" id="other_risk_input" @if (empty(old('otherRiskInputSpecify')) && empty($clinical['other_risk_specify'])) disabled @else "" @endif style="width:400px;">
											</div>
										</div>
									</div>
								</td>
								<td class="text-danger">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="otherRiskInput" value="n" @if ($clinical['other_risk'] == 'n' || old('otherRiskInput') == 'n') checked @endif class="custom-control-input risk-9" id="other_risk_n">
										<label for="other_risk_n" class="custom-control-label normal-label">ไม่ใช่</label>
									</div>
								</td>
								<td class="text-success">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="otherRiskInput" value="y" @if ($clinical['other_risk'] == 'y' || old('otherRiskInput') == 'y') checked @endif class="custom-control-input risk-9" id="other_risk_y">
										<label for="other_risk_y" class="custom-control-label normal-label">ใช่</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
				<label for="treatment" class="text-info">ผลการรักษา</label>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="cured" @if ($clinical['result_cli'] == 'cured' || old('resultCliInput') == 'cured') checked @endif class="custom-control-input treatment-1" id="treatment_cured">
						<label for="treatment_cured" class="custom-control-label normal-label">หาย</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="treat" @if ($clinical['result_cli'] == 'treat' || old('resultCliInput') == 'treat') checked @endif class="custom-control-input treatment-1" id="treatment_treat">
						<label for="treatment_treat" class="custom-control-label normal-label">อยู่ระหว่างการรักษา</label>
					</div>
				</div>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="refer" @if ($clinical['result_cli'] == 'refer' || old('resultCliInput') == 'refer') checked @endif class="custom-control-input treatment-1" id="treatment_refer">
						<label for="treatment_refer" class="custom-control-label normal-label">ส่งต่อไปรักษา</label>
					</div>
				</div>
				<div class="form-group pt-2 pr-2 pb-0 pl-4">
					<label for="refer_place" class="text-info">ระบุสถานที่ส่งต่อ</label>
					<div class="input-group">
						<input type="text" name="resultCliReferInput" value="{{ old('resultCliReferInput') ?? $clinical['result_cli_refer'] }}" class="form-control form-control-sm ml-2" id="treatment_refer_at">
					</div>
				</div>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="dead" @if ($clinical['result_cli'] == 'dead' || old('resultCliInput') == 'dead') checked @endif class="custom-control-input treatment-1" id="treatment_dead">
						<label for="treatment_dead" class="custom-control-label normal-label">เสียชีวิต</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="unknown" @if ($clinical['result_cli'] == 'unknown' || old('resultCliInput') == 'unknown') checked @endif class="custom-control-input treatment-1" id="treatment_unknown">
						<label for="treatment_unknown" class="custom-control-label normal-label">ไม่ทราบ</label>
					</div>
				</div>
				<div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="resultCliInput" value="other" @if ($clinical['result_cli'] == 'other' || old('resultCliInput') == 'other') checked @endif class="custom-control-input treatment-1" id="treatment_other">
						<label for="treatment_other" class="custom-control-label normal-label">อื่นๆ</label>
					</div>
					<div class="form-group pt-2 pr-2 pb-0 pl-4">
						<label for="refer_place" class="text-info">โปรดระบุ</label>
						<div class="input-group">
							<input type="text" name="resultOtherCliInput" value="{{ old('resultOtherCliInput') ?? $clinical['result_cli_other'] }}" class="form-control form-control-sm ml-2" id="treatment_other_txt">
						</div>
					</div>
				</div>
			</div>
			{{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="form-group">
					<label for="refer_place" class="text-info">กรณีส่งต่อไปรักษา โปรดระบุสถานที่ส่งต่อ</label>
					<div class="input-group">
						<input type="text" name="resultCliReferInput" value="{{ old('resultCliReferInput') ?? $clinical['result_cli_refer'] }}" class="form-control form-control-sm ml-2" id="treatment_refer_at">
					</div>
				</div>
			</div> --}}
		</div>
	</div><!-- card body -->
</div><!-- card -->
