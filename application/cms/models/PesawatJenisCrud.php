<?php
/**
 * Model untuk table Pesawat Jenis
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_PesawatJenisCrud extends Cms_Model_Crud
{
	protected $_primary = 'pesawat_jenis_id';
	protected $_tableSchema = 'master';
	protected $_tableName = 'pesawat_jenis';
}