<?php
/**
 * Pivot Table Ship <-> Sonar
 * @author Kanwil
 */
class Cms_Model_DbTable_PvShipSonar extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'pv_ships_sonars';
	protected $_primary = array('ship_id', 'sonar_id');
	protected $_sequence = false;
	protected $_referenceMap    = array(
		'Ship' => array(
			'columns'           => array('ship_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Ship',
			'refColumns'        => array('SHIP_ID')
		),
		'Sonar' => array(
			'columns'           => array('sonar_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Sonar',
			'refColumns'        => array('SONAR_ID')
		)
	);

}