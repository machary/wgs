<?php
class Cms_Form_Link extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct($option);

        $judul = $this->createElement('text', 'judul');
        $judul->setAttrib('label-name', 'Judul')
                ->setAttrib('class', 'medium')
                ->setRequired(true)
                ->removeDecorator('Label');
        $this->addElement($judul);

        $tautan = $this->createElement('text', 'tautan');
        $tautan->setAttrib('label-name', 'Tautan')
                ->setAttrib('class', 'medium')
                ->removeDecorator('Label');
        $this->addElement($tautan);

        $link = $this->createElement('file', 'file');
        $link->setAttrib('label-name', 'Logo')
                ->setAttrib('class', 'medium')
                ->removeDecorator('Label');
        $this->addElement($link);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmLink');
        $this->setLegend('Form Tautan');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}