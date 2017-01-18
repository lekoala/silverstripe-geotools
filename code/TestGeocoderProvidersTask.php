<?php

/**
 * A simple task to test if your providers are running smoothling

 * @author LeKoala
 */
class TestGeocoderProvidersTask extends BuildTask
{

    protected $description = 'Run a geocoding of a demo address with all your defined providers';

    public function run($request)
    {
        // Try to geocode a sample address
        $address = Geocoder::config()->local_address;

        // Test simple geocoding
        $geo = Geocoder::simpleGeocode($address);

        DB::alteration_message("Testing general behaviour");
        if ($geo) {
            DB::alteration_message('Simple geocoding is working ' . $geo['Latitude'] . '/' . $geo['Longitude'], 'created');
        } else {
            DB::alteration_message('Simple geocoding failed', 'error');
        }

        // Test all providers
        $geocoder = Geocoder::getGeocoder();
        $list = array_keys($geocoder->getProviders());
        DB::alteration_message("Registered providers are : " . implode(',', $list));

        foreach ($geocoder->getProviders() as $name => $provider) {
            DB::alteration_message("Testing : " . $name);

            try {
                $res = $geocoder->using($name)->geocode($address);

                if ($res->count()) {
                    DB::alteration_message("Provider $name is working and returned " . $res->count() . ' results', 'created');
                    foreach ($res as $result) {
                        DB::alteration_message('Geoloc is working ' . $result->getLatitude() . '/' . $result->getLongitude(), 'created');
                    }
                } else {
                    DB::alteration_message("Provider $name failed to geocode address", 'error');
                }
            } catch (\Geocoder\Exception\NoResult $ex) {
                DB::alteration_message("Provider $name failed to geocode address", 'error');
                DB::alteration_message($ex->getMessage(), 'error');
            } catch (\Exception $ex) {
                DB::alteration_message($ex->getMessage(), 'error');
            }
        }
    }
}
