<?php

/**
 * A simple list of belgian provinces
 *
 * @author Koala
 */
class BelgianProvinceField extends GroupedDropdownField
{
    public function __construct($name = 'SubAdministrativeArea', $title = null, $source = array(),
                                $value = '', $form = null, $emptyString = null)
    {
        if($title === null) {
            $title = _t('BelgianProvinceField.TITLE','Province');
        }
        if(empty($source)) {
            $source = BelgianGeoUtils::getProvincesByRegion();
        }
        parent::__construct($name, $title, $source, $value, $form, $emptyString);
        $this->setEmptyString(''); // Allow blank selection
    }

    public function saveInto(\DataObjectInterface $record)
    {
        if(!$this->dataValue()) {
            return;
        }
		$record->setCastedField('AdministrativeArea', BelgianGeoUtils::getProvinceRegion($this->dataValue()));
        return parent::saveInto($record);
    }
}