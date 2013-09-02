<?php
/**
 * Pivot Table Ship <-> Radar
 * @author Kanwil
 */
class Cms_Model_DbTable_PvShipRadar extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'pv_ships_radars';
	protected $_primary = array('ship_id', 'radar_id');
	protected $_sequence = false;
	protected $_referenceMap    = array(
		'Ship' => array(
			'columns'           => array('ship_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Ship',
			'refColumns'        => array('SHIP_ID')
		),
		'Radar' => array(
			'columns'           => array('radar_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Radar',
			'refColumns'        => array('RADAR_ID')
		)
	);

}