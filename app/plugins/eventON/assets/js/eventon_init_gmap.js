/*
	INITIATE google gmaps on web page
*/
	
var geocoder;
var map;
function initialize(map_canvas_id, address,mapformat, zoom_level) {
	
	
	
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(-34.397, 150.644);
	var myOptions = {
		zoom: zoom_level,
		center: latlng,
		mapTypeId:mapformat
	}
	var map_canvas = document.getElementById(map_canvas_id);
	map = new google.maps.Map(map_canvas, myOptions);
			
	
	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location
		});
		//$('#'+map_canvas_id).height(200);
	  } else {
			document.getElementById('#'+map_canvas_id).style.display='none';
		//alert("Geocode was not successful for the following reason: " + status);
	  }
	});
}
