Silverstripe Geotools module
==================

Provide geocoding facilities for Silverstripe.

Geocoder
==================

This module provides a geocoder based on https://github.com/geocoder-php/Geocoder
This class is configurable through Silverstripe Config API.

You can geocode based on ip or address.

Reverse geocode and address formatting is also supported.

Options for providers and adapters must be provided as an array in the right order.
The locale must be defined with a placeholder string "locale" that will be replaced
by i18n::get_locale().

For example:
    
    Geocoder:
        providers:    
            BingMaps: ['your-key-here','locale']

Caching is also enabled by default since one address is not likely to be
geolocalized differently. This is very useful since ip lookup is quite slow.

If only one result is found, the address is direclty returned instead of an
array.

Exceptions are catched automatically by the library to make it easier for the
developper. You can access last throwed exception if you need more details
about was has happened. Errors are also logged automatically if configured.

Leaflet
==================

If you geocode stuff, chances are you will need a map. Since you already like
geocoder, you don't want to be stuck with a single provider.

To build a map, Leaflet is here for you! And this module provides some ready
to use helpers to easily integrate maps in your pages

Providers demo: http://leaflet-extras.github.io/leaflet-providers/preview/

Some sample code in your controller:

	public function LeafletMap()
    {
        $map = new LeafletMap();
        $map->setID('contact-map');
        $map->setLatitude($this->Latitude);
        $map->setLongitude($this->Longitude);
        $map->setTileLayer('http://{s}.tile.stamen.com/toner-lite/{z}/{x}/{y}.png');
        $map->setContent($this->MapContent);
        return $map;
    }

Compatibility
==================
Tested with 3.1

Maintainer
==================
LeKoala - thomas@lekoala.be