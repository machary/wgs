<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Radar
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Radar extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'RADAR_NAME',
			'RADAR_ELEVATION',
			'RADAR_TYPE_CODE',
			'RADAR_MAX_RANGE',
			'RADAR_FREQ',
			'RADAR_JAMM_RANGE',
			'DESCRIPTION',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_Radar();
		return $table->fetchAll()->count();
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		$table = new Cms_Model_DbTable_Radar();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('r' => 'master.M_RADAR'), array(
				'RADAR_ID', // minimalisir column
			))
			->joinLeft(array('t' => 'master.M_RADAR_TYPE'), 't."RADAR_TYPE_ID" = r."RADAR_TYPE"', array())
		;
		$this->_search($query);
		return $table->fetchAll($query)->count();
	}
	
	/**
	 * Needs to be overriden
	 * Return the data based on DataTables parameters acquired
	 * @return array an array of rows as sequential arrays
	 */
	public function retrieveData()
	{
		$table = new Cms_Model_DbTable_Radar();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('r' => 'master.M_RADAR'), array(
				'RADAR_ID', // ID as first column
				'RADAR_NAME',
				'RADAR_ELEVATION',
				't.RADAR_TYPE_CODE',
				'RADAR_MAX_RANGE',
				'RADAR_FREQ',
				'RADAR_JAMM_RANGE',
			))
			->joinLeft(array('t' => 'master.M_RADAR_TYPE'), 't."RADAR_TYPE_ID" = r."RADAR_TYPE"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;
		$this->_search($query);
		
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);
			// insert HTML links
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit.radar', 'id'=>$id)).'">Ubah</a> | 
				<a href="'.$hUrl->url(array('action'=>'del.radar', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}