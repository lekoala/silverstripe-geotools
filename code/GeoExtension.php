<?php

/**
 * GeoExtension
 *
 * Field name follow closely the Geocoder\Model\Address class
 *
 * @author lekoala
 */
class GeoExtension extends DataExtension
{
    private static $db = array(
        'Latitude' => 'Float(10,6)',
        'Longitude' => 'Float(10,6)',
        'StreetNumber' => 'Varchar(255)',
        'StreetName' => 'Varchar(255)',
        'PostalCode' => 'Varchar(20)',
        'Locality' => 'Varchar(255)',
        'SubLocality' => 'Varchar(255)',
        'CountyName' => 'Varchar(255)',
        'CountyCode' => 'Varchar',
        'RegionName' => 'Varchar(255)',
        'RegionCode' => 'Varchar',
        'CountryName' => 'Varchar(255)',
        'CountryCode' => 'Varchar(2)',
        'Timezone' => 'Varchar',
        'GeolocateOnLocation' => 'Boolean'
    );

    /**
     * Get a list of countries
     * 
     * @param string $lang
     * @return array
     */
    public static function getCountryList($lang = null)
    {
        if (!$lang) {
            $lang = i18n::get_locale();
        }
        $countries = Zend_Locale::getTranslationList('territory', $lang, 2);
        asort($countries, SORT_LOCALE_STRING);
        unset($countries['SU'], $countries['ZZ'], $countries['VD'],
            $countries['DD']);
        return $countries;
    }

    /**
     * Get country based on CountryCode
     * 
     * @return string
     */
    function getCountryFromCode()
    {
        return Zend_Locale::getTranslation($this->owner->CountryCode,
                'territory', i18n::get_locale());
    }

    /**
     * Get county
     *
     * @return \Geocoder\Model\County
     */
    function getCounty()
    {
        return new \Geocoder\Model\County($this->owner->CountyName,
            $this->owner->CountyCode);
    }

    /**
     * Get region
     *
     * @return \Geocoder\Model\Region
     */
    function getRegion()
    {
        return new \Geocoder\Model\Region($this->owner->RegionName,
            $this->owner->RegionCode);
    }

    /**
     * Get country
     *
     * @return \Geocoder\Model\Country
     */
    function getCountry()
    {
        return new \Geocoder\Model\Country($this->owner->CountryName,
            $this->owner->CountryCode);
    }

    /**
     * Get address
     *
     * @return \Geocoder\Model\Address
     */
    function getAddress()
    {
        return new \Geocoder\Model\Address($this->getCoordinates(), null,
            $this->owner->StreetNumber, $this->owner->StreetName,
            $this->owner->PostalCode, $this->owner->Locality,
            $this->owner->SubLocality, $this->getCounty(), $this->getRegion(),
            $this->getCountry(), $this->owner->Timezone);
    }

    /**
     * Get formatted address
     *
     * @param string $format
     * @return string
     */
    function getFormattedAddress($format = null)
    {
        return Geocoder::formatAddress($this->getAddress(), $format);
    }

    /**
     * Get coordinates as array
     *
     * @return \Geocoder\Model\Coordinates
     */
    function getCoordinates()
    {
        return new \Geocoder\Model\Coordinates($this->owner->Latitude,
            $this->owner->Longitude);
    }

    /**
     * Alias for locality
     * 
     * @return string
     */
    function getCity()
    {
        return $this->owner->Locality;
    }

    /**
     * Get location (number street)
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->getFormattedAddress('%n %S');
    }

    /**
     * Get location (city, country)
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->getFormattedAddress('%L, %C');
    }

    /**
     * Get address for js
     *
     * @param string $format
     * @return string
     */
    public function getEncodedAddress($format = null)
    {
        return urlencode($this->getFormattedAddress($format));
    }

    /**
     * Returns the full address in a simple HTML template.
     *
     * @return string
     */
    public function getFullAddressHTML()
    {
        return $this->owner->renderWith('Address');
    }

    /**
     * Get nearest records
     *
     * @param int $lat
     * @param int $lng
     * @param int $distance in kilometers => 6371 (radius of earth)
     */
    public static function Nearby($lat, $lng, $distance = 100, $limit = null)
    {
        $class = $this->owner->class;
        $table = ClassInfo::baseDataClass($class);
        $sql   = "SELECT ID,
			( 6371 * acos(
				cos( radians($lat))
				* cos( radians( Lat ))
				* cos( radians( Lon ) - radians($lng))
				+ sin( radians($lat ))
				* sin( radians( Lat ))
			 )) AS distance
			FROM ".$table."
			HAVING distance < $distance ORDER BY distance";
        if ($limit) {
            $sql .= ' LIMIT '.$limit;
        }
        $records = DB::query($sql);
        $ids     = array();
        foreach ($records as $r) {
            $ids[] = $r['ID'];
        }
        return $class::get()->filter(array('ID' => $ids));
    }

    public function updateCMSFields(FieldList $fields)
    {
        $dbFields = self::$db;
        foreach ($dbFields as $name => $type) {
            $fields->removeByName($name);
        }
        $fields->addFieldsToTab('Root.Geo', $this->getGeoFields());
    }

    /**
     * @param boolean $geoDefaults
     * @return \FieldList
     */
    public function getAddressFields($geoDefaults = true)
    {
        $fields = new CompositeField;

        $localityValue   = $this->owner->Locality;
        $countryCode     = $this->owner->CountryCode;
        $postalCodeValue = $this->owner->PostalCode;

        if ((!$localityValue || !$countryCode || !$postalCodeValue) && $geoDefaults) {
            $addressFromIp = Geocoder::geocodeIp(null, 'city', true);
            if ($addressFromIp) {
                if (!$localityValue) {
                    $localityValue = $addressFromIp->getLocality();
                }
                if (!$countryCode) {
                    $countryCode = $addressFromIp->getCountryCode();
                }
                if (!$postalCodeValue) {
                    $postalCodeValue = $addressFromIp->getPostalCode();
                }
            }
        }

        $longWidth  = 300;
        $shortWidth = 80;

        $streetname = new TextField('StreetName',
            _t('GeoMemberExtension.STREETNAME', 'Street Name'),
            $this->owner->StreetName);
        $streetname->setAttribute('placeholder',
            _t('GeoMemberExtension.STREETNAME', 'Street Name'));
        $streetname->setTitle('');
        $streetname->setAttribute('style', 'width:'.$longWidth.'px');

        $streetnumber = new TextField('StreetNumber',
            _t('GeoMemberExtension.STREETNUMBER', 'Street Number'),
            $this->owner->StreetNumber);
        $streetnumber->setAttribute('placeholder',
            _t('GeoMemberExtension.STREETNUMBERPLACEHOLDER', 'N°'));
        $streetnumber->setTitle('');
        $streetnumber->setAttribute('style', 'width:'.$shortWidth.'px');

        $fields->push($street = new FieldGroup($streetname, $streetnumber));
        $street->setTitle(_t('GeoMemberExtension.STREET', 'Street'));
        $street->setFieldHolderTemplate('AddressFieldHolder');

        $postcode = new TextField('PostalCode',
            _t('GeoMemberExtension.POSTCODE', 'Postal Code'), $postalCodeValue);
        $postcode->setAttribute('placeholder',
            _t('GeoMemberExtension.POSTCODEPLACEHOLDER', 'Postcode'));
        $postcode->setTitle('');
        $postcode->setAttribute('style', 'width:'.$shortWidth.'px');

        $locality = new TextField('Locality',
            _t('GeoMemberExtension.CITY', 'City'), $localityValue);
        $locality->setAttribute('placeholder',
            _t('GeoMemberExtension.CITY', 'City'));
        $locality->setTitle('');
        $locality->setAttribute('style', 'width:'.$longWidth.'px');

        $fields->push($localitygroup = new FieldGroup($postcode, $locality));
        $localitygroup->setTitle(_t('GeoMemberExtension.LOCALITY', 'Locality'));
        $localitygroup->setFieldHolderTemplate('AddressFieldHolder');

        $label = _t('GeoMemberExtension.COUNTRY', 'Country');
        $fields->push(new CountryDropdownField('CountryCode',
            _t('GeoMemberExtension.COUNTRY', 'Country'), self::getCountryList(),
            $countryCode));

        return $fields;
    }

    /**
     * @return \FieldList
     */
    public function getGeoFields()
    {
        $fields = new FieldList;

        $fields->push(new HeaderField('AddressHeader',
            _t('GeoMemberExtension.ADDRESSHEADER', 'Address')));
        $fields->push($this->getAddressFields(), 'Address');

        $fields->push(new HeaderField('GeoHeader',
            _t('GeoMemberExtension.GEOHEADER', 'Geo data')));

        $latitude = new TextField('Latitude',
            _t('GeoMemberExtension.LATITUDE', 'Latitude'),
            $this->owner->Latitude ? $this->owner->Latitude : null);
        $latitude->setAttribute('placeholder',
            _t('GeoMemberExtension.LATITUDE', 'Latitude'));
        $latitude->setTitle('');

        $longitude = new TextField('Longitude',
            _t('GeoMemberExtension.LONGITUDE', 'Longitude'),
            $this->owner->Longitude ? $this->owner->Longitude : null);
        $longitude->setAttribute('placeholder',
            _t('GeoMemberExtension.LONGITUDE', 'Longitude'));
        $longitude->setTitle('');

        $coords = new FieldGroup($latitude, $longitude);
        $coords->setFieldHolderTemplate('AddressFieldHolder');

        $coords->setTitle(_t('GeoMemberExtension.COORDS', 'Coordinates'));

        $fields->push($coords);

        $fields->push(new CheckboxField('GeolocateOnLocation',
            _t('GeoMemberExtension.GEOLOCATEONLOCATION',
                'Only show location instead of full address')));

        $tz       = timezone_identifiers_list();
        $timezone = $this->owner->Timezone;
        if (!$timezone) {
            $timezone = date_default_timezone_get();
        }
        $fields->push(new DropdownField('Timezone',
            _t('GeoMemberExtension.TIMEZONE', 'Timezone'),
            array_combine($tz, $tz), $timezone));

        return $fields;
    }

    /**
     * Returns a static google map of the address, linking out to the address.
     *
     * @param  int $width
     * @param  int $height
     * @return string
     */
    public function AddressMap($width, $height)
    {
        $data = $this->owner->customise(array(
            'Width' => $width,
            'Height' => $height,
            'Address' => rawurlencode($this->getFormattedAddress())
        ));
        return $data->renderWith('AddressMap');
    }

    /**
     * Returns TRUE if any of the address fields have changed.
     *
     * @param  int $level
     * @return bool
     */
    public function isAddressChanged($level = 1)
    {
        $fields = array('StreetName', 'StreetNumber', 'Locality', 'PostalCode',
            'CountryCode', 'GeolocateOnLocation ')

        ;
        $changed = $this->owner->getChangedFields(false, $level);

        foreach ($fields as $field) {
            if (array_key_exists($field, $changed)) return true;
        }

        return false;
    }

    public function canBeGeolocalized()
    {
        return strlen($this->getFormattedAddress()) > 5;
    }

    public function shouldBeGeolocalized()
    {
        return !$this->owner->Latitude && $this->canBeGeolocalized();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($this->shouldBeGeolocalized() || $this->isAddressChanged()) {
            $this->Geocode();
        }
        if (!$this->owner->Timezone) {
            $this->owner->Timezone = date_default_timezone_get();
        }
    }

    /**
     * @return \Geocoder\Model\Address
     */
    public function ReverseGeocode()
    {
        if (!$this->owner->Latitude) {
            return false;
        }
        try {
            $results = Geocoder::getGeocoder()->reverse($this->owner->Latitude,
                $this->owner->Longitude);
            if ($results->count()) {
                return $results->first();
            }
        } catch (\Geocoder\Exception\NoResult $ex) {
            SS_Log::log($ex->getMessage(), SS_Log::DEBUG);
        }
        return false;
    }

    /**
     * Geocode the current full address
     * @return \Geocoder\Model\Address
     */
    public function Geocode()
    {
        if (!$this->canBeGeolocalized()) {
            return false;
        }
        try {
            if ($this->owner->GeolocateOnLocation) {
                $collection = Geocoder::getGeocoder()->geocode($this->getLocation());
            } else {
                $collection = Geocoder::getGeocoder()->geocode($this->getFormattedAddress());
            }
            if ($collection->count()) {
                $address                = $collection->first();
                $this->owner->Latitude  = $address->getLatitude();
                $this->owner->Longitude = $address->getLongitude();
                return $address;
            }
        } catch (\Geocoder\Exception\NoResult $ex) {
            SS_Log::log($ex->getMessage(), SS_Log::DEBUG);
        }
        return false;
    }
}