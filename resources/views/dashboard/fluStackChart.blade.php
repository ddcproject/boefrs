@section('internal-style')
<style>
canvas {

	-moz-user-select: none;
	-webkit-user-select: none;
	-ms-user-select: none;

}
</style>
@endsection
<div style="width: 90%">
	<canvas id="canvas_chart_5l"></canvas>
</div>
@section('bottom-script')
	<script>
		var barChartData = {
			labels: [
				'1',
				'3',
				'5',
				'7',
				'9',
				'11',
				'13',
				'15',
				'17',
				'19',
				'21',
				'23',
				'25',
				'27',
				'29',
				'31',
				'33',
				'35',
				'37',
				'39',
				'41',
				'43',
				'45',
				'47',
				'49',
				'51',
				'1',
				'3',
				'5',
				'7',
				'9',
				'11',
				'13',
				'15',
				'17',
				'19',
				'21',
				'23',
				'25',
				'27',
				'29',
				'31',
				'33',
				'35',
				'37',
				'39',
				'41',
				'43',
				'45',
				'47',
				'49',
				'51'
			],
			datasets: [{
					label: 'Flu Positive',
					yAxisID: 'P',
					fill: false,
					borderColor: window.chartColors.blue,
					borderWidth: 2,
					data: [
						90,
						51,
						66,
						42,
						7,
						9,
						95,
						21,
						33,
						65,
						78,
						21,
						24,
						65,
						23,
						67,
						11,
						62,
						43,
						54,
						76,
						56,
						23,
						67,
						65,
						98,
						30,
						51,
						66,
						42,
						7,
						9,
						95,
						21,
						33,
						65,
						78,
						21,
						24,
						65,
						23,
						67,
						11,
						62,
						43,
						54,
						76,
						56,
						23,
						67,
						65,
						98
					],
					type: 'line'
			}, {
				label: 'A/H1 2009',
				backgroundColor: window.chartColors.yellow,
				data: [
					3,
					10,
					17,
					4,
					9,
					5,
					23,
					3,
					35,
					21,
					26,
					3,
					6,
					8,
					23,
					6,
					18,
					14,
					4,
					26,
					22,
					14,
					25,
					26,
					15,
					2,
					10,
					31,
					17,
					4,
					19,
					25,
					13,
					23,
					35,
					21,
					26,
					13,
					6,
					8,
					3,
					5,
					8,
					9,
					34,
					6,
					12,
					24,
					15,
					26,
					15,
					22
				]
			}, {
				label: 'A/H3',
				backgroundColor: window.chartColors.red,
				data: [
					5,
					3,
					21,
					8,
					7,
					32,
					45,
					16,
					21,
					23,
					14,
					15,
					21,
					14,
					27,
					32,
					14,
					18,
					19,
					16,
					11,
					14,
					16,
					8,
					18,
					20,
					14,
					23,
					21,
					8,
					16,
					32,
					45,
					26,
					21,
					23,
					54,
					35,
					21,
					34,
					27,
					32,
					34,
					18,
					9,
					16,
					21,
					14,
					26,
					18,
					28,
					10
				]
			}, {
				label: 'B',
				backgroundColor: window.chartColors.green,
				data: [
					45,
					21,
					35,
					40,
					43,
					78,
					32,
					98,
					34,
					56,
					21,
					77,
					23,
					54,
					3,
					21,
					12,
					22,
					34,
					56,
					77,
					12,
					77,
					99,
					32,
					10,
					45,
					21,
					35,
					40,
					43,
					78,
					32,
					98,
					34,
					56,
					21,
					77,
					23,
					54,
					3,
					21,
					12,
					22,
					34,
					56,
					77,
					12,
					77,
					99,
					32,
					10
				]
			}, {
				label: 'Nagative',
				backgroundColor: window.chartColors.gray,
				data: [
					30,
					51,
					66,
					42,
					7,
					9,
					95,
					21,
					33,
					65,
					78,
					21,
					24,
					65,
					23,
					67,
					11,
					62,
					43,
					54,
					76,
					56,
					23,
					67,
					65,
					98,
					30,
					51,
					66,
					42,
					7,
					9,
					95,
					21,
					33,
					65,
					78,
					21,
					24,
					65,
					23,
					67,
					11,
					62,
					43,
					54,
					76,
					56,
					23,
					67,
					65,
					98
				]
			}]
		};
		window.onload = function() {
			var ctx = document.getElementById('canvas_chart_5l').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					title: {
						display: true,
						text: 'จำนวนตัวอย่างที่ส่งตรวจ จำแนกตามผลการตรวจทางห้องปฏิบัติการ รายสัปดาห์ ตั้งแต่วันที่ 1 มกราคม 2562 ถึงวันที่ 10 สิงหาคม 2562'
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
		};
/*
		document.getElementById('randomizeData').addEventListener('click', function() {
			barChartData.datasets.forEach(function(dataset) {
				dataset.data = dataset.data.map(function() {
					return randomScalingFactor();
				});
			});
			window.myBar.update();
		});
		*/
	</script>
@endsection
