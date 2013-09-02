<?php
/**
 * Model CRUD untuk table cbl_fasbek
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Crud_FasbekPendukung extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cbl_fasbek';
//	protected $_ignoreCols = array('idcbl_pangkalan');
//	protected $_foreignKeys = array(
//		'idcbl_pangkalan' => array( // nama kolom yg merupakan foreign key
//			'label' => 'Pilih Pangkalan',
//			'schema' => 'master',
//			'table' => 'pangkalan', // nama table yang ditunjuk
//			'field' => 'idpangkalan', // nama kolom yang ditunjuk
//			'display' => 'nama', // nama kolom dari table yg dijadikan display option
//		),
//	);
}