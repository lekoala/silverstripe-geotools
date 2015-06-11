<?php

/**
 * StreetField
 *
 * @author Koala
 */
class StreetField extends FieldGroup
{

    public function __construct($arg1 = null, $arg2 = null)
    {
        $arg1 = new TextField('StreetName', '');
        $arg1->setAttribute('placeholder', _t('GeoMemberExtension.STREETNAME'));
        $arg1->setAttribute('style', 'width:300px');
        $arg2 = new TextField('StreetNumber', '');
        $arg2->setAttribute('placeholder', _t('GeoMemberExtension.STREETNUMBER'));
        $arg2->setAttribute('style', 'width:80px');

        parent::__construct($arg1, $arg2);

        $this->setTitle(_t('GeoMemberExtension.STREET', 'Street'));
        $this->setFieldHolderTemplate('AddressFieldHolder');
    }

    public function extraClass()
    {
        return 'text '.parent::extraClass();
    }
}