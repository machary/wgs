<?php
class Peta_Form_Operasional_TitikReferensi extends Zend_Form
{
    public function __construct($option = null)
    {
        //$model = new Cms_Model_DbTable_List();

        $longitude = $this->createElement('text', 'longitude');
        $longitude->setAttrib('label-name','Longitude')
            ->setAttrib('id', 'longitude')
            ->setRequired(true)
            ->removeDecorator('Label');
        $this->addElement($longitude);

        $latitude = $this->createElement('text', 'latitude');
        $latitude->setAttrib('label-name', 'Latitude')
            ->setAttrib('id', 'latitude')
            ->setRequired(true)
            ->removeDecorator('label');
        $this->addElement($latitude);

        $keterangan = $this->createElement('textarea', 'keterangan');
        $keterangan->setAttrib('label-name', 'Keterangan')
            ->removeDecorator('label');
        $this->addElement($keterangan);

        $id_divisi = $this->createElement('hidden', 'id_divisi');
        $this->addElement($id_divisi);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/titik.referensi');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmTitikReferensiOperasional');
        $this->setLegend('Form Titik Referensi Operasional');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}