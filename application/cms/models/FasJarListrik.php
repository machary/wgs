<?php
/**
 * Model untuk table fasilitas_jaringan_listrik
 * 
 * @author Kanwil
 */
 
class Cms_Model_FasJarListrik extends Cms_Model_Crud
{
	protected $_primary = 'idjaringanlistrik';
	protected $_tableSchema = 'master';
	protected $_tableName = 'fasilitas_jaringan_listrik';
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