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
        if ($arg1 === null) {
            $arg1 = new TextField('StreetName', '');
            $arg1->setAttribute('placeholder',
                _t('GeoMemberExtension.STREETNAME'));
            $arg1->setAttribute('style', 'width:300px');
        }
        if ($arg2 === null) {
            $arg2 = new TextField('StreetNumber', '');
            $arg2->setAttribute('placeholder',
                _t('GeoMemberExtension.STREETNUMBER'));
            $arg2->setAttribute('style', 'width:75px');
        }

        $this->streetNameField   = $arg1;
        $this->streetNumberField = $arg2;

        $lang = i18n::get_lang_from_locale(i18n::get_locale());
        if ($lang == 'fr') {
            parent::__construct($arg2, $arg1);
        } else {
            parent::__construct($arg1, $arg2);
        }

        $this->setTitle(_t('GeoMemberExtension.ADDRESSHEADER', 'Address'));
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
        if ($do->StreetName) {
            $this->streetNameField->setValue($do->StreetName);
        }
        if ($do->StreetNumber) {
            $this->streetNumberField->setValue($do->StreetNumber);
        }
    }
}
