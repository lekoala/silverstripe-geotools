function buildLeafletMap(id) {
	var $map = $('#' + id);
	var $content = $('#' + id + '_content');
	var mapOptions = $map.data('mapoptions');
	var height = $map.css('height');
	var lat = $map.data('lat');
	var lon = $map.data('lon');
	var zoom = $map.data('zoom');
	var itemsurl = $map.data('itemsurl');
	if (!zoom) {
		zoom = 12;
	}
	var tilelayer = $map.data('tilelayer');
	var tileOptions = $map.data('tileoptions');

	var map = L.map(id, mapOptions);

	// Add tiles
	L.tileLayer(tilelayer, tileOptions).addTo(map);

	// Define base marker
	if (lat && lon) {
		map.setView(new L.LatLng(lat, lon), zoom);
		var marker = L.marker([lat, lon]);
		marker.addTo(map);
		if ($content.length) {
			marker.bindPopup($content.html())
					.openPopup();
		}
	}
	
	// 100% height support
	if (!height || height === '0px') {
		$map.height($(window).height());
		map.invalidateSize();
	}

	// Load items
	if (itemsurl) {
		$.getJSON(itemsurl, function (data) {
			var points = [];
			for (var i = 0; i < data.length; i++) {
				var item = data[i];
				if (!item.lat) {
					continue;
				}
				var point = [item.lat, item.lon];
				var marker = L.marker(point);
				if (item.popup) {
					marker.bindPopup(item.popup);
				}
				marker.addTo(map);
				points.push(point);
			}
			var bounds = new L.LatLngBounds(points);
			map.fitBounds(bounds);
		});
	}
	
	// Show something if nothing is set
	if(!lat && !lon && !itemsurl) {
		map.setView(new L.LatLng(0, 0), 1);
	}
}