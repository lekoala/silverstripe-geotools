<?php

namespace LeKoala\GeoTools\Fields;

use ArrayAccess;
use LeKoala\GeoTools\CountriesList;
use SilverStripe\Forms\DropdownField;

class CountryDropdownField extends DropdownField
{
    /**
     * @var bool
     */
    protected $hasEmptyDefault = true;

    /**
     * @param string $name
     * @param string$title
     * @param array<string,string>|ArrayAccess<string,string> $source
     * @param mixed $value
     */
    public function __construct($name = 'CountryCode', $title = null, $source = [], $value = null)
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
