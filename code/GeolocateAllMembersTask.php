<?php

/**
 * WARNING : there is a separate manifest for cli and web (how nice is that???). Add flush=all at the end of the cli request to clear cache
 * php framework/cli-script.php /MyCliTask flush=all

 * @author LeKoala
 */
class GeolocateAllMembersTask extends BuildTask
{
    protected $title       = "Geolocate All Members";
    protected $description = "Geolocate your members";

    /**
     * 
     * @param SS_HTTPRequest $request
     */
    function run($request)
    {
        increase_time_limit_to();

        echo 'Pass ?refresh=1 to refresh your members<br/>';
        echo '<hr/>';

        $refresh = $request->getVar('refresh');

        if ($refresh) {
            $Members = Member::get();
        } else {
            $Members = Member::get()->filter(array('Latitude' => 0));
        }

        foreach ($Members as $Member) {
            DB::alteration_message('Processing member #'.$Member->ID . ' - ' . $Member->getTitle());
            if ($Member->shouldBeGeolocalized()) {
                DB::alteration_message('Should be geolocalized');
                if ($Member->canBeGeolocalized()) {
                    DB::alteration_message($Member->GeocodeText());

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
                DB::alteration_message('Should not be geolocalized', 'error');
            }
        }
    }
}