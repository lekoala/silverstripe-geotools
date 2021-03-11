<?php

namespace LeKoala\GeoTools\Fields;

use LeKoala\GeoTools\CountriesList;
use SilverStripe\Forms\DropdownField;

class CountryDropdownField extends DropdownField
{
    protected $hasEmptyDefault = true;

    public function __construct($name = 'CountryCode', $title = null, $source = array(), $value = null)
    {
        if ($title === null) {
            $title = _t('CountryDropdownField.TITLE', 'Country');
        }
        if (empty($source)) {
            $source = CountriesList::get();
        }
        $this->setEmptyString(_t('CountryDropdownField.PLEASESELECTACOUNTRY', 'Please select a country'));
        parent::__construct($name, $title, $source, $value);
    }
}
