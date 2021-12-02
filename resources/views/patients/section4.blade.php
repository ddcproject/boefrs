<div class="card">
	<div class="card-body">
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
				<label for="reporter">ผู้รายงาน</label>
				<input type="hidden" name="userIdInput" value="{{ auth()->user()->id }}">
				<input type="text" name="userInput" value="{{ auth()->user()->name . ' ' . auth()->user()->lastname }}" class="form-control" id="first_name_input" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
				<label for="user_phone">โทรศัพท์</label>
				<input type="text" name="userPhoneInput" value="{{ auth()->user()->phone }}" class="form-control" placeholder="โทรศัพท์" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
				<label for="report_date">วันที่รายงาน</label>
				<div class="input-group date">
					<div class="input-group-append">
						<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
					</div>
					<input type="text" name="reportDateInput" class="form-control" data-provide="datepicker" id="report_date" readonly>
					<div class="input-group-append">
						<button type="button" class="input-group-text text-danger" id="cls_reportDateInput"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
				<label for="user_hospital">หน่วยงาน/โรงพยาบาล</label>
				@role('admin')
					<input type="text" name="userHospitalInput" value="{{ $user_hospital[0]->hosp_name }}" class="form-control" readonly>
				@endrole
				@role('hospital')
					<div class="box" style="background:#E9ECEF;height:36px;">{{ Session::get('user_hospital_name') }}</div>
					<input type="hidden" name="userHospitalInput" value="{{ $user_hospital[0]->hosp_name }}" class="form-control" readonly>
				@endrole
			</div>
		</div>
	</div><!-- card body -->
</div><!-- card -->
