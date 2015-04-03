<?php

/**
 * WARNING : there is a separate manifest for cli and web (how nice is that???). Add flush=all at the end of the cli request to clear cache
 * php framework/cli-script.php /MyCliTask flush=all

 * @author LeKoala
 */
class GeolocateAllMembersTask extends BuildTask
{

    function run($request)
    {
        $Members = Member::get()->filter(array('Latitude' => 0));

        foreach ($Members as $Member) {
            DB::alteration_message('Processing member #'.$Member->ID);
            if ($Member->shouldBeGeolocalized()) {
                DB::alteration_message('Should be geolocalized');
                if ($Member->canBeGeolocalized()) {
                    DB::alteration_message('Can be geolocalized');

                    $res = $Member->Geocode();
                    if ($res) {
                        DB::alteration_message('Geocode success', 'success');
                        $Member->save();
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