<?php
class Ops_Form_Situtasi_Editcog extends Zend_Form
{
    public function __construct($option = null)
    {
        $list = new Cms_Model_DbTable_List();

        $cv = $this->createElement('textarea', 'critical_vinerability');
        $cv->setAttrib('label-name', 'Critical Vunerability')
            ->removeDecorator('Label');
        $this->addElement($cv);

        $cr = $this->createElement('textarea', 'critical_requirement');
        $cr->setAttrib('label-name', 'Critical Requirement')
            ->removeDecorator('Label');
        $this->addElement($cr);

        $cc = $this->createElement('textarea', 'critical_capabilities');
        $cc->setAttrib('label-name', 'Critical Capabilities')
            ->removeDecorator('Label');
        $this->addElement($cc);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/intelijen');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'editcog');
        $this->setLegend('Form Edit');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}