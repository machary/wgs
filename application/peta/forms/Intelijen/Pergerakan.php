<?php
class Peta_Form_Intelijen_Pergerakan extends Zend_Form
{
    public function __construct($option = null)
    {
        $list = new Cms_Model_DbTable_List();

        $simbol = $this->createElement('select', 'simbol_pergerakan');
        $simbol->setAttrib('label-name', 'Simbol Pergerakan')
                ->setAttrib('id', 'simbol-pergerakan')
                ->removeDecorator('Label')
                ->addMultiOption('', '[Pilih]');
        foreach($list->selectAllSymPergerakan() as $data)
        {
	        $simbol->addMultiOption($data['id'], $data['nama']);
        }
        $this->addElement($simbol);

        $simbol_value = $this->createElement('hidden', 'simbol_value');
        $simbol_value->setAttrib('id', 'simbol_value');
        $this->addElement($simbol_value);

        $lonlat = $this->createElement('textarea', 'lonlat');
        $lonlat->setAttrib('label-name', 'Longitude & Latitude')
                    ->setAttrib('id', 'point')
                    ->removeDecorator('Label');
        $this->addElement($lonlat);

        $rotation = $this->createElement('textarea', 'rotation');
        $rotation->setAttrib('label-name', 'Rotation')
                ->setAttrib('id', 'rotation-value')
                ->removeDecorator('Label');
        $this->addElement($rotation);

        $size = $this->createElement('textarea', 'size');
        $size->setAttrib('label-name', 'Size')
                ->setAttrib('id', 'size-simbol')
                ->removeDecorator('Label');
        $this->addElement($size);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/intelijen');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmPergerakan');
        $this->setLegend('Form Pergerakan');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}