<?php
/**
 * Model untuk table fasilitas_rumah
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
class Cms_Model_FasRumah extends Cms_Model_Crud
{
	protected $_primary = 'idrumah';
	protected $_tableSchema = 'master';
	protected $_tableName = 'fasilitas_rumah';
	protected $_foreignKeys = array(
		'idpangkalan' => array( // nama kolom yg merupakan foreign key
		'label' => 'Pangkalan',
		'schema' => 'master',
		'table' => 'pangkalan', // nama table yang ditunjuk
		'field' => 'idpangkalan', // nama kolom yang ditunjuk
		'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
	);
}