<?php

namespace LeKoala\GeoTools\Test;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\SapphireTest;
use LeKoala\GeoTools\Services\IpApi;
use LeKoala\GeoTools\Services\MapBox;
use LeKoala\GeoTools\Services\BingMaps;
use LeKoala\GeoTools\Services\Geocoder;
use LeKoala\GeoTools\Services\Nominatim;
use SilverStripe\Core\Injector\Injector;
use LeKoala\GeoTools\Services\GeocodeXyz;
use LeKoala\GeoTools\Services\Geolocator;

class GeoTest extends SapphireTest
{
    public function testInjector(): void
    {
        $geocoder = Injector::inst()->get(Geocoder::class);
        $this->assertTrue($geocoder instanceof Geocoder);
        $geolocator = Injector::inst()->get(Geolocator::class);
        $this->assertTrue($geolocator instanceof Geolocator);
        // It's a singleton
        $geolocator2 = Injector::inst()->get(Geolocator::class);
        $this->assertSame($geolocator, $geolocator2);
    }

    public function testIpApi(): void
    {
        $ip = '189.59.228.17';
        $service = new IpApi;
        $result = $service->geolocate($ip);

        $this->assertNotEmpty($result);
        $this->assertEquals('BR', $result->getCountry()->getCode());
        $this->assertEquals('-23.5558', $result->getCoordinates()->getLatitude());
        $this->assertEquals('01000-000', $result->getPostalCode());
    }

    public function testGeocodeXyz(): void
    {
        // $service = new GeocodeXyz;

        // $result = $service->reverseGeocode('41.31900', '2.07465');
        // $this->assertNotEmpty($result);
        // $this->assertEquals('ES', $result->getCountry()->getCode());
        // $this->assertEquals(41.319, round($result->getCoordinates()->getLatitude(), 3));
        // $this->assertEquals('8820', $result->getPostalCode());

        // No hammering
        // sleep(5);

        // $result = $service->geocode("71, avenue des Champs Élysées, Paris, France");
        // $this->assertNotEmpty($result);
        // $this->assertEquals('FR', $result->getCountry()->getCode());
        // $this->assertEquals('75008', $result->getPostalCode());
        // $this->assertEquals(48.871, round($result->getCoordinates()->getLatitude(), 3));
    }

    public function testBing(): void
    {
        if (!Environment::getEnv('BING_MAPS_API_KEY')) {
            $this->markTestSkipped("Need a BING_MAPS_API_KEY env");
        }

        $service = new BingMaps;

        $result = $service->reverseGeocode('41.31900', '2.07465');
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'ES');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 41.319);
        $this->assertEquals($result->getPostalCode(), '08820');

        // No hammering
        sleep(5);

        $result = $service->geocode("71, avenue des Champs Élysées, Paris, France");
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'FR');
        $this->assertEquals($result->getPostalCode(), '75008');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 48.871);
    }

    public function testMapBox(): void
    {
        if (!Environment::getEnv('MAPBOX_API_KEY')) {
            $this->markTestSkipped("Need a MAPBOX_API_KEY env");
        }

        $service = new MapBox;

        $result = $service->reverseGeocode('41.31900', '2.07465');
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'ES');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 41.319);
        $this->assertEquals($result->getPostalCode(), '08820');

        // No hammering
        sleep(5);

        $result = $service->geocode("71, avenue des Champs Élysées, Paris, France");
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'FR');
        $this->assertEquals($result->getPostalCode(), '75008');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 48.871);
    }

    public function testNominatim(): void
    {
        $service = new Nominatim;

        $result = $service->reverseGeocode('41.31900', '2.07465');
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'ES');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 41.319);
        $this->assertEquals($result->getPostalCode(), '08820');

        // No hammering
        sleep(5);

        $result = $service->geocode("71, avenue des Champs Élysées, Paris, France");
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getCountry()->getCode(), 'FR');
        $this->assertEquals($result->getPostalCode(), '75008');
        $this->assertEquals(round($result->getCoordinates()->getLatitude(), 3), 48.871);
    }
}
