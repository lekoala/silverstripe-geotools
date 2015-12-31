<?php

/**
 * A simple list of french departments
 *
 * @author Koala
 */
class FrenchDepartmentField extends GroupedDropdownField
{

    public function __construct($name = 'SubAdministrativeArea', $title = null,
                                $source = array(), $value = '', $form = null,
                                $emptyString = null)
    {
        if ($title === null) {
            $title = _t('FrenchAdministrativeAreaField.TITLE', 'Département');
        }
        if (empty($source)) {
            $source = FrenchGeoUtils::getDepartmentsByRegion();
        }
        parent::__construct($name, $title, $source, $value, $form, $emptyString);
        $this->setEmptyString(''); // Allow blank selection
    }

    public function saveInto(\DataObjectInterface $record)
    {
        if (!$this->dataValue()) {
            return;
        }
        $record->setCastedField('AdministrativeArea',
            FrenchGeoUtils::getDepartementRegion($this->dataValue()));
        return parent::saveInto($record);
    }
}
