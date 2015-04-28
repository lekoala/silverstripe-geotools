<?php
if(!defined('GEOTOOLS_PATH')) define('GEOTOOLS_PATH', rtrim(basename(dirname(__FILE__))));

// Autoconfigure providers based on constants

$baseProviders = Config::inst()->get('Geocoder', 'providers');

if (defined('BING_MAPS_API_KEY')) {
    $baseProviders = array('BingMaps' => array(BING_MAPS_API_KEY, 'locale')) + $baseProviders;
}
if (defined('GOOGLE_MAPS_API_KEY')) {
    $baseProviders = array('GoogleMaps' => array('locale', null, true, GOOGLE_MAPS_API_KEY)) + $baseProviders;
}

Config::inst()->update('Geocoder', 'providers', $baseProviders);