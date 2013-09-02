<?php
/**
 * Model untuk table cbl_depo
 * @author : tajhul.faijin@sangkuriang.co.id
 */
 
class Peta_Model_CbldepoCrud extends Cms_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cbl_depo';
	protected $_foreignKeys = array(
		'id_pertamina' => array( // nama kolom yg merupakan foreign key
			'label' => 'Depo',
			'schema' => 'public',
			'table' => 'pertamina', // nama table yang ditunjuk
			'field' => 'gid', // nama kolom yang ditunjuk
			'display' => 'nama_depo', // nama kolom dari table yg dijadikan display option
		),
	);
}