@extends('layouts.index')
@section('custom-style')
<link href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/date-range-picker/daterangepicker.css') }}" rel="stylesheet">
<link href="{{ URL::asset('fonts/fontawesome-free-5.13.0-web/css/fontawesome.min.css') }}" rel="stylesheet">
@endsection
@section('internal-style')
<style type="text/css">
.page-wrapper {
	background: white !important;
}
.dataTables_wrapper {
	width: 100% !important;
	font-family: 'Fira-code' !important;
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
			<h4 class="page-title"><span style="display:none;">Exports</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">export</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
@if(Session::has('success'))
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="alert alert-success">
				<i class="fas fa-check-circle"></i> {{ Session::get('success') }}
				@php
					Session::forget('success');
				@endphp
			</div>
		</div>
	</div>
@elseif(Session::has('error'))
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="alert alert-danger">
				<i class="fas fa-times-circle"></i> {{ Session::get('error') }}
				@php
					Session::forget('error');
				@endphp
			</div>
		</div>
	</div>
@endif
@if(count($errors) > 0)
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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

			<article class="card" style="border:2px solid #eee">
				<section class="card-body">
					<form action="#" method="POST" enctype="multipart/form-data" class="form-horizontal">
					<!--<form action="{ route('export.search') }}" method="POST" enctype="multipart/form-data" class="form-horizontal"> -->
						{{ csrf_field() }}
						<div class="form-row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group">
									<label for="date">เลือกช่วงเวลาที่ต้องการส่งออกข้อมูล <span class="text-danger">(ไม่ควรเกิน 1 เดือน/ครั้ง)</span></label>
									<div class="input-group date" data-provide="datepicker" id="breathing_tube_date">
										<div class="input-group-append">
											<span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
										</div>
										<input type="text" name="date_range" id="export_date" class="form-control" style="cursor: pointer;" readonly>
										<div class="input-group-append">
											<button type="button" class="btn btn-outline btn-primary" id="export_btn">ค้นหา</button>
											<!--<button type="submit">isad</button> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="form-row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div class="card">
								<div class="card-body">
									<ul class="chat-list loader" style="display:none;">
										<li class="chat-item">
											<div class="chat-img text-danger" style="font-size:2em;">
												<i class="fas fa-spinner fa-spin"></i>
											</div>
											<div class="chat-content">
												<h2 class="text-danger">กำลังเขียนข้อมูล โปรดรอให้ข้อความนี้หายไป...</h2>
												<div class="box text-info">ข้อมูลจำนวนมาก อาจใช้เวลานานหลายนาที</div>
											</div>
										</li>
									</ul>
									<div class="dl-section">
										<div id="dl-detail"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</article>

		</div>
	</div>
</div>
@endsection
@section('bottom-script')
<script type="text/javascript" src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/libs/date-range-picker/moment-2.18.1.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/libs/date-range-picker/daterangepicker.min.js') }}"></script>
<script>
$(document).ready(function() {
	$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
	var currentdate = new Date();
	var startDate =  (currentdate.getMonth()+1) + "/" + (currentdate.getDate()-7) +  "/" + currentdate.getFullYear();
	var endDate =  (currentdate.getMonth()+1) + "/" +  currentdate.getDate() + "/" + currentdate.getFullYear();

	/* date range */
	$('#export_date').daterangepicker({
		"minYear": 2019,
		"maxYear": 2023,
		"maxSpan": {
			"days": 31
		},
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		"alwaysShowCalendars": true,
		"startDate": startDate,
		"endDate": endDate,
		"cancelClass": "btn-danger"
	}, function(start, end, label) {
		console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
	});

	$('.dl-section').hide();
	$('#warning_msg').modal('show');

	$('#export_btn').click(function(e) {
		try {
			e.preventDefault();
			$('.loader').show();
			$('.dl-section').hide();
			var date_range = $('#export_date').val();
			var pt_status = $('#pt_status').val();
			$.ajax({
				method: 'POST',
				url: "{{ route('export.search') }}",
				data: {date_range:date_range, pt_status:pt_status},
				dataType: "HTML",
				success: function(response) {
					$('.loader').hide();
					$('.dl-section').show();
					$('#dl-detail').html(response);
				},
				error: function(xhr) {
					alert(xhr.errorMessage + xhr.status);
				}
			});
		} catch(err) {
			alert(err.message);
		}
	});

});
</script>
@endsection
