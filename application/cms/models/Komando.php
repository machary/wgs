<?php
/**
 * Model untuk table komando
 * Contoh pemakaian protected $_foreignKeys
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Komando extends Cms_Model_Crud
{
	protected $_primary = 'idkomando';
	protected $_tableSchema = 'master';
	protected $_tableName = 'komando';
}