<?php
/**
 * Model untuk table ranpur (kendaraan tempur)
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Ranpur extends Cms_Model_Crud
{
	protected $_primary = 'idranpur';
	protected $_tableSchema = 'master';
	protected $_tableName = 'ranpur';
}