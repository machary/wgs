<?php
/**
 * Model untuk table Ship
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_ShipCrud extends Cms_Model_Crud
{
	protected $_primary = 'SHIP_ID';
	protected $_tableSchema = 'master';
	protected $_tableName = 'M_SHIP';
	protected $_foreignKeys = array(
		'SHIP_CLASS_ID' => array( // nama kolom yg merupakan foreign key
			'label' => 'Class',
			'schema' => 'master',
			'table' => 'M_SHIP_CLASS', // nama table yang ditunjuk
			'field' => 'SHIP_CLASS_ID', // nama kolom yang ditunjuk
			'display' => 'SHIP_CLASS_NAME', // nama kolom dari table yg dijadikan display option
		),
		'SHIP_SYM_ID' => array( // nama kolom yg merupakan foreign key
			'label' => 'Symbol',
			'schema' => 'master',
			'table' => 'M_SHIP_SYMBOL', // nama table yang ditunjuk
			'field' => 'SHIP_SYM_ID', // nama kolom yang ditunjuk
			'display' => 'SHIP_SYM_NAME', // nama kolom dari table yg dijadikan display option
		),
		'negara_id' => array( // nama kolom yg merupakan foreign key
			'label' => 'Negara',
			'schema' => 'master',
			'table' => 'M_COUNTRY', // nama table yang ditunjuk
			'field' => 'COUNTRY_ID', // nama kolom yang ditunjuk
			'display' => 'COUNTRY_NAME', // nama kolom dari table yg dijadikan display option
		),
		'pangkalan_id' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pangkalan',
			'schema' => 'master',
			'table' => 'pangkalan', // nama table yang ditunjuk
			'field' => 'idpangkalan', // nama kolom yang ditunjuk
			'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
	);
	protected $_exceptForm = array(

		//contoh pengecualian form
		'SHIP_LAUNCHED_DATE' => array(
			'required' => true,
//			'label' => 'Pangkalan',
			'formtype' => 'text',
//			'ismultiple' => false,
//			'options' => array('rusak' => 'rusak', 'lumayan' => 'lumayan', 'bagus' => 'bagus'),
			'filter' => array('StringTrim'),
			'validator' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
			'class' => 'tgl',

		),
		'SHIP_LAID_DOWN_DATE' => array(

			'tampil' => true, //ketika field ini tidak ingin tampil diisi false
			'required' => true,
			'formtype' => 'text',
			'filter' => array('StringTrim'),
			'validator' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
			'class' => 'tgl',

		),

	);
}