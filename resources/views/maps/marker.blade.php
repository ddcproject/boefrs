@extends('layouts.index')
@section('custom-style')
<!--<link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.css" rel="stylesheet">-->
<link href="{{ URL::asset('assets/libs/mapbox-plugins/mapbox-gl-js/assembly/assembly-v0.23.2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('asset(libs/mapbox/plugins/mapbox-gl-js/v1.11.1/mapbox-gl.css') }}" rel='stylesheet'>
@endsection
@section('internal-style')
<style>
.page-wrapper {
	background: white !important;
}
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
.mapboxgl-popup {
	max-width: 400px;
	font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
}
.marker {width:0; height:0;}
.marker  span {
  display:flex;
  justify-content:center;
  align-items:center;
  box-sizing:border-box;
  width: 30px;
  height: 30px;
  color:#fff;
  background: #693;
  border:solid 2px;
  border-radius: 0 70% 70%;
  box-shadow:0 0 2px #000;
  cursor: pointer;
  transform-origin:0 0;
  transform: rotateZ(-135deg);
}
.marker b {transform: rotateZ(135deg)}

.marker1 {width:0; height:0;}
.marker1  span {
  display:flex;
  justify-content:center;
  align-items:center;
  box-sizing:border-box;
  width: 30px;
  height: 30px;
  color:#fff;
  background: #ff00ff;
  border:solid 2px;
  border-radius: 0 70% 70%;
  box-shadow:0 0 2px #000;
  cursor: pointer;
  transform-origin:0 0;
  transform: rotateZ(-135deg);
}
.marker1 b {transform: rotateZ(135deg)}


.mapboxgl-popup-content {
	min-width: 250px;
}
.mapboxgl-poupu-content-wrapper {
	padding: 1%;
}
</style>
@endsection
@section('contents')
<div class="page-breadcrumb bg-light pb-2">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title">Map</h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Dot Map</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid" style="margin:0;padding:0;">
	<div id="map"></div>
</div>
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/mapbox-plugins/mapbox-gl-js/v1.11.1/mapbox-gl.js') }}"></script>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiZGFsb3JrZWUiLCJhIjoiY2pnbmJrajh4MDZ6aTM0cXZkNDQ0MzI5cCJ9.C2REqhILLm2HKIQSn9Wc0A';
	var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/streets-v11',
	center: [ 103.511621, 12.538136 ],
	zoom: 5.2
});
map.on('load', function() {
	var geojson = {
	  "type": "FeatureCollection",
	  "features": [{
	      "type": "Feature",
	      "geometry": {
	        "type": "Point",
	        "coordinates": [100.5217518, 13.85299353]
	      },
	      "properties": {
	        "title": "Mapbox",
	        "description": "Washington, D.C."
	      }
	    },
	    {
	      "type": "Feature",
	      "geometry": {
	        "type": "Point",
	        "coordinates": [102.1022606, 12.60435331]
	      },
	      "properties": {
	        "title": "Mapbox",
	        "description": "San Francisco, California"
	      }
	    }
	  ]
	};

	// add markers to map
geojson.features.forEach(function(marker, i) {

// create a HTML element for each feature
var el = document.createElement('div');
if (i == 1) {
	el.className = 'marker';
} else {
	el.className = 'marker1';
}
el.innerHTML = '<span><b>' + (i + 1) + '</b></span>'
// make a marker for each feature and add it to the map
new mapboxgl.Marker(el)
	.setLngLat(marker.geometry.coordinates)
	.setPopup(new mapboxgl.Popup({
		offset: 25
	}) // add popups
	.setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
.addTo(map);
});

});
</script>
@endsection
