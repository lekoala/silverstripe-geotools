function buildLeafletMap(id) {
	var $map = $('#' + id);
	var $content = $('#' + id + '_content');
	var mapOptions = $map.data('mapoptions');
	var lat = $map.data('lat');
	var lon = $map.data('lon');
	var zoom = $map.data('zoom');
	if(!zoom) {
		zoom = 12;
	}
	var tilelayer = $map.data('tilelayer');
	var tileOptions = $map.data('tileoptions');

	var map = L.map(id, mapOptions);
	
	//add tiles
	L.tileLayer(tilelayer, tileOptions).addTo(map);
	
	map.setView(new L.LatLng(lat, lon), zoom);

	var marker = L.marker([lat, lon]);
	marker.addTo(map);
	if ($content.length) {
		marker.bindPopup($content.html())
				.openPopup();
	}

}