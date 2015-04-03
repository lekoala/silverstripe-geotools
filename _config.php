<?php
// Autoconfigure providers based on constants

$baseProviders = Config::inst()->get('Geocoder', 'providers');

if (defined('BING_MAPS_API_KEY')) {
    $baseProviders['BingMaps'] = array(BING_MAPS_API_KEY, 'locale');
}
if (defined('GOOGLE_MAPS_API_KEY')) {
    $baseProviders['GoogleMaps'] = array('locale', null, true, GOOGLE_MAPS_API_KEY);
}

Config::inst()->update('Geocoder', 'providers', $baseProviders);