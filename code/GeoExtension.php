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
    private static $db                  = array(
        'Latitude' => 'Float(10,6)',
        'Longitude' => 'Float(10,6)',
        'StreetNumber' => 'Varchar(255)',
        'StreetName' => 'Varchar(255)',
        'PostalCode' => 'Varchar(32)',
        'Locality' => 'Varchar(255)',
        'SubLocality' => 'Varchar(255)',
        'AdministrativeArea' => 'Varchar(255)', // State, province, region...
        'SubAdministrativeArea' => 'Varchar(255)', // County, district...
        'CountryName' => 'Varchar(255)',
        'CountryCode' => 'Varchar(2)',
        'Timezone' => 'Varchar',
        'GeolocateOnLocation' => 'Boolean'
    );
    public static $disable_auto_geocode = false;

    /**
     * Get a list of countries
     *
     * @param string $locale
     * @return array
     */
    public static function getCountryList($locale = null)
    {
        if (!$locale) {
            $locale = i18n::get_locale();
        }
        $countries = Zend_Locale::getTranslationList('territory', $locale, 2);
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
        return self::convertCountryCodeToName($this->owner->CountryCode);
    }

    /**
     * Convert country code to name
     *
     * @param string $code
     * @return string
     */
    public static function convertCountryCodeToName($code)
    {
        if (!$code) {
            return;
        }
        return Zend_Locale::getTranslation($code, 'territory',
                i18n::get_locale());
    }

    /**
     * Get country
     * 
     * @return string
     */
    function getCountry()
    {
        if ($this->owner->CountryName) {
            return $this->owner->CountryName;
        }
        if ($this->owner->CountryCode) {
            return $this->getCountryFromCode();
        }
    }

    /**
     * Get country as an object
     *
     * @return \Geocoder\Model\Country
     */
    function getCountryObject()
    {
        return new \Geocoder\Model\Country($this->getCountry(),
            $this->owner->CountryCode);
    }

    /**
     * Get admin level collection (up to two levels)
     *
     * @return \Geocoder\Model\AdminLevelCollection
     */
    function getAdminLevelObject()
    {
        $arr = array();
        if ($this->owner->SubAdministrativeArea) {
            $arr[] = new \Geocoder\Model\AdminLevel(8,
                $this->owner->SubAdministrativeArea, '');
        }
        if ($this->owner->AdministrativeArea) {
            $arr[] = new \Geocoder\Model\AdminLevel(4,
                $this->owner->AdministrativeArea, '');
        }

        return new \Geocoder\Model\AdminLevelCollection($arr);
    }

    /**
     * Get address
     *
     * @return \Geocoder\Model\Address
     */
    function getAddress()
    {
        return new \Geocoder\Model\Address($this->getCoordinatesObjet(), null,
            $this->owner->StreetNumber, $this->owner->StreetName,
            $this->owner->PostalCode, $this->owner->Locality,
            $this->owner->SubLocality, $this->getAdminLevelObject(),
            $this->getCountryObject(), $this->owner->Timezone);
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
     * @return array
     */
    function getCoordinates()
    {
        return array($this->owner->Latitude, $this->owner->Longitude);
    }

    /**
     * Get coordinates as object
     *
     * @return \Geocoder\Model\Coordinates
     */
    function getCoordinatesObjet()
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
     * @param string $value
     * @return DataObject $this
     */
    public function setLatitude($value)
    {
        return $this->owner->setField('Latitude', str_replace(',', '.', $value));
    }

    /**
     * @param string $value
     * @return DataObject $this
     */
    public function setLongitude($value)
    {
        return $this->owner->setField('Longitude', str_replace(',', '.', $value));
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
     * Get full address
     *
     * @param string $format
     * @return string
     */
    public function getFullAddress($format = null) {
        return $this->getFormattedAddress($format);
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
     * @return ArrayList
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
        if (empty($ids)) {
            return $class::get()->filter('ID', 0);
        }
        return $class::get()->filter(array('ID' => $ids));
    }

    public function updateCMSFields(FieldList $fields)
    {
        // Add tab if it does not exists yet
        if (!$fields->fieldByName('Root.Geo')) {
            $geoFields = $this->getGeoFields(false);

            // Avoid duplicates
            $fields->removeByName('CountryName');
            $fields->removeByName('SubLocality');
            $fields->removeByName('AdministrativeArea');
            $fields->removeByName('SubAdministrativeArea');
            foreach ($geoFields->dataFields() as $geoField) {
                $fields->removeByName($geoField->getName());
            }

            $fields->addFieldsToTab('Root.Geo', $geoFields);
        }
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
                    $localityValue = (string) $addressFromIp->getLocality();
                }
                if (!$countryCode) {
                    $countryCode = (string) $addressFromIp->getCountryCode();
                }
                if (!$postalCodeValue) {
                    $postalCodeValue = (string) $addressFromIp->getPostalCode();
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

        $label     = _t('GeoMemberExtension.COUNTRY', 'Country');
        $fields->push($countrydd = new CountryDropdownField('CountryCode',
            _t('GeoMemberExtension.COUNTRY', 'Country'), self::getCountryList(),
            $countryCode));
        $countrydd->setEmptyString('');

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
        $fields->push($tzdd = new DropdownField('Timezone',
            _t('GeoMemberExtension.TIMEZONE', 'Timezone'),
            array_combine($tz, $tz), $timezone));
        $tzdd->setEmptyString('');

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
        if (!$this->owner->Timezone) {
            $this->owner->Timezone = date_default_timezone_get();
        }

        // Auto geocoding
        if (self::$disable_auto_geocode) {
            return false;
        }
        if ($this->shouldBeGeolocalized() || $this->isAddressChanged()) {
            $this->Geocode();
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
        $results = Geocoder::reverseGeocode($this->owner->Latitude,
                $this->owner->Longitude);

        if ($results) {
            $this->owner->StreetNumber = $results->getStreetNumber();
            $this->owner->StreetName   = $results->getStreetName();
            $this->owner->PostalCode   = $results->getPostalCode();
            $this->owner->Locality     = $results->getLocality();
            $this->owner->SubLocality  = $results->getSubLocality();
            $this->owner->CountryName  = $results->getCountry();
            $this->owner->CountryCode  = $results->getCountryCode();
            if (count($results->getAdminLevels())) {
                $all = $results->getAdminLevels();

                /* @var \Geocoder\Model\AdminLevel $firstLevel */
                $firstLevel                      = $all[0];
                $this->owner->AdministrativeArea = $firstLevel->getName();
                if (count($all) > 1) {
                    $secondLevel                        = $all[1];
                    $this->owner->SubAdministrativeArea = $secondLevel->getName();
                }
            }
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
        if ($this->owner->GeolocateOnLocation) {
            $result = Geocoder::geocodeAddress($this->getLocation());
        } else {
            $result = Geocoder::geocodeAddress($this->getFormattedAddress());
        }
        if ($result) {
            $this->owner->Latitude  = $result->getLatitude();
            $this->owner->Longitude = $result->getLongitude();
            return $result;
        }
        return false;
    }

    /**
     * Convenience method to convert a whole array
     * 
     * @param array $arr
     * @return array
     */
    public static function arrayToLeafletMapItems($arr)
    {
        $d = array();
        foreach ($arr as $a) {
            $a = $a->toLeafletMapItem();
            if ($a) {
                $d[] = $a;
            }
        }
        return $d;
    }

    /**
     * Helper function to create items for map
     * @return \LeafletMapItem
     */
    public function toLeafletMapItem()
    {
        $item      = new LeafletMapItem;
        $item->lat = $this->owner->Latitude;
        $item->lon = $this->owner->Longitude;
        if ($this->owner->Number) {
            $item->number = $this->owner->Number;
        }
        if ($this->owner->hasMethod('getLeafletPopup')) {
            $item->popup = $this->owner->getLeafletPopup();
        } else {
            $item->popup = $this->owner->Title;
        }
        if ($this->owner->hasMethod('getLeafletCategory')) {
            $category             = $this->owner->getLeafletCategory();
            $item->category_title = $category->title;
            $item->category_image = $category->image;
        }
        return $item;
    }
}