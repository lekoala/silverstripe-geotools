<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use LeKoala\GeoTools\Models\Address;

interface Geocoder
{
    /**
     * @param string|float $lat
     * @param string|float $lon
     * @param array<int|string,mixed> $params
     * @return Address
     * @throws Exception
     */
    public function reverseGeocode($lat, $lon, $params = []);

    /**
     * @param string $address
     * @param array<int|string,mixed> $params
     * @return Address
     * @throws Exception
     */
    public function geocode($address, $params = []);
}
