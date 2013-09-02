<?php
/**
 * Form Sonar
 * @author Kanwil
 */
 
class Cms_Form_Sonar extends Zend_Form
{
	public function init()
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'sonar/form-sonar.phtml')) 
		))
		->addElement('text', 'sonar_name', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('select', 'sonar_category', array(
			'required' => true,
			'multiOptions' => Cms_Model_Idname::factory('M_SONAR_CATEGORY')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'M_SONAR_CATEGORY',
					'field' => 'SONAR_CATEGORY',
				)),
			),
		))
		->addElement('text', 'sonar_max_detect_range', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'sonar_max_depth', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'sonar_max_speed', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('textarea', 'description', array(
			'required' => false,
			'filters' => array('StringTrim'),
			'validators' => array(
			),
		))
		
		->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		))
		;
	}
}