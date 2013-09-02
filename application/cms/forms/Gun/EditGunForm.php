<?php
class Cms_Form_Gun_EditGunForm extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName('Gun');
        $getData = new Cms_Model_DbTable_List();

        $gunName = $this->createElement('text', 'GUN_NAME');
        $gunName->setAttrib('label-name', 'Nama Senjata')
            ->setRequired(true)
            ->removeDecorator('Label');
        $this->addElement($gunName);

        $category = $this->createElement('select', 'GUN_CATEGORY');
        $category->setAttrib('label-name', 'Kategori Senjata')
            ->setRequired(true)
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllCategory() as $cat)
        {
            $category->addMultiOption($cat['GUN_CATEGORY'], $cat['GUN_CATEGORY_NAME']);
        }
        $this->addElement($category);

        $country = $this->createElement('select', 'COUNTRY_ID');
        $country->setAttrib('label-name', 'Negara Asal Senjata')
            ->setRequired(true)
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllCountry() as $negara)
        {
            $country->addMultiOption($negara['COUNTRY_ID'], $negara['COUNTRY_NAME']);
        }
        $this->addElement($country);

        $caliber = $this->createElement('text', 'GUN_CALIBER');
        $caliber->setAttrib('label-name', 'Kaliber Senjata');
        $this->addElement($caliber);

        $capacity = $this->createElement('text', 'GUN_MAGAZINE_CAPACITY');
        $capacity->setAttrib('label-name', 'Kapasitas Peluru Senjata');
        $this->addElement($capacity);

        $rangeEfective = $this->createElement('text', 'GUN_RANGE_EFFECTIVE');
        $rangeEfective->setAttrib('label-name', 'Jarak Efektiv Menembak');
        $this->addElement($rangeEfective);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','cms/gun');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmGun');
        $this->setLegend('Form Senjata');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}