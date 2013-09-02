<?php
class Peta_Form_SetFolder extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName('set_folder');
        $getData = new Cms_Model_DbTable_List();

        $latihan = $this->createElement('select', 'parent_id_folder_telegram');
        $latihan->setAttrib('label-name', 'Latihan')
            ->setAttrib('id', 'latihan')
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '');
        foreach($getData->selectFolderTelegram() as $lat)
        {
            $latihan->addMultiOption($lat['id'], $lat['nama']);
        }
        $this->addElement($latihan);

        $category = $this->createElement('select', 'child_id_folder_telegram');
        $category->setAttrib('label-name', 'Hari')
            ->setAttrib('id', 'hari')
            ->setRegisterInArrayValidator(false)
            ->removeDecorator('Label')
            ->addMultiOption('', '');
        $this->addElement($category);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','cms/gun');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'setFolder');
        $this->setLegend('Set Folder');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}