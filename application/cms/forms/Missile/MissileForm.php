<?php
class Cms_Form_Missile_MissileForm extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct();
        $this->setName('Misil');
        $getData = new Cms_Model_DbTable_List();

        $missileName = $this->createElement('text', 'MISSILE_NAME');
        $missileName->setAttrib('label-name', 'Nama Misil')
                    ->setRequired(true)
                    ->removeDecorator('Label');
                $this->addElement($missileName);

        $country = $this->createElement('select', 'COUNTRY_ID');
        $country->setAttrib('label-name', 'Negara Asal Senjata')
            ->setRequired(true)
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllCountry() as $negara)
        {
            $country->addMultiOption($negara['COUNTRY_ID'], $negara['COUNTRY_NAME']);
        }
        $this->addElement($country);

        $missileType = $this->createElement('select', 'MISSILE_MID_COURSE_TYPE');
        $missileType->setAttrib('label-name', 'Nama Tipe Misil')
            ->setRequired(true)
            ->addMultiOption('', '[PILIH]');
        foreach($getData->selectAllMissileType() as $negara)
        {
            $missileType->addMultiOption($negara['MISSILE_MID_COURSE_TYPE'], $negara['MISSILE_MID_COURSE_TYPE_NAME']);
        }
        $this->addElement($missileType);

        $panjangMisil = $this->createElement('text', 'MISSILE_LENGTH');
        $panjangMisil->setAttrib('label-name', 'Panjang (m)')
            ->removeDecorator('Label');
        $this->addElement($panjangMisil);

        $maxMisil = $this->createElement('text', 'MISSILE_MAX_SPD_KNOT');
        $maxMisil->setAttrib('label-name', 'Kecepatan Maksimal (Km/H)')
            ->removeDecorator('Label');
        $this->addElement($maxMisil);

        $minMisil = $this->createElement('text', 'MISSILE_MIN_RANGE');
        $minMisil->setAttrib('label-name', 'Jarak Minimal (Km)')
            ->removeDecorator('Label');
        $this->addElement($minMisil);

        $beratMisil = $this->createElement('text', 'MISSILE_WEIGHT');
        $beratMisil->setAttrib('label-name', 'Berat (Kg)')
            ->removeDecorator('Label');
        $this->addElement($beratMisil);

        $hitMisil = $this->createElement('text', 'MISSILE_PROB_OF_HIT');
        $hitMisil->setAttrib('label-name', 'Akurasi (%)')
            ->removeDecorator('Label');
        $this->addElement($hitMisil);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','cms/missile');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmMissile');
        $this->setLegend('Form Misil');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}