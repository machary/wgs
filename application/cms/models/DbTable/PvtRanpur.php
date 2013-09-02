<?php
/**
 * Pivot Table Kesatuan <-> Ranpur
 * @author irfan.muslim@sangkuriang.co.id
 */
class Cms_Model_DbTable_PvtRanpur extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'pvt_ranpur';
	protected $_primary = array('id');
	protected $_sequence = false;
	protected $_referenceMap    = array(
		'Kesatuan' => array(
			'columns'           => array('idkesatuan'),
			'refTableClass'     => 'Cms_Model_DbTable_Kesatuan',
			'refColumns'        => array('idkesatuan')
		),
		'Ranpur' => array(
			'columns'           => array('idranpur'),
			'refTableClass'     => 'Cms_Model_DbTable_Ranpur',
			'refColumns'        => array('idranpur')
		)
	);

}