<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use LeKoala\GeoTools\Models\Address;

interface Geolocator
{
    /**
     * @param string $ip
     * @param array<int|string,mixed> $params
     * @return Address
     * @throws Exception
     */
    public function geolocate($ip, $params = []);
}
