<?php
/**
 * Model CRUD untuk table cbl_depo
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Crud_DepoPendukung extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cbl_depo';
	protected $_ignoreCols = array('id_cb_logistik');
	protected $_foreignKeys = array(
		'id_pertamina' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pilih Depo Pertamina',
			'schema' => 'public',
			'table' => 'pertamina', // nama table yang ditunjuk
			'field' => 'gid', // nama kolom yang ditunjuk
			'display' => 'nama_depo', // nama kolom dari table yg dijadikan display option
		),
	);
}