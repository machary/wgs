<?php
class Peta_Form_Operasional_AddCb extends Zend_Form
{
    public function __construct($option = null)
    {
        $modelPangkalan = new Cms_Model_DbTable_Pangkalan();

        $id = $this->createElement('hidden', 'id');
        $this->addElement($id);

        $cb = $this->createElement('text', 'no_cb_operasional');
        $cb->setAttrib('label-name', 'No Cb')
            ->removeDecorator('Label');
        $this->addElement($cb);

        $wkt =  $this->createElement('text', 'waktu_pembuatan');
        $wkt->setAttrib('label-name', 'Waktu pembuatan')
            ->setAttrib('class', 'datetimepicker')
            ->removeDecorator('Label');
        $this->addElement($wkt);

        $kotama = $this->createElement('select', 'kotama');
        $kotama->setAttrib('label-name', 'Kotama')
               ->removeDecorator('Label')
               ->addMultiOption('', '[PILIH]')
               ->addMultiOption('armatim', 'Armatim')
               ->addMultiOption('armabar', 'Armabar');
        $this->addElement($kotama);

        $pangkalan = $this->createElement('select', 'pangkalan_aju');
        $pangkalan->setAttrib('label-name', 'Pangkalan Aju')
                  ->removeDecorator('Label')
                  ->addMultiOption('', '[PILIH]');
        foreach($modelPangkalan->getAllPangkalan() as $data)
        {
            $pangkalan->addMultiOption($data['idpangkalan'], $data['nama']);
        }
        $this->addElement($pangkalan);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/operasional');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmDecision');
        $this->setLegend('Form Cb Operasional');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}