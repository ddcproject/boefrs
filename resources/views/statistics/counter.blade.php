@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css') }}">
@endsection
@section('internal-style')
<style>
.page-wrapper {
	background: #fff !important;
}
canvas {
	-moz-user-select: none;
	-webkit-user-select: none;
	-ms-user-select: none;
}
</style>
@endsection
@section('top-script')
<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/chart.js/Chart.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/chart.js/utils.js') }}"></script>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
<div class="page-breadcrumb bg-light pb-2">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title">Statistics (Start: 13/07/2020)</h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Statistics</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-3">
					<div class="bg-primary p-10 text-white text-center">
						<i class="mdi mdi-account m-b-5 font-24"></i>
						<h3 class="m-b-0 m-t-5">{{ $counter['cntToday'] }}</h3>
						<h5 class="font-light">Today</h5>
					</div>
				</div>
				<div class="col-3">
					<div class="bg-cyan p-10 text-white text-center">
						<i class="mdi mdi-account m-b-5 font-24"></i>
						<h3 class="m-b-0 m-t-5">{{ $counter['cntYesterday'] }}</h3>
						<h5 class="font-light">Yesterday</h5>
					</div>
				</div>
				<div class="col-3">
					<div class="bg-danger p-10 text-white text-center">
						<i class="mdi mdi-account m-b-5 font-24"></i>
						<h3 class="m-b-0 m-t-5">{{ ((int)$counter['cntThisMonth']+(int)$counter['cntToday']) }}</h3>
						<h5 class="font-light">This Month</h5>
					</div>
				</div>
				<div class="col-3">
					<div class="bg-success p-10 text-white text-center">
						<i class="mdi mdi-account m-b-5 font-24"></i>
						<h3 class="m-b-0 m-t-5">{{ ((int)$counter['cntThisYear']+(int)$counter['cntToday']) }}</h3>
						<h5 class="font-light">This Year</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 mt-4">
			<div id="container" style="width: 100%; height: 500px">
				<canvas id="canvas"></canvas>
			</div>
		</div>
	</div>
</div>
@endsection
@section('bottom-script')
	<script>
		var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
			datasets: [{
				label: 'ครั้ง',
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [
					@foreach ($monthChart as $key => $value)
						{{ $value.","}}
					@endforeach
				]
			}]

		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						fontSize: 16,
						text: 'แผนภูมิแสดงผู้ใช้งานรายเดือน ปี {{ (int)date('Y')+543 }}'
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero:true,
								precision: 0,
								max: {{ (max($monthChart) + 2) }},
							},
							scaleLabel: {
								display: true,
								labelString: 'จำนวน'
							}
						}]
					},
					responsive: true,
					maintainAspectRatio: false,
				}
			});

		};
	</script>
@endsection
