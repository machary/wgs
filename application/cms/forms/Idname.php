<?php
/**
 */
 
class Cms_Form_Idname extends Zend_Form
{
	public function init()
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'partials/form-idname.phtml'))
		))
		->addElement('text', 'name', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		))
		;
	}
	
}