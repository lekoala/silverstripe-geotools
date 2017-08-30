function buildLeafletMap(id) {
    var $map = jQuery('#' + id);
    var $content = jQuery('#' + id + '_content');
    var mapOptions = $map.data('mapoptions');
    var builderOptions = $map.data('builderoptions');
    var height = $map.css('height');
    var lat = $map.data('lat');
    var lon = $map.data('lon');
    var icon = $map.data('icon');
    var zoom = 16;
    var itemsurl = $map.data('itemsurl');
    if (!zoom) {
        zoom = 12;
    }
    var tilelayer = $map.data('tilelayer');
    var tileOptions = $map.data('tileoptions');

    var defaultIconSize = builderOptions.iconSize;
    var defaultIconWidth = defaultIconSize[0];
    var iconHalfWidth = defaultIconWidth / 2;
    var defaultIconHeight = defaultIconSize[1];

    var map = L.map(id, mapOptions);

    // Add tiles
    L.tileLayer(tilelayer, tileOptions).addTo(map);

    // Define base marker
    if (lat && lon) {
        map.setView(new L.LatLng(lat, lon), zoom);
        var markerOpts = {};
        if (icon) {
            icon = L.icon({
                iconUrl: icon,
                iconSize: defaultIconSize,
                iconAnchor: [iconHalfWidth, defaultIconHeight],
                popupAnchor: [0, -defaultIconHeight]
            });
            markerOpts.icon = icon;
        }
        var marker = L.marker([lat, lon], markerOpts);
        marker.addTo(map);
        if ($content.length) {
            marker.bindPopup($content.html())
                    .openPopup();
        }
    }
    // 100% height support
    if (!height || height === '0px') {
        $map.height(jQuery(window).height());
        map.invalidateSize();
    }


    // Load items
    if (itemsurl) {

        $.getJSON(itemsurl, function(data) {

            var points = [];
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                if (!item.lat) {
                    continue;
                }
                var point = [item.lat, item.lon];

                var markerOpts = {};
                var icon;

                if (item.number) {
                    icon = L.divIcon({
                        iconSize: defaultIconSize,
                        iconAnchor: [iconHalfWidth, defaultIconHeight],
                        popupAnchor: [0, -defaultIconHeight],
                        html: '<div class="leaflet-map-number">' + item.number + '</div>'
                    });
                    markerOpts.icon = icon;
                } else if (item.category_image) {
                    icon = L.icon({
                        iconUrl: item.category_image,
                        iconAnchor: [iconHalfWidth, defaultIconHeight],
                        popupAnchor: [0, -defaultIconHeight]
                    });
                    markerOpts.icon = icon;
                }

                var marker = L.marker(point, markerOpts);

                if (item.popup) {
                    marker.bindPopup(item.popup);
                }

                marker.addTo(map);
                points.push(point);
            }

            if (builderOptions.fitToBounds) {
                var bounds = new L.LatLngBounds(points);
                map.fitBounds(bounds);
            } else {

            }
        });
        map.scrollWheelZoom.enable();
    }
    // Show something if nothing is set
    if (!lat && !lon && !itemsurl) {
        map.setView(new L.LatLng(0, 0), 1);
    }
}