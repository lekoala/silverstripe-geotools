<?php

/**
 * StreetField
 *
 * @author Koala
 */
class StreetField extends FieldGroup
{
    /**
     *
     * @var TextField
     */
    protected $streetNameField;
    /**
     *
     * @var TextField
     */
    protected $streetNumberField;

    public function __construct($arg1 = null, $arg2 = null)
    {
        $arg1 = new TextField('StreetName', '');
        $arg1->setAttribute('placeholder', _t('GeoMemberExtension.STREETNAME'));
        $arg1->setAttribute('style', 'width:300px');
        $arg2 = new TextField('StreetNumber', '');
        $arg2->setAttribute('placeholder', _t('GeoMemberExtension.STREETNUMBER'));
        $arg2->setAttribute('style', 'width:80px');

        $this->streetNameField   = $arg1;
        $this->streetNumberField = $arg2;

        parent::__construct($arg1, $arg2);

        $this->setTitle(_t('GeoMemberExtension.STREET', 'Street'));
        $this->setFieldHolderTemplate('AddressFieldHolder');
    }

    public function getStreetNameField()
    {
        return $this->streetNameField;
    }

    public function setStreetNameField($streetNameField)
    {
        $this->streetNameField = $streetNameField;
        return $this;
    }

    public function getStreetNumberField()
    {
        return $this->streetNumberField;
    }

    public function setStreetNumberField($streetNumberField)
    {
        $this->streetNumberField = $streetNumberField;
        return $this;
    }

    public function extraClass()
    {
        return 'text '.parent::extraClass();
    }

    public function setValueFrom(DataObjectInterface $do)
    {
        if($do->StreetName) {
            $this->streetNameField->setValue($do->StreetName);
        }
        if($do->StreetNumber) {
            $this->streetNumberField->setValue($do->StreetNumber);
        }
    }
}