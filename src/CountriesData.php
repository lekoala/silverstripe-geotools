<?php

namespace LeKoala\GeoTools;

use Exception;
use SilverStripe\i18n\Data\Intl\IntlLocales;

/**
 * @author Koala
 */
class CountriesData
{
    const SHORT_NAME = 'ShortName';
    const OFFICIAL_NAME = 'OfficialName';
    const ISO3 = 'ISO3';
    const ISO2 = 'ISO2';
    const UNI = 'UNI';
    const UNDP = 'UNDP';
    const FAOSTAT = 'FAOSTAT';
    const GAUL = 'GAUL';

    /**
     * @var string
     */
    private $file;
    /**
     * @var array<array<mixed>>
     */
    private $data;

    /**
     * @param string $file
     */
    public function __construct($file = null)
    {
        if ($file) {
            $this->setFile($file);
        } else {
            $this->setFile(dirname(dirname(__DIR__)) . '/resources/Geo/countries.csv');
        }
    }

    /**
     * @param string $file
     * @return self
     */
    public static function getInstance($file = null)
    {
        return new self($file);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    private function loadData(): void
    {
        if ($this->data) {
            return;
        }
        if (!is_file($this->file)) {
            throw new Exception("File {$this->file} is not valid");
        }
        if (!is_readable($this->file)) {
            throw new Exception("File {$this->file} is not readable");
        }
        $file = fopen($this->file, "r");
        if ($file === false) {
            throw new Exception("Unable to open stream");
        }
        $arr = [];
        $headers = fgetcsv($file);
        while (!feof($file)) {
            $line = fgetcsv($file);
            if ($line) {
                //@phpstan-ignore-next-line
                $arr[] = array_combine($headers, $line);
            }
        }
        fclose($file);
        $this->data = $arr;
    }
    /**
     * Get the list of all countries
     *
     * @return array<array<mixed>>
     */
    public function getCountries()
    {
        $this->loadData();
        return $this->data;
    }

    /**
     * Convert a code to another
     *
     * @param string $code
     * @param string $from
     * @param string $to
     * @return string|bool
     */
    public function convertCode($code, $from, $to)
    {
        if (!$code) {
            return false;
        }
        $countries = $this->getCountries();
        foreach ($countries as $country) {
            if ($country[$from] == $code) {
                return $country[$to];
            }
        }
        return false;
    }

    /**
     * Convert ISO2 to ISO3
     *
     * @param string $code
     * @return string|bool
     */
    public function convertIso2ToIso3($code)
    {
        return $this->convertCode($code, self::ISO2, self::ISO3);
    }

    /**
     * Convert ISO2 to ISO3
     *
     * @param string $code
     * @return string|bool
     */
    public function convertIso3ToIso2($code)
    {
        return $this->convertCode($code, self::ISO3, self::ISO2);
    }

    /**
     * Get a map of countries as key => value
     *
     * @param string $key
     * @param string $value
     * @return array<int|string,mixed>
     */
    public function toMap($key = 'ISO2', $value = 'ShortName')
    {
        $arr = [];
        foreach ($this->getCountries() as $country) {
            $arr[$country[$key]] = $country[$value];
        }
        return $arr;
    }

    /**
     * Get the country list, using IntlLocales
     *
     * @return array<int|string,mixed>
     */
    public static function getCountryList()
    {
        $intl = new IntlLocales;
        return $intl->getCountries();
    }
}
