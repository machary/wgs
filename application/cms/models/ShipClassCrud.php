<?php
/**
 * Model untuk table Ship Class
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_ShipClassCrud extends Cms_Model_Crud
{
	protected $_primary = 'SHIP_CLASS_ID';
	protected $_tableSchema = 'master';
	protected $_tableName = 'M_SHIP_CLASS';
	protected $_foreignKeys = array(
		'SHIP_CATEGORY_ID' => array( // nama kolom yg merupakan foreign key
			'label' => 'Kategori',
			'schema' => 'master',
			'table' => 'M_SHIP_CATEGORY', // nama table yang ditunjuk
			'field' => 'SHIP_CATEGORY_ID', // nama kolom yang ditunjuk
			'display' => 'SHIP_CATEGORY_NAME', // nama kolom dari table yg dijadikan display option
		),
		'COUNTRY_ID' => array( // nama kolom yg merupakan foreign key
			'label' => 'Asal Negara',
			'schema' => 'master',
			'table' => 'M_COUNTRY', // nama table yang ditunjuk
			'field' => 'COUNTRY_ID', // nama kolom yang ditunjuk
			'display' => 'COUNTRY_NAME', // nama kolom dari table yg dijadikan display option
		),
	);

	protected $_exceptForm = array(

		//contoh pengecualian form
		'FONT_ID' => array(
			'tampil' => false, //jika true, sertakan attribut form (mis: required,formtype,dll) dari array ini
		),
	);
}