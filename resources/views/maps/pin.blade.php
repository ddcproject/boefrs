@extends('layouts.index')
@section('custom-style')
<link href="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/v1.11.1/mapbox-gl.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/assembly/assembly-v0.23.2.min.css') }}" rel="stylesheet">
@endsection
@section('internal-style')
<style>
.topbar {
	z-index: 10001 !important;
}
.left-sidebar {
	z-index: 10000 !important;
}
.page-wrapper {
	background: white !important;
}
.map-box {
	margin: 0;
	padding: 0;
	position:relative;
}
/*#map { position: absolute; top: 0; bottom: 0; width: 100%; }*/
#map {
	position:absolute;
	top: 0;
	right: 0;
	width:  100vw;
	height: 100vh;
}
.legend {
	position: absolute;
	top: 76vh;
	left: 10px;
	min-width: 150px;
	background-color: #fff;
	border-radius: 3px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
	font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
	padding: 10px;
	z-index: 1;
}
.legend h4 {
	font-size: 1em;
	margin: 0 0 10px;
	line-height: 1.475em;
	border-bottom: 1px solid #eeeeee;
}
.legend div span {
	border-radius: 50%;
	display: inline-block;
	height: 10px;
	margin-right: 5px;
	width: 10px;
}
.mapboxgl-popup {
	max-width: 400px;
	font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
}
.marker {
	background-image: url("{{ URL::asset('assets/images/mapbox-icon.png') }}");
	background-size: cover;
	width: 50px;
	height: 50px;
	border-radius: 50%;
	cursor: pointer;
}
.mapboxgl-popup-content {
	min-width: 250px;
}
.mapboxgl-poupu-content-wrapper {
	padding: 1%;
}
</style>
@endsection
@section('contents')
<div style="margin:0;padding:0;height:100vh;">
	<div id="map"></div>
	<div id="image"></div>
	<div id="state-legend" class="legend">
		<h4>ตำแหน่ง</h4>
		<div><span style="background-color: #ff6384"></span>โรงพยาบาล</div>
	</div>
</div>
@endsection
@section('bottom-script')
<script src="{{ asset('assets/libs/mapbox-plugins/mapbox-gl-js/v1.11.1/mapbox-gl.js') }}"></script>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiZGFsb3JrZWUiLCJhIjoiY2pnbmJrajh4MDZ6aTM0cXZkNDQ0MzI5cCJ9.C2REqhILLm2HKIQSn9Wc0A';
var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/streets-v11',
	center: [ 103.511621, 12.538136 ],
	zoom: 5.2,
	preserveDrawingBuffer: true
});

map.on('load', function() {
	map.loadImage('{{ URL::asset("assets/images/custom-marker.png") }}', function(error, image) {
		if (error) throw error;
		map.addImage('custom-marker', image);
		map.addSource('places', {
			'type': 'geojson',
			'data': {
				'type': 'FeatureCollection',
				'features': [
					@foreach ($marker_map as $key => $value)
						@php
							$pc_b = (($value->b/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
							$pc_flu_a = (($value->flu_a/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
							$pc_flu_h = (($value->flu_h/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
							$pc_neg = (($value->neg/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
							$desc = "<div class=\"card\">";
								$desc .= "<div class=\"card-body\">";
									$desc .= "<h4 class=\"card-title m-b-0 border-bottom\"><i class=\"mdi mdi-hospital-marker\"></i> ".$hosp_name[$value->hoscode]."</h4>";
									$desc .= "<div class=\"m-t-20\">";
										$desc .= "<div class=\"d-flex no-block align-items-center\">";
											$desc .= "<span>B, ".number_format($pc_b, 2)."% </span>";
										$desc .= "<div class=\"ml-auto\"><span>".number_format($value->b)."</span></div>";
									$desc .= "</div>";
									$desc .= "<div class=\"progress\">";
										$desc .= "<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width:".$pc_b."%\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
									$desc .= "</div>";
								$desc .= "</div>";
								$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
									$desc .= "<span>Flu A, ".number_format($pc_flu_a, 2)."%</span>";
									$desc .= "<div class=\"ml-auto\"><span>".number_format($value->flu_a)."</span></div>";
								$desc .= "</div>";
								$desc .= "<div class=\"progress\">";
									$desc .= "<div class=\"progress-bar progress-bar-striped bg-danger\" role=\"progressbar\" style=\"width:".$pc_flu_a."%\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
								$desc .= "</div>";
								$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
									$desc .= "<span>Flu H, ".number_format($pc_flu_h, 2)."%</span>";
									$desc .= "<div class=\"ml-auto\"><span>".number_format($value->flu_h)."</span></div>";
								$desc .= "</div>";
								$desc .= "<div class=\"progress\">";
									$desc .= "<div class=\"progress-bar progress-bar-striped bg-info\" role=\"progressbar\" style=\"width:".$pc_flu_h."%\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
								$desc .= "</div>";
								$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
									$desc .= "<span>Nagative, ".number_format($pc_neg, 2)."%</span>";
									$desc .= "<div class=\"ml-auto\"><span>".number_format($value->neg)."</span></div>";
									$desc .= "</div>";
									$desc .= "<div class=\"progress\">";
										$desc .= "<div class=\"progress-bar progress-bar-striped bg-success\" role=\"progressbar\" style=\"width:".$pc_neg."%\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
									$desc .= "</div>";
								$desc .= "</div>";
							$desc .= "</div>";
						@endphp
						{
							'type': 'Feature',
							'properties': {
								'description': '{!!$desc!!}',
								'icon': 'theatre'
							},
							'geometry': {
								'type': 'Point',
								'coordinates': [{{ $value->lon }} , {{ $value->lat }}]
							}
						},
						@endforeach
					]
				}
			});

		map.addLayer({
			'id': 'places',
			'type': 'symbol',
			'source': 'places',
			'layout': {
				'icon-image': 'custom-marker',
				//'icon-image': '{icon}-15',
				'icon-allow-overlap': true
			}
		});


		// Add zoom and rotation controls to the map.
		map.addControl(new mapboxgl.NavigationControl());

		// When a click event occurs on a feature in the places layer, open a popup at the
		// location of the feature, with description HTML from its properties.
		map.on('click', 'places', function(e) {
			var coordinates = e.features[0].geometry.coordinates.slice();
			var description = e.features[0].properties.description;

			// Ensure that if the map is zoomed out such that multiple
			// copies of the feature are visible, the popup appears
			// over the copy being pointed to.
			while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
			coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
		}

		new mapboxgl.Popup()
			.setLngLat(coordinates)
			.setHTML(description)
			.addTo(map);
		});

		// Change the cursor to a pointer when the mouse is over the places layer.
		map.on('mouseenter', 'places', function() {
			map.getCanvas().style.cursor = 'pointer';
		});

		// Change it back to a pointer when it leaves.
		map.on('mouseleave', 'places', function() {
			map.getCanvas().style.cursor = '';
		});
	});

	$('#downloadLink').click(function() {
	var img = map.getCanvas().toDataURL('image/png')
	this.href = img
})
});
</script>
@endsection
