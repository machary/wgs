<?php
class Cms_Model_Mess extends Cms_Model_Crud
{
    protected $_primary = 'idmess';
    protected $_tableSchema = 'master';
    protected $_tableName = 'fasilitas_mess';
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