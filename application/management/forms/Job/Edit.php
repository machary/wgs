<?php
class Management_Form_Job_Edit extends Zend_Form
{
    public function __construct($option = null)
    {
        $model = new Management_Model_DbTable_Job();

        $nrp = $this->createElement('text', 'nrp');
        $nrp->setAttrib('label-name', 'NRP')
            ->removeDecorator('Label');
        $this->addElement($nrp);

        $nama = $this->createElement('text', 'nama');
        $nama->setAttrib('label-name', 'Nama')
            ->removeDecorator('Label');
        $this->addElement($nama);

        $jabatan = $this->createElement('select', 'id_jabatan');
        $jabatan->setAttrib('label-name', 'Jabatan')
            ->removeDecorator('Label')
            ->addMultiOption('', '[PILIH]');
        foreach($model->getkogas() as $jab)
        {
            $jabatan->addMultiOption($jab['id_jabatan'], $jab['nama_jabatan']);
        }
        $this->addElement($jabatan);

        $subjabatan = $this->createElement('select', 'id_subjabatan');
        $subjabatan->setAttrib('label-name', 'Jabatan')
            ->removeDecorator('Label')
            ->addMultiOption('', '[PILIH]');
        foreach($model->getsubkogas() as $sub)
        {
            $subjabatan->addMultiOption($sub['id_subjabatan'], $sub['nama_subjabatan']);
        }
        $this->addElement($subjabatan);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','management/job');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmEditJob');
        $this->setLegend('Form Edit Job');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}