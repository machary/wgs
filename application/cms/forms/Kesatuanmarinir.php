<?php
/**
 * @author tajhul.faijin@sangkuriang.co.id
 */
 
class Cms_Form_Kesatuanmarinir extends Zend_Form
{
    public function __construct($option = null)
    {
        $nama = $this->createElement('text', 'nama');
        $nama->setAttrib('label-name', 'Nama')
                    ->setAttrib('id', 'nama')
                    ->setRequired(true)
                    ->setAttrib('style', 'width:250px;')
                    ->removeDecorator('Label');
        $this->addElement($nama);

        $longitude = $this->createElement('text', 'longitude');
        $longitude->setAttrib('label-name', 'Longitude')
                    ->setAttrib('id', 'longitude')
                    ->setAttrib('style', 'width:250px;')
                    ->setRequired(true)
                    ->removeDecorator('Label');
        $this->addElement($longitude);

        $latitude = $this->createElement('text', 'latitude');
        $latitude->setAttrib('label-name', 'Latitude')
                    ->setAttrib('id', 'latitude')
                    ->setAttrib('style', 'width:250px;')
                    ->setRequired(true)
                    ->removeDecorator('Label');
        $this->addElement($latitude);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmKesatuan');
        $this->setLegend('Form Data');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}