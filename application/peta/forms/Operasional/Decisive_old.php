<?php
class Peta_Form_Operasional_Decisive extends Zend_Form
{
    public function __construct($option = null)
    {
        $this->setName('frmDecisive');

        $list = new Cms_Model_DbTable_List();

        $cb = $this->createElement('text','no_cb_operasional');
        $cb->setAttrib('label-name', 'No Cb')
            ->setAttrib('enabled', false)
            ->removeDecorator('Label');
        $this->addElement($cb);

        $target = $this->createElement('select', 'id_target');
        $target->setAttrib('label-name', 'Target')
               ->removeDecorator('Label')
               ->addMultiOption('', '[PILIH]');
        foreach($list->dataintelijen() as $data)
        {
            $target->addMultiOption($data['id'], $data['sandi_operasi']);
        }
        $this->addElement($target);

        $lon =  $this->createElement('text', 'longitude');
        $lon->setAttrib('label-name', 'Longitude')
            ->removeDecorator('Label');
        $this->addElement($lon);

        $lat = $this->createElement('text', 'latitude');
        $lat->setAttrib('label-name', 'Latitude')
            ->removeDecorator('Label');
        $this->addElement($lat);

        $ket = $this->createElement('textarea', 'keterangan');
        $ket->setAttrib('label-name', 'Keterangan')
            ->removeDecorator('Label');
        $this->addElement($ket);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/operasional');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmDecision');
        $this->setLegend('Form Decision');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}