<?php
class Cms_Model_FasilitasLabuhUdara extends Cms_Model_Crud
{
    protected $_primary = 'idlabuh_udara';
    protected $_tableSchema = 'master';
    protected $_tableName = 'fasilitas_labuh_udara';
    protected $_foreignKeys = array(
        'idlanal' => array( // nama kolom yg merupakan foreign key
            'label' => 'Lantamal/Lanal',
            'schema' => 'master',
            'table' => 'pangkalan', // nama table yang ditunjuk
            'field' => 'idpangkalan', // nama kolom yang ditunjuk
            'display' => 'nama', // nama kolom dari table yg dijadikan display option
        ),
		'idpangkalan' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pangkalan',
			'schema' => 'master',
			'table' => 'pangkalan', // nama table yang ditunjuk
			'field' => 'idpangkalan', // nama kolom yang ditunjuk
			'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
    );
}