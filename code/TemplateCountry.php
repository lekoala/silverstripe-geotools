<?php

/**
 * A country that can be used in a template as is
 *
 * @author Koala
 */
class TemplateCountry extends Geocoder\Model\Country
{
    public function forTemplate() {
        return $this->getName();
    }
}