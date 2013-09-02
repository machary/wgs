<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Ship Weaponry
 * @author Kanwil
 */
 
class Cms_Model_Datatables_ShipWeaponry extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'SHIP_NAME',
			'bomb_count',
			'radar_count',
			'sonar_count',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_Ship();
		return $table->fetchAll()->count();
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		return $this->getTotalRecords();
	}
	
	/**
	 * Needs to be overriden
	 * Return the data based on DataTables parameters acquired
	 * @return array an array of rows as sequential arrays
	 */
	public function retrieveData()
	{
		$table = new Cms_Model_DbTable_Ship();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('s' => 'master.M_SHIP'), array(
				's.SHIP_ID', // ID as first column
				'SHIP_NAME' => 'MIN("SHIP_NAME")',
				'bomb_count' => 'coalesce(SUM(b.bomb_count), 0)', // WARNING: coalesce is postgre-specific
				'radar_count' => 'coalesce(SUM(r.radar_count), 0)',
				'sonar_count' => 'coalesce(SUM(sr.sonar_count), 0)',
			))
			->joinLeft(array('b' => 'master.pv_ships_bombs'), 'b."ship_id" = s."SHIP_ID"', array())
			->joinLeft(array('r' => 'master.pv_ships_radars'), 'r."ship_id" = s."SHIP_ID"', array())
			->joinLeft(array('sr' => 'master.pv_ships_sonars'), 'sr."ship_id" = s."SHIP_ID"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
			->group('SHIP_ID')
		;
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t); // ID is taken out
			// insert HTML links
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a>';
			$result[] = $t;
		}
		return $result;
	}
}