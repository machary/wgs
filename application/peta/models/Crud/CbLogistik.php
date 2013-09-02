<?php
/**
 * Model CRUD untuk table cb logistik
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Crud_CbLogistik extends App_Model_Crud
{
	protected $_primary = 'idcb_logistik';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cb_logistik';
	protected $_customElements = array(
		'waktu_pembuatan' => array(
			'text',
			'label' => 'Tanggal Pembuatan',
			'class' => 'tgl',
			'required' => true,
		),
		'id_team' => array(
			'hidden',
			'required' => true
		),
	);
	protected $_foreignKeys = array(
		'id_skenario' => array( // nama kolom yg merupakan foreign key
			'label' => 'Skenario',
			'schema' => 'latihan',
			'table' => 'skenario', // nama table yang ditunjuk
			'field' => 'id', // nama kolom yang ditunjuk
			'display' => 'nomor', // nama kolom dari table yg dijadikan display option
		),
	);
}