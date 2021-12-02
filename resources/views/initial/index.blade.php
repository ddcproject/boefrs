<?php
use App\HelperClass\Helper as CmsHelper;
?>
@extends('layouts.index')
@section('internal-style')
<style>
.page-wrapper {
	background: #fff !important;
}
#chartContainer_Sex canvas,
#chartContainer_Nation canvas,
#chartContainer_Age_Group canvas,
#chartContainer_month_median canvas,
#chartContainer_month_median canvas,
#chartContainer_week_median canvas {
	width: 100% !important;
}
</style>
@endsection
@section('top-script')
	<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!-- Charts js Files -->
	{{ Html::script('assets/libs/flot/excanvas.js') }}
	{{ Html::script('assets/libs/flot/jquery.flot.js') }}
	{{ Html::script('assets/libs/flot/jquery.flot.pie.js') }}
	{{ Html::script('assets/libs/flot/jquery.flot.time.js') }}
	{{ Html::script('assets/libs/flot/jquery.flot.stack.js') }}
	{{ Html::script('assets/libs/flot/jquery.flot.crosshair.js') }}
	{{ Html::script('assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}
	{{ Html::script('dist/js/pages/chart/chart-page-init.js') }}
	{{ Html::script('assets/extra-libs/chart.js/Chart.min.js') }}
	{{ Html::script('assets/extra-libs/chart.js/utils.js') }}
	{{ Html::script('assets/libs/canvas-js/canvasjs.min.js') }}
@endsection
@section('contents')
<div class="page-breadcrumb bg-light pb-2">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 d-flex no-block align-items-center">
				<h4 class="page-title d-none">Dashboard</h4>
				<div class="ml-auto text-right">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb d-none">
							<li class="breadcrumb-item"><a href="{{ route('init') }}">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div id="ajax_response">
		<div class="row mb-4">
			<div class="col-lg-12">
				<div class="row" style="font-family:sukhumvit;">
					<div class="col-3">
						<div class="bg-info p-10 text-white text-center">
							<h2 class="font-light">ข้อมูลทั้งหมด</h2>
							<h3 class="m-b-0 m-t-5">{{ number_format($case_all) }}</h3>
							<!--<i class="fas fa-flask m-b-5 font-24"></i>-->
						</div>
					</div>
					<div class="col-3">
						<div class="bg-cyan p-10 text-white text-center">
							<h2 class="font-light">ข้อมูลใหม่</h2>
							<h3 class="m-b-0 m-t-5">{{ number_format($case_gen_code) }}</h3>
							<!--<i class="fab fa-odnoklassniki m-b-5 font-24"></i>-->
						</div>
					</div>
					<div class="col-3">
						<div class="bg-danger p-10 text-white text-center">
							<h2 class="font-light">รอผลแลป</h2>
							<h3 class="m-b-0 m-t-5">{{ number_format($case_hos_send) }}</h3>
							<!--<i class="fab fa-odnoklassniki m-b-5 font-24"></i>-->
						</div>
					</div>
					<div class="col-3">
						<div class="bg-success p-10 text-white text-center">
							<h2 class="font-light">เสร็จสิ้น</h2>
							<h3 class="m-b-0 m-t-5">{{ number_format($case_lab_confirm) }}</h3>
							<!--<i class="far fa-check-circle m-b-5 font-24"></i>-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-6">
				<div class="card border">
					<div class="card-body">
						<h5 class="card-title d-none">Sex Group</h5>
						<div id="chartContainer_Sex" style="height: 360px; width: 100%;"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card border">
					<div class="card-body">
						<h5 class="card-title d-none">Nation</h5>
						<div id="chartContainer_Nation" style="height: 360px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card border">
					<div class="card-body">
						<h5 class="card-title d-none">Age Group</h5>
							<div id="chartContainer_Age_Group" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card border">
					<div class="card-body">
						<h5 class="card-title d-none">Mohth Median</h5>
							<div id="chartContainer_month_median" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="card border">
					<div class="card-body">
						<h5 class="card-title d-none">Week Median</h5>
							<div id="chartContainer_week_median" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card border">
					<div class="card-body">
						<div style="width: 100%">
							<canvas id="canvas_chart_5l"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js') }}"></script>
<script>
window.onload = function() {
	/* donough chart pat */
	var chart1 = new CanvasJS.Chart("chartContainer_Sex", {
		theme: "light1",
		animationEnabled: true,
		//exportEnabled: true,
		title: {
			text: "แผนภูมิร้อยละผู้ป่วยจำแนกตามเพศ",
			fontSize: 20,
			fontFamily: "Prompt",
		},
		data: [{
			type: "doughnut",
			indexLabel: "{symbol} - {y}%",
			//yValueFormatString: "#,##0.0\"%\"",
			showInLegend: true,
			legendText: "{label} : {y}%",
			toolTipContent: "{y}%",
			dataPoints: <?php echo json_encode($donut_charts_sex_arr, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart1.render();


	var chart2 = new CanvasJS.Chart("chartContainer_Age_Group", {
			animationEnabled: true,
			//exportEnabled: true,
			title: {
				text: "แผนภูมิอัตราป่วยแยกตามกลุ่มอายุ",
				fontSize: 20,
				fontFamily: "Prompt",
			},
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			axisX:{
			  labelAngle: 150,
		    labelFontSize: 10,
		    labelWrap: true,
		    labelAutoFit: true,
				interval:1,
  		},
			data: [{
				type: "column", //change type to bar, line, area, pie, etc
				indexLabelPlacement: "auto",
				//indexLabelFontColor: "black",
				indexLabel: "{y}",
				dataPoints: <?php echo json_encode($line_charts_age_group_arr, JSON_NUMERIC_CHECK); ?>
			}]
		});
chart2.render();

	var chart3 = new CanvasJS.Chart("chartContainer_Nation", {
			animationEnabled: true,
			//exportEnabled: true,
			title: {
				text: "แผนภูมิร้อยละผู้ป่วยแยกตามสัญชาติ",
				fontSize: 20,
				fontFamily: "Prompt",
			},
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			axisX:{
				interval:1,
				labelAngle: 150,
				labelFontSize: 12,
				labelWrap: true,
				labelAutoFit: true
			},
			axisY: {
				suffix: "%",
			},
			data: [{
				type: "column", //change type to bar, line, area, pie, etc
				indexLabelPlacement: "auto",
				indexLabelFontColor: "black",
				indexLabel: "{symbol} {y}",
				yValueFormatString: "#0.00'%'",
				dataPoints: <?php echo json_encode($line_charts_nation_group_arr, JSON_NUMERIC_CHECK); ?>
			}]
		});
	chart3.render();


	var chart4 = new CanvasJS.Chart("chartContainer_month_median", {
	animationEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "กราฟแสดงจำนวนผู้ป่วยรายเดือนเทียบกับค่ามัธยฐาน",
		fontSize: 20,
		fontFamily: "Prompt",
	},
	legend:{
		cursor: "pointer",
		fontSize: 16,
		itemclick: toggleDataSeries
	},
	toolTip:{
		shared: true
	},
	axisX:{
		labelAngle: 150,
		labelFontSize: 12,
		interval:1,
	},
	data: [{
		name: "มัธยฐาน 5 ปีย้อนหลัง",
		type: "line",
    connectNullData : true,
  	showInLegend: true,
    color: '#F26B70',
    lineColor:"#F26B70",
    markerType: "circle",
    markerColor: "#F26B70",
    lineDashType: "dash",
    lineThickness: 3,
    markerSize: 10,
		showInLegend: true,
    legendMarkerColor: "#F26B70",
		dataPoints: <?php echo json_encode($data_three_year_median, JSON_NUMERIC_CHECK); ?>
	},
	{
		name: "ปี {{ date('Y')+543 }} ",
		type: "line",
    connectNullData : true,
		showInLegend: true,
    color: '#6A6C68',
    lineColor:"#6A6C68",
    markerType: "square",
    markerColor: "#6A6C68",
    lineDashType: "solid",
    lineThickness: 3,
    markerSize: 10,
    indexLabelMaxWidth: 50,
		showInLegend: true,
		dataPoints: <?php echo json_encode($result_data_now_year_median, JSON_NUMERIC_CHECK); ?>
	}]
});
chart4.render();

var chart5 = new CanvasJS.Chart("chartContainer_week_median", {
animationEnabled: true,
theme: "light1", // "light1", "light2", "dark1", "dark2"
title:{
	text: "กราฟแสดงจำนวนผู้ป่วยรายสัปดาห์เทียบกับค่ามัธยฐาน",
	fontSize: 20,
	fontFamily: "Prompt",
},
legend:{
	cursor: "pointer",
	fontSize: 16,
	itemclick: toggleDataSeries
},
toolTip:{
	shared: true
},
axisX:{
	labelAngle: 90,
	labelFontSize: 12,
	interval:1,
},
data: [{
	name: "มัธยฐาน 5 ปีย้อนหลัง",
	type: "line",
	connectNullData : true,
	showInLegend: true,
	color: '#F26B70',
	lineColor:"#F26B70",
	markerType: "circle",
	markerColor: "#F26B70",
	lineDashType: "dash",
	lineThickness: 3,
	markerSize: 10,
	showInLegend: true,
	legendMarkerColor: "#F26B70",
	dataPoints: <?php echo json_encode($data_week_median, JSON_NUMERIC_CHECK); ?>
},
{
	name: "ปี {{ date('Y')+543 }} ",
	type: "line",
	connectNullData : true,
	showInLegend: true,
	color: '#6A6C68',
	lineColor:"#6A6C68",
	markerType: "square",
	markerColor: "#6A6C68",
	lineDashType: "solid",
	lineThickness: 3,
	markerSize: 10,
	indexLabelMaxWidth: 50,
	showInLegend: true,
	dataPoints: <?php echo json_encode($result_data_now_week_median, JSON_NUMERIC_CHECK); ?>
}]
});
chart5.render();

function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart4.render();
	chart5.render();
}

	var barChartData = {
		labels: <?php echo json_encode($rp_week_length, JSON_NUMERIC_CHECK);?>,
		datasets: [{
				label: 'Flu Positive',
				yAxisID: 'P',
				fill: false,
				borderColor: window.chartColors.blue,
				borderWidth: 2,
				data: <?php echo json_encode($result_sum_flu_positive_data_now_week, JSON_NUMERIC_CHECK);?>,
				type: 'line'
		}, {
			label: 'A/H1',
			backgroundColor: window.chartColors.yellow,
			data: <?php echo json_encode($result_sum_flu_a_data_now_week, JSON_NUMERIC_CHECK);?>,
		}, {
			label: 'A/H3',
			backgroundColor: window.chartColors.red,
			data: <?php echo json_encode($result_sum_flu_a_h3_data_now_week, JSON_NUMERIC_CHECK);?>,
		}, {
			label: 'B',
			backgroundColor: window.chartColors.green,
			data: <?php echo json_encode($result_sum_flu_b_data_now_week, JSON_NUMERIC_CHECK);?>,
		}, {
			label: 'Nagative',
			backgroundColor: window.chartColors.gray,
			data: <?php echo json_encode($result_sum_negative_data_now_week, JSON_NUMERIC_CHECK);?>,
		}]
	};

	var ctx = document.getElementById('canvas_chart_5l').getContext('2d');
	window.myBar = new Chart(ctx, {
		type: 'bar',
		data: barChartData,
		options: {
			title: {
				display: true,
				text: 'จำนวนตัวอย่างที่ส่งตรวจ จำแนกตามผลการตรวจทางห้องปฏิบัติการ รายสัปดาห์ ตั้งแต่วันที่ <?php echo CmsHelper::DateThai(date('2020-01-01'));?> ถึงวันที่ <?php echo CmsHelper::DateThai(date('Y-m-d')); ?>'
			},
			legend: {
				position: 'bottom'
			},
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true,
			scales: {
				xAxes: [{
					stacked: true,
					/*
					ticks: {
						autoSkip: true,
						maxRotation: 0,
						minRotation: 0
					} */
				}],
				yAxes: [{
					stacked: true,
					scaleLabel: {
						display: true,
						labelString: 'จำนวนตัวอย่าง'
					}
				}, {
					id: 'P',
					position: 'right',
					ticks: {
						max: 100,
						min: 1
					},
					scaleLabel: {
						display: true,
						labelString: 'ร้อยละที่ให้ผลบวก'
					}
				}]
			}
		}
	});


}
</script>
@endsection
