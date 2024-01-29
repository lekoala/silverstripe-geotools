<?php

namespace LeKoala\GeoTools\FieldType;

use SilverStripe\ORM\FieldType\DBVarchar;
use LeKoala\GeoTools\Fields\TimezoneDropdown;

/**
 * A timezone dbfield
 */
class DBTimezone extends DBVarchar
{
    /**
     * @param string $name
     * @param array<mixed> $options
     */
    public function __construct($name = null, $options = [])
    {
        // The mysql.time_zone_name table has a Name column defined with 64 characters.
        // It makes sense to use VARCHAR(64) for storing this information; that matches the way the names are stored in the system.
        parent::__construct($name, 64, $options);
    }

    /**
     * @param string $title
     * @param mixed $params
     * @return TimezoneDropdown
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        $field = TimezoneDropdown::create($this->name, $title);
        $field->setHasEmptyDefault(true);
        return $field;
    }

    public function FullAlias(): string
    {
        $field = TimezoneDropdown::create('dummy');

        // Use config value or some default values
        // See more https://www.timeanddate.com/time/zones/
        $aliases = $field->hasMethod('normalizedAliases') ? $field->normalizedAliases() : [
            "Gulf Standard Time (GST)" => "Asia/Dubai",
            "Central European Time (CET)" => "Europe/Brussels",
            "Atlantic Standard Time (AST)" => "America/Blanc-Sablon",
            "Eastern Standard Time (EST)" => "America/Panama",
            "Central Standard Time (CST)" => "America/Regina",
            "Mountain Standard Time (MST)" => "America/Phoenix",
            "Pacific Standard Time (PST)" => "America/Los_Angeles",
        ];
        $v = $this->value;
        $flip = array_flip($aliases);
        return $flip[$v] ?? $v;
    }

    public function ShortAlias(): string
    {
        $alias = $this->FullAlias();
        $parts = explode("(", $alias);
        if (isset($parts[1])) {
            return trim($parts[1], ')');
        }
        return $parts[0];
    }
}
