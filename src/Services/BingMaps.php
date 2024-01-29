<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use SimpleXMLElement;
use SilverStripe\Core\Environment;
use LeKoala\GeoTools\Models\Address;
use LeKoala\GeoTools\Models\Country;
use LeKoala\GeoTools\Models\Coordinates;

/**
 * @link https://www.bingmapsportal.com/Application
 * @link https://learn.microsoft.com/en-us/bingmaps/articles/accessing-the-bing-maps-rest-services-using-php
 */
class BingMaps implements Geocoder
{
    const API_URL = 'http://dev.virtualearth.net/REST/v1/Locations/';

    /**
     * @return string|null
     */
    public static function getApiKey()
    {
        return Environment::getEnv('BING_MAPS_API_KEY');
    }

    /**
     * @param ?string $query
     * @param array<int|string,mixed> $params countrycodes
     * @return Address
     * @throws Exception when there is a problem with the api, otherwise may return an empty address
     */
    protected function query($query, $params = [])
    {
        $key = self::getApiKey();
        if (!$key) {
            throw new Exception('No api key defined in env');
        }

        $query = str_ireplace(" ", "%20", $query);
        $findURL = self::API_URL . "/" . $query . "?incl=ciso2&key=" . $key;
        $output = file_get_contents($findURL);

        if (!$output) {
            throw new Exception("The api returned no result");
        }

        $result = json_decode($output, true);

        if (!$result) {
            throw new Exception(json_last_error_msg());
        }

        $firstResult = $result['resourceSets'][0]['resources'][0] ?? null;
        if (!$firstResult) {
            throw new Exception("Empty resultset");
        }

        $point = $firstResult['point']['coordinates'];
        $lat = $point[0];
        $lon = $point[1];

        $address = $firstResult['address'];
        $countryName = $address['countryRegion'];
        $countryCode = $address['countryRegionIso2'];

        $streetAndNumber = $address['addressLine'] ?? null;
        $location = [];
        if ($streetAndNumber) {
            $number = null;
            $street = $streetAndNumber;
            $parts = explode(' ', $streetAndNumber, 2);
            if (intval($parts[0]) > 0) {
                $number = $parts[0];
                $street = $parts[1];
            }
            $location = [
                'streetName' => $street,
                'streetNumber' => $number,
                'postalCode' =>  $address['postalCode'] ?? null,
                'locality' =>  $address['locality'] ?? null,
            ];
        }

        $country = new Country($countryCode, $countryName);
        $coordinates = new Coordinates($lat, $lon);

        return new Address($location, $country, $coordinates);
    }

    /**
     * @inheritDoc
     */
    public function reverseGeocode($lat, $lon, $params = [])
    {
        return $this->query("$lat,$lon", $params);
    }

    /**
     * @inheritDoc
     */
    public function geocode($address, $params = [])
    {
        return $this->query($address, $params);
    }
}
