<?php
/**
 * Pivot Table Ship <-> Bomb
 * @author Kanwil
 */
class Cms_Model_DbTable_PvShipBomb extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'pv_ships_bombs';
	protected $_primary = array('ship_id', 'bomb_id');
	protected $_sequence = false;
	protected $_referenceMap    = array(
		'Ship' => array(
			'columns'           => array('ship_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Ship',
			'refColumns'        => array('SHIP_ID')
		),
		'Bomb' => array(
			'columns'           => array('bomb_id'),
			'refTableClass'     => 'Cms_Model_DbTable_Bomb',
			'refColumns'        => array('BOMB_ID')
		)
	);

}