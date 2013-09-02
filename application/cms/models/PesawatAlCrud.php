<?php
/**
 * Model untuk table Ship
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_PesawatAlCrud extends App_Model_Crud
{
	protected $_primary = 'pesawat_al_id';
	protected $_tableSchema = 'master';
	protected $_tableName = 'pesawat_al';
	protected $_foreignKeys = array(
		'pesawat_jenis_id' => array( // nama kolom yg merupakan foreign key
			'label' => 'Jenis Pesawat',
			'schema' => 'master',
			'table' => 'pesawat_jenis', // nama table yang ditunjuk
			'field' => 'pesawat_jenis_id', // nama kolom yang ditunjuk
			'display' => 'nama', // nama kolom dari table yg dijadikan display option
		),
	);
    protected $_customElements = array(
   		'gambar' => array(
   			'file', // element pertama adalah jenis form element
   			'label' => 'Gambar',
   		),
   	);


}