<?php
class Cms_Form_Home_Artikel extends Zend_Form
{
    public function __construct($option = null)
    {
        parent::__construct($option);

        $judul = $this->createElement('text', 'judul');
        $judul->setAttrib('label-name', 'Judul')
                ->setAttrib('class', 'medium')
                ->removeDecorator('Label');
        $this->addElement($judul);

        $tanggal = $this->createElement('text', 'tanggal');
        $tanggal->setAttrib('label-name', 'Tanggal')
                ->removeDecorator('Label')
                ->setAttrib('class', 'datepicker');
        $this->addElement($tanggal);

        $berita = $this->createElement('textarea', 'berita');
        $berita->setAttrib('label-name', 'Berita')
                ->setAttrib('style', 'width: 99.8%;')
                ->removeDecorator('Label');
        $this->addElement($berita);

//        $redirect = $this->createElement('hidden', 'redirect_text');
//        $redirect ->setAttrib('title','cms/artikel');
//        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmBerita');
        $this->setLegend('Form Berita');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}