<?php

/**
 * WARNING : there is a separate manifest for cli and web (how nice is that???). Add flush=all at the end of the cli request to clear cache
 * php framework/cli-script.php /MyCliTask flush=all

 * @author LeKoala
 */
class GeolocateAllMembersTask extends BuildTask
{

    protected $title = "Geolocate All Members";
    protected $description = "Geolocate your members";

    /**
     * 
     * @param SS_HTTPRequest $request
     */
    public function run($request)
    {
        increase_time_limit_to();

        echo 'Pass ?refresh=1 to refresh your members<br/>';
        echo '<hr/>';

        $refresh = $request->getVar('refresh');

        if ($refresh) {
            DB::alteration_message("Resetting all members location");
            DB::query('UPDATE Member SET Latitude = 0, Longitude = 0');
        }

        $Members = Member::get()->filter(array('Latitude' => 0));

        foreach ($Members as $Member) {
            DB::alteration_message('Processing member #' . $Member->ID . ' - ' . $Member->getTitle());
            if (!$Member->Latitude) {
                if ($Member->canBeGeolocalized()) {
                    DB::alteration_message($Member->GeocodeText());

                    if (!$Member->CountryCode) {
                        DB::alteration_message("Warning ! This member has no country code", "error");
                    }

                    /* @var $res Geocoder\Model\Address */
                    $res = $Member->Geocode();

                    if ($res) {
                        DB::alteration_message('Geocode success on ' . $res->getLatitude() . ',' . $res->getLongitude() . ' : ' . $res->getStreetNumber() . ', ' . $res->getStreetName() . ' ' . $res->getPostalCode() . ' ' . $res->getLocality() . ' ' . $res->getCountry(), 'created');
                        $Member->write();
                    } else {
                        DB::alteration_message('Geocode error', 'error');
                    }
                } else {
                    DB::alteration_message('Cannot be geolocalized', 'error');
                }
            } else {
                DB::alteration_message('Already geolocalized', 'error');
            }
        }
    }
}
