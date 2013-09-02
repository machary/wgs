<?php
/**
 * Form Radar
 * @author Kanwil
 */
 
class Cms_Form_Radar extends Zend_Form
{
	public function init() 
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'radar/form-radar.phtml')) 
		))
		->addElement('text', 'name', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('text', 'elevation', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('select', 'type', array(
			'required' => true,
			'multiOptions' => Cms_Model_Idname::factory('M_RADAR_TYPE')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'M_RADAR_TYPE',
					'field' => 'RADAR_TYPE_ID',
				)),
			),
		))
		->addElement('text', 'max_range', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'freq', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'jamm_range', array(
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