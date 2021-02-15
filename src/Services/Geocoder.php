<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use LeKoala\GeoTools\Models\Address;

interface Geocoder
{
    /**
     * @param string $lat
     * @param string $lon
     * @param array $params
     * @return Address
     * @throws Exception
     */
    public function reverseGeocode($lat, $lon, $params = []);

    /**
     * @param string $address
     * @param array $params
     * @return Address
     * @throws Exception
     */
    public function geocode($address, $params = []);
}
