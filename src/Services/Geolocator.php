<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use LeKoala\GeoTools\Models\Address;

interface Geolocator
{
    /**
     * @param string $ip
     * @param array $params
     * @return Address
     * @throws Exception
     */
    public function geolocate($ip, $params = []);
}
