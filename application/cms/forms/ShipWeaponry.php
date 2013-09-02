<?php
/**
 * Form Ship Weaponry
 * @author Kanwil
 */
 
class Cms_Form_ShipWeaponry extends Zend_Form
{
	public function init()
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'ship-weaponry/form.phtml')) 
		))
		// Bomb
		->addElement('select', 'bomb_id', array(
			'isArray' => true,
			'required' => false,
			'multiple' => false,
			'multiOptions' => Cms_Model_Idname::factory('M_BOMB')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		->addElement('text', 'bomb_count', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		// Radar
		->addElement('select', 'radar_id', array(
			'isArray' => true,
			'required' => false,
			'multiple' => false,
			'multiOptions' => Cms_Model_Idname::factory('M_RADAR')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		->addElement('text', 'radar_count', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		// Sonar
		->addElement('select', 'sonar_id', array(
			'isArray' => true,
			'required' => false,
			'multiple' => false,
			'multiOptions' => Cms_Model_Idname::factory('M_SONAR')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		->addElement('text', 'sonar_count', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('Int'),
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