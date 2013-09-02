<?php
class Cms_Form_Torpedo_EditTorpedoForm extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct();

        $getData = new Cms_Model_DbTable_List();

        $torpedoName = $this->createElement('text', 'TORPEDO_NAME');
        $torpedoName->setAttrib('label-name', 'Nama Torpedo')
            ->setRequired(true)->addValidator('NotEmpty', true, array('message' => 'Nama Misil Harus Di Isis'))
            ->removeDecorator('Label');
        $this->addElement($torpedoName);

        $country = $this->createElement('select', 'COUNTRY_ID');
        $country->setAttrib('label-name', 'Negara Asal Torpedo')
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllCountry() as $negara)
        {
            $country->addMultiOption($negara['COUNTRY_ID'], $negara['COUNTRY_NAME']);
        }
        $this->addElement($country);

        $detectType = $this->createElement('select', 'DETECT_TYPE');
        $detectType->setAttrib('label-name', 'Nama Tipe Misil')
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllDetectType() as $negara)
        {
            $detectType->addMultiOption($negara['DETECT_TYPE'], $negara['DETECT_TYPE_NAME']);
        }
        $this->addElement($detectType);

        $launchType = $this->createElement('select', 'LAUNCH_TYPE');
        $launchType->setAttrib('label-name', 'Nama Tipe Torpedo')
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllLaunchType() as $negara)
        {
            $launchType->addMultiOption($negara['LAUNCH_TYPE'], $negara['LAUNCH_TYPE_NAME']);
        }
        $this->addElement($launchType);

        $beratTorpedo = $this->createElement('text', 'TORPEDO_WEIGHT');
        $beratTorpedo->setAttrib('label-name', 'Berat Torpedo')
            ->removeDecorator('Label');
        $this->addElement($beratTorpedo);

        $diameterTorpedo = $this->createElement('text', 'TORPEDO_DIAMETERS');
        $diameterTorpedo->setAttrib('label-name', 'Diameter Torpedo')
            ->removeDecorator('Label');
        $this->addElement($diameterTorpedo);

        $maxTorpedo = $this->createElement('text', 'TORPEDO_MAX_SPEED');
        $maxTorpedo->setAttrib('label-name', 'Kecepatan Maksimal Torpedo')
            ->removeDecorator('Label');
        $this->addElement($maxTorpedo);

        $range = $this->createElement('text', 'TORPEDO_MAX_RANGE');
        $range->setAttrib('label-name', 'Jarak Maksimal Torpedo')
            ->removeDecorator('Label');
        $this->addElement($range);

        $hitTorpedo = $this->createElement('text', 'TORPEDO_PROB_OF_HIT');
        $hitTorpedo->setAttrib('label-name', 'Daya Hancur Torpedo')
            ->removeDecorator('Label');
        $this->addElement($hitTorpedo);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','cms/missile');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmTorpedo');
        $this->setLegend('Form Torpedo');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}