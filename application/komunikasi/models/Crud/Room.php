<?php
/**
 * Model untuk table Room BBB
 *
 * @author Hermanet Lay
 */

class Komunikasi_Model_Crud_Room extends App_Model_Crud
{
	protected $_primary 	   = 'room_id';
	protected $_tableSchema    = 'bbb';
	protected $_tableName 	   = 'room';
	protected $_ignoreCols 	   =  array('room_id', 'room_creator');
	protected $_customElements =  array(
		'status' 	=> array(
			'select',
			'label'    		=> 'Status Ruangan',
			'required' 		=> true,
			'multiOptions'	=> array('closed' => 'closed', 'open' => 'open')
		),
		'moderatorPW' => array(
			'text',
			'label'			=> 'Password untuk Moderator',
			'required'		=> true,
		),
		'attendeePW' => array(
			'text',
			'label'			=> 'Password untuk Peserta',
			'required'		=> true,
		),
		'room_name' => array(
			'text',
			'label'			=> 'Nama Ruang Diskusi',
			'required'		=> true,
		),
	);

	public function setFromForm($form, $meetingID, $creatorID)
	{
		if (is_a($form, 'Zend_Form')) {
			$values = $form->getValues();
			foreach ($values as $name => $val) {
				if ($val === '' && !$form->getElement($name)->isRequired()) {
					unset($values[$name]);
				}
			}
			$form = $values;
		}
		$this->_values = $form;
		$this->_values['room_id'] = $meetingID;
		$this->_values['room_creator'] = $creatorID;
	}
}
