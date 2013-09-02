<?php
/**
 * Model untuk table kesatuan
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Kesatuan extends Cms_Model_Crud
{
	protected $_primary = 'idkesatuan';
	protected $_tableSchema = 'master';
	protected $_tableName = 'kesatuan';
	protected $_foreignKeys = array(
        'idparent' => array( // nama kolom yg merupakan foreign key
            'label' => 'Komando Pelaksana',
            'schema' => 'master',
            'table' => 'kesatuan', // nama table yang ditunjuk
            'field' => 'idkesatuan', // nama kolom yang ditunjuk
            'display' => 'nama_kesatuan', // nama kolom dari table yg dijadikan display option
        ),
        'idkomando' => array( // nama kolom yg merupakan foreign key
            'label' => 'Komando Utama',
            'schema' => 'master',
            'table' => 'komando', // nama table yang ditunjuk
            'field' => 'idkomando', // nama kolom yang ditunjuk
            'display' => 'nama_komando', // nama kolom dari table yg dijadikan display option
        ),
	);


}