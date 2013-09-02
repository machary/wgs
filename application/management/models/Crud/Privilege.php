<?php
/**
 * Model untuk table User Privilege
 * 
 * @author Kanwil
 */
 
class Management_Model_Crud_Privilege extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'user';
	protected $_tableName = 'privileges';
	protected $_customElements = array(
		'name' => array(
			'text',
			'label' => 'Name',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>150)),
			),
			// html
			'size' => 60,
		),
		'actions' => array(
			'text',
			'label' => 'Actions (pisahkan dengan koma ",") "%" artinya semua action',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>150)),
			),
			// html
			'size' => 80,
		),
	);

}
	