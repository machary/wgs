<?php
/**
 * Form Kekuatan Sendiri Udara
 * @author Febi
 */

class Latihan_Form_KekuatanSendiriUdara extends Zend_Form
{
	public function __construct($skenarioID)
	{
		$this
			->setMethod('post')
			->setDecorators(array(
			array('ViewScript', array('viewScript' => 'kekuatan-sendiri/form-udara.phtml')) // EDIT ME
		))
			->addElement('select', 'bandara_id', array(
			'required' => true,
			'multiOptions' => Latihan_Model_KekuatanSendiri_Udara::BandaraAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'pangkalan_udara',
					'field' => 'PANGKALAN_ID',
				)),
			),
		))
			->addElement('text', 'nama', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'class' => array('large'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>254)),
			),
		))
			->addElement('hidden', 'skenario_id', array(
			'required' => true,
			'value' => $skenarioID,
			'filters' => array('StringTrim'),
		))
			->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		))
		;
	}
}