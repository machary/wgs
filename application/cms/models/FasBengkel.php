<?php
/**
 * Model untuk table fasilitas_bengkel
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_FasBengkel extends Cms_Model_Crud
{
	protected $_primary = 'idbengkel';
	protected $_tableSchema = 'master';
	protected $_tableName = 'fasilitas_bengkel';
	protected $_foreignKeys = array(
    		'idpangkalan' => array( // nama kolom yg merupakan foreign key
			'label' => 'Pangkalan',
			'schema' => 'master',
			'table' => 'pangkalan', // nama table yang ditunjuk
			'field' => 'idpangkalan', // nama kolom yang ditunjuk
			'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
	);

//JANGAN DIHAPUS CONTOH MEMBUAT ELEMENT FORM PENGECUALIAN
//	protected $_exceptForm = array(
//		'kondisi' => array(
//			'required' => false,
//			'label' => 'Pangkalan',
//			'formtype' => 'select',
////			'ismultiple' => false,
//			'options' => array('rusak' => 'rusak', 'lumayan' => 'lumayan', 'bagus' => 'bagus'),
////			'filter' => array('Int'),
//			'validator' => array(),
//			'class' => '',
//
//		)
//	);
}