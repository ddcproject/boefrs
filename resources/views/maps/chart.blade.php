@extends('layouts.index')
@section('custom-style')
<link href="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/v1.11.1/mapbox-gl.css') }}" rel='stylesheet'>
<link href="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/assembly/assembly-v0.23.2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/mapbox-plugins/animate.min.css') }}" rel="stylesheet">
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
/* #map {position: absolute; top:0; bottom:0; width: 100%;} */
#map {
	position:absolute;
	top: 0;
	right: 0;
	width:  100vw;
	height: 100vh;
}
#map:after {
	content: '';
	display: block;
	clear: both;
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
	min-width: 400px;
	font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
	z-index: 99999;
}
.mapboxgl-popup-content-wrapper {
	padding: 1%;
}
.marker {
	height: 15px;
	width: 15px;
	background-color: #CB98FF;
	border-radius: 50%;
	display: inline-block;
	cursor: pointer;
	z-index: 2;
}
.marker b {transform: rotateZ(135deg)}
.dom-popup {
	border-radius: 4px;
	/*animation: gen .8s ease-in-out;*/
	border: 1px solid rgba(197, 197, 197, 0.44);
}
.dom-popup-gen {
	animation: gen .8s forwards;
}
/*
@keyframes gen {
	from {transform: scale(0);}
	to {transform: scale(1);}
}
*/
.dom-ele:hover, .dom-popup:hover {
	box-shadow: 0px 0px 2px rgba(255, 255, 255, 0.8);
}
.dom-ele {
	background-color: #CB98FF;
	/*animation: expandLine 1.2s ease-in-out;*/
}
/*@keyframes expand {
	0% {width: 0; height: 0;}
	100% {width: 100%; height: 50px;}
}
@keyframes expandLine {
	from {height: 0;}
	to {height: 100%;}
}*/
.mapboxgl-ctrl-pitchtoggle-3d {
	background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCI+ICAgIDx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkeT0iLjM1ZW0iIHN0eWxlPSJmb250LXNpemU6IDE0cHg7IGZvbnQtZmFtaWx5OiAnSGVsdmV0aWNhIE5ldWUnLEFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmOyBmb250LXdlaWdodDogYm9sZDsgdGV4dC1hbmNob3I6IG1pZGRsZTsiPjNEPC90ZXh0Pjwvc3ZnPg==);
}
.mapboxgl-ctrl-pitchtoggle-2d {
	background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCI+ICAgIDx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkeT0iLjM1ZW0iIHN0eWxlPSJmb250LXNpemU6IDE0cHg7IGZvbnQtZmFtaWx5OiAnSGVsdmV0aWNhIE5ldWUnLEFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmOyBmb250LXdlaWdodDogYm9sZDsgdGV4dC1hbmNob3I6IG1pZGRsZTsiPjJEPC90ZXh0Pjwvc3ZnPg==);
}
.mapbox-gl-draw_point {
	background-repeat: no-repeat;
	background-position: center;
	pointer-events: auto;
	background-size: 16px, 16px;
	background-image: url("{{ URL::asset('assets/images/download.png') }}");
}
#remover {
	margin: 0 auto;
	/*background-color: #fb5b3f;
	color: #fff;
	font-weight: bold;*/
	padding: 0.5em;
	border: 2px solid #fff;
	border-radius: 0.5em;
	box-shadow: 0px 0px 5px rgba(0,0,0,0.2);
	cursor: pointer;
	position: absolute;
	left: 0.7em;
	top: 0.7em;

	width: 32px;
	height: 32px;
	background-repeat: no-repeat;
	background-position: center;
	pointer-events: auto;
	background-size: 24px, 24px;
	background-image: url("{{ URL::asset('assets/images/satellite.png') }}");
}
#remover:hover {
	/*background-color: salmon;*/
	background-color: #8DD394;
}
.on {
	background-color: #B8E3AE;
}

</style>
@endsection
@section('top-script')
<script src="{{ URL::asset('assets/libs/html2canvas.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/canvas2image.js') }}"></script>
<script src="{{ URL::asset('assets/libs/dom-to-image.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/FileSaver.min.js') }}"></script>
@endsection
@section('contents')
<div style="margin:0;padding:0;height:100vh;" id="wp">
	<div class="map-box">
		<div id="map"></div>
		<div id="state-legend" class="legend">
			<h4>Pathogen</h4>
			<div><span style="background-color: #ff6384"></span>B</div>
			<div><span style="background-color: #FFB447"></span>Flu A</div>
			<div><span style="background-color: #36a2eb"></span>Flu H</div>
			<div><span style="background-color: #77DD77"></span>Nagative</div>
		</div>
		<div id='remover'></div>
	</div>
</div>
<div id="ccv"></div>
<div id="img"></div>

@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/v1.11.1/mapbox-gl.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/chart.js/Chart.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/mapbox-plugins/bundle.js') }}"></script>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiZGFsb3JrZWUiLCJhIjoiY2pnbmJrajh4MDZ6aTM0cXZkNDQ0MzI5cCJ9.C2REqhILLm2HKIQSn9Wc0A';
	var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/streets-v11',
	center: [ 100.503435, 13.7504999 ],
	zoom: 5,
	preserveDrawingBuffer: true
});

class PitchToggle {
	constructor({ bearing = -20, pitch = 70, minpitchzoom = null }) {
		this._bearing = bearing;
		this._pitch = pitch;
		this._minpitchzoom = minpitchzoom;
	}

	onAdd(map) {
		this._map = map;
		let _this = this;

		this._btn = document.createElement("button");
		this._btn.className = "mapboxgl-ctrl-icon mapboxgl-ctrl-pitchtoggle-3d";
		this._btn.type = "button";
		this._btn["aria-label"] = "Toggle Pitch";
		this._btn.onclick = function() {
			if (map.getPitch() === 0) {
				let options = { pitch: _this._pitch, bearing: _this._bearing };
				if (_this._minpitchzoom && map.getZoom() > _this._minpitchzoom) {
					options.zoom = _this._minpitchzoom;
				}
				map.easeTo(options);
				_this._btn.className = "mapboxgl-ctrl-icon mapboxgl-ctrl-pitchtoggle-2d";
			} else {
				map.easeTo({ pitch: 0, bearing: 0 });
				_this._btn.className = "mapboxgl-ctrl-icon mapboxgl-ctrl-pitchtoggle-3d";
			}
		};

		this._container = document.createElement("div");
		this._container.className = "mapboxgl-ctrl-group mapboxgl-ctrl";
		this._container.appendChild(this._btn);

		return this._container;
	}

	onRemove() {
		this._container.parentNode.removeChild(this._container);
		this._map = undefined;
	}
}

class MapboxGLButtonControl {
	constructor({
		className = "",
		title = "",
		eventHandler = evtHndlr
	}) {
		this._className = className;
		this._title = title;
		this._eventHandler = eventHandler;
	}

	onAdd(map) {
		this._btn = document.createElement("button");
		this._btn.className = "mapboxgl-ctrl-icon" + " " + this._className;
		this._btn.type = "button";
		this._btn.title = this._title;
		this._btn.onclick = this._eventHandler;

		this._container = document.createElement("div");
		this._container.className = "mapboxgl-ctrl-group mapboxgl-ctrl";
		this._container.appendChild(this._btn);

		return this._container;
	}

	onRemove() {
		this._container.parentNode.removeChild(this._container);
		this._map = undefined;
	}
}

/* Event Handlers */
function one(event) {
	exportToImage();
}

/* Instantiate new controls with custom event handlers */
const ctrlPoint = new MapboxGLButtonControl({
	className: "mapbox-gl-draw_point",
	title: "Export to image",
	eventHandler: one
});

var domLayer = null;
map.on('load', function() {
	var geojson = {
		"type": "FeatureCollection",
		"features": [
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
									$desc .= "<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width:".$pc_b."%;background-color:#ff6384;\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
								$desc .= "</div>";
							$desc .= "</div>";
							$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
								$desc .= "<span>Flu A, ".number_format($pc_flu_a, 2)."%</span>";
								$desc .= "<div class=\"ml-auto\"><span>".number_format($value->flu_a)."</span></div>";
							$desc .= "</div>";
							$desc .= "<div class=\"progress\">";
								$desc .= "<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width:".$pc_flu_a."%;background-color:#FFB447;\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
							$desc .= "</div>";
							$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
								$desc .= "<span>Flu H, ".number_format($pc_flu_h, 2)."%</span>";
								$desc .= "<div class=\"ml-auto\"><span>".number_format($value->flu_h)."</span></div>";
							$desc .= "</div>";
							$desc .= "<div class=\"progress\">";
								$desc .= "<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width:".$pc_flu_h."%;background-color:#36a2eb;\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
							$desc .= "</div>";
							$desc .= "<div class=\"d-flex no-block align-items-center m-t-15\">";
								$desc .= "<span>Nagative, ".number_format($pc_neg, 2)."%</span>";
								$desc .= "<div class=\"ml-auto\"><span>".number_format($value->neg)."</span></div>";
							$desc .= "</div>";
							$desc .= "<div class=\"progress\">";
								$desc .= "<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width:".$pc_neg."%;background-color:#77DD77;\" aria-valuenow=\"10\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
							$desc .= "</div>";
						$desc .= "</div>";
					$desc .= "</div>";
				@endphp
			{
				'type': 'Feature',
				'geometry': {
					'type': 'Point',
					'coordinates': [{{ $value->lon }} , {{ $value->lat }}]
				},
				'properties': {
					'title': '',
					'description': '{!!$desc!!}'
				}
			},
			@endforeach
		]
	};

	geojson.features.forEach(function(marker, i) {
		var el = document.createElement('div');
		el.className = 'marker';
		//el.innerHTML = '<span><b>' + (i + 1) + '</b></span>'
		el.innerHTML = ''

		new mapboxgl.Marker(el)
			.setLngLat(marker.geometry.coordinates)
			.setPopup(new mapboxgl.Popup({
				offset: 25
			})
			.setHTML('<div>' + marker.properties.description + '</div>'))
			.addTo(map);
	});

	domLayer = new Mapbox.DomOverlayer({
		map: map,
		doms: [
		@foreach ($marker_map as $key => $value)
			@php
				$pc_b = (($value->b/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
				$pc_flu_a = (($value->flu_a/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
				$pc_flu_h = (($value->flu_h/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
				$pc_neg = (($value->neg/($value->b+$value->flu_a+$value->flu_h+$value->neg))*100);
			@endphp
			{
				'type': 'doughnut',
				'class': 'bounceIn',
				'data': {
					'datasets': [{
						'data': [{{number_format($pc_b, 2)}}, {{number_format($pc_flu_a,2)}}, {{number_format($pc_flu_h,2)}}, {{number_format($pc_neg,2)}}],
						'backgroundColor': ['#ff6384', '#FFB447', '#36a2eb', '#77DD77']
					}],
					'labels': ['B', 'Flu A', 'Flu H', 'Neg']
				},
				'lon': {{$value->lon}},
				'lat': {{$value->lat}},
				'options': {
					'bezierCurve': false,
					'animation': false
				}
			},
		@endforeach
		]
	});

	/* sattlelight */
	map.addLayer({
		id: 'satellite',
		source: {"type": "raster",  "url": "mapbox://mapbox.satellite", "tileSize": 256},
		type: "raster",
		layout: {"visibility":"none"}
	});
	var switchy = document.getElementById('remover');
	switchy.addEventListener("click", function() {
		switchy = document.getElementById('remover');
		if (switchy.className === 'on') {
			switchy.setAttribute('class', 'off');
			map.setLayoutProperty('satellite', 'visibility', 'none');
			//switchy.innerHTML = 'Add satellite';
		} else {
			switchy.setAttribute('class', 'on');
			map.setLayoutProperty('satellite', 'visibility', 'visible');
			//switchy.innerHTML = 'Remove satellite';
		}
	});

});
map.addControl(new mapboxgl.NavigationControl(), 'top-right');
map.addControl(new PitchToggle({ minpitchzoom: 11 }), 'top-right');
map.addControl(ctrlPoint, 'top-right');
map.addControl(new mapboxgl.Directions());
</script>
<script>
function setCanvas() {
	html2canvas(document.getElementById('wp')).then(function(canvas) {
		canvas.id = "jet";
		canvas.style.display="none";
		document.body.appendChild(canvas);
	});
}
function exportToImage() {
	html2canvas(document.getElementById('wp')).then(function(canvas) {
		canvas.style.display="none";
		document.getElementById("img").src = canvas.toDataURL('image/png');
		Canvas2Image.saveAsPNG(canvas);
	});
}
</script>
@endsection
