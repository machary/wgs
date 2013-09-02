<?php
/**
 * Model CRUD untuk table cbl_rumahsakit
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Crud_RumahsakitPendukung extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cbl_rumahsakit';
	protected $_ignoreCols = array('id_cb_logistik');
	protected $_foreignKeys = array(
		'id_rumahsakit' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pilih Rumahsakit',
			'schema' => 'public',
			'table' => 'rumahsakit', // nama table yang ditunjuk
			'field' => 'gid', // nama kolom yang ditunjuk
			'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
	);
}