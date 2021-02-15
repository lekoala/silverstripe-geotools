<?php

namespace LeKoala\GeoTools\Services;

use Exception;
use LeKoala\GeoTools\Models\Address;
use LeKoala\GeoTools\Models\Country;
use LeKoala\GeoTools\Models\Coordinates;

/**
 * @link https://geocode.xyz
 */
class GeocodeXyz implements Geocoder, Geolocator
{
    const API_URL = 'https://geocode.xyz/';

    /**
     * @link https://geocode.xyz/api
     * @param string $query
     * @param array $params region, citybias
     * @return Address
     * @throws Exception when there is a problem with the api, otherwise may return an empty address
     */
    protected function query($query, $params = [])
    {
        $url = self::API_URL;

        $defaultParams = [
            'locate' => $query,
            'json' => 1
        ];
        $params = array_merge($defaultParams, $params);

        $url .= '?' . http_build_query($params);

        $headers = [
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "accept-encoding: gzip, deflate, br",
            "accept-language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7,nb;q=0.6",
            "cache-control: max-age=0",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36",
        ];
        $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => implode("\r\n", $headers),
        ));

        $context = stream_context_create($opts, $opts);

        $result = file_get_contents($url);
        if (!$result) {
            throw new Exception("The api returned no result");
        }

        $data = json_decode($result, JSON_OBJECT_AS_ARRAY);

        if (!$data) {
            throw new Exception("Failed to decode api results");
        }

        $location = [];
        $countryCode = $countryName = null;
        $lat = $lon = null;

        // normalize
        if (isset($data['standard'])) {
            $location = [
                'streetName' => $data['standard']['addresst'] ?? null,
                'streetNumber' => $data['standard']['stno'] ?? null,
                'postalCode' => $data['standard']['postal'] ?? null,
                'locality' => $data['standard']['city'] ?? null,
            ];
            $countryCode = $data['standard']['prov'] ?? null;
            $countryName = $data['standard']['countryname'] ?? null;
        } elseif (isset($data['staddress'])) {
            $location = [
                'streetName' => $data['staddress'] ?? null,
                'streetNumber' => $data['stnumber'] ?? null,
                'postalCode' => $data['postal'] ?? null,
                'locality' => $data['city'] ?? null,
            ];
            $countryCode = $data['prov'] ?? null;
            $countryName = $data['country'] ?? null;
        }

        // Make sure we have a string
        if (empty($location['postalCode'])) {
            $location['postalCode'] = '';
        }

        if (!empty($data['latt'])) {
            $lat = $data['latt'];
            $lon = $data['longt'];
        }

        $country = new Country($countryCode, $countryName);
        $coordinates = new Coordinates($lat, $lon);

        return new Address($location, $country, $coordinates);
    }

    /**
     * @return array
     */
    public static function listRegions()
    {
        return explode(', ', 'AF, AX, AL, DZ, AS, AD, AO, AI, AQ, AG, AR, AM, AW, AU, AT, AZ, BS, BH, BD, BB, BY, BE, BZ, BJ, BM, BT, BO, BQ, BA, BW, BR, IO, VG, BN, BG, BF, BI, KH, CM, CA, CV, KY, CF, TD, CL, CN, CX, CC, CO, KM, CG, CK, CR, HR, CU, CW, CY, CZ, CI, DK, DJ, DM, DO, EC, EG, SV, GQ, ER, EE, ET, FK, FO, FJ, FI, FR, GF, PF, TF, GA, GM, GE, DE, GH, GI, GR, GL, GD, GP, GU, GT, GG, GN, GW, GY, HT, HN, HK, HU, IS, IN, ID, IR, IQ, IE, IM, IL, IT, JM, JP, JE, JO, KZ, KE, KI, KS, KW, KG, LA, LV, LB, LS, LR, LY, LI, LT, LU, MO, MK, MG, MW, MY, MV, ML, MT, MH, MQ, MR, MU, YT, MX, FM, MD, MC, MN, ME, MS, MA, MZ, MM, NA, NR, NP, NL, AN, NC, NZ, NI, NE, NG, NU, NF, KP, MP, NO, OM, PK, PW, PS, PA, PG, PY, PE, PH, PN, PL, PT, PR, QA, RO, RU, RW, RE, GS, SH, KN, LC, PM, VC, BL, SX, MF, WS, SM, ST, SA, SN, RS, SC, SL, SG, SK, SI, SB, SO, ZA, KR, SS, ES, LK, SD, SR, SJ, SZ, SE, CH, SY, TW, TJ, TZ, TH, TL, TG, TK, TO, TT, TN, TR, TM, TC, TV, UM, UG, UA, AE, UK, US, UY, UZ, VU, VA, VE, VN, VI, WF, EH, YE, CD, ZM, ZW');
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

    /**
     * @inheritDoc
     */
    public function geolocate($ip, $params = [])
    {
        return $this->query($ip, $params);
    }
}
