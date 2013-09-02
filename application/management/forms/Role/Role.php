<?php
class Management_Form_Role_Role extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName('Role');
        $getData = new Cms_Model_DbTable_List();

        $nama = $this->createElement('text', 'name');
        $nama->setAttrib('label-name', 'Nama')
            ->setRequired(true)
            ->removeDecorator('Label');
        $this->addElement($nama);

        $jabatan = $this->createElement('select', 'kogas');
        $jabatan->setAttrib('label-name', 'Jabatan')
            ->setRequired(true)
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '');
        foreach($getData->selectAllJabatan() as $cat)
        {
            $jabatan->addMultiOption($cat['id_jabatan'], $cat['nama_jabatan']);
        }
        $this->addElement($jabatan);

        $panglima = $this->createElement('select', 'panglima');
        $panglima->setAttrib('label-name', 'Panglima')
            ->setRequired(true)
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '')
            ->addMultiOption(1, 'Ya')
            ->addMultiOption(0, 'Tidak');
        $this->addElement($panglima);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','management/role');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmRole');
        $this->setLegend('Form Role');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}