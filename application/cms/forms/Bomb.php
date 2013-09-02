<?php
/**
 * Form Bomb
 * @author Kanwil
 */
 
class Cms_Form_Bomb extends Zend_Form
{
	public function init()
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'bomb/form-bomb.phtml')) // EDIT ME
		))
		->addElement('select', 'bomb_type', array(
			'required' => true,
			'multiOptions' => Cms_Model_Idname::factory('M_BOMB_TYPE')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'M_BOMB_TYPE',
					'field' => 'BOMB_TYPE',
				)),
			),
		))
		->addElement('select', 'country_id', array(
			'required' => true,
			'multiOptions' => Cms_Model_Idname::factory('M_COUNTRY')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'M_COUNTRY',
					'field' => 'COUNTRY_ID',
				)),
			),
		))
		->addElement('text', 'bomb_name', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('text', 'bomb_warhead_weight', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'bomb_range_max', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('Float'),
			),
		))
		->addElement('text', 'bomb_prob_of_hit', array(
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