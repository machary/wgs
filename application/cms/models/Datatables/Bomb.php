<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Bomb
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Bomb extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'BOMB_TYPE_NAME',
			'COUNTRY_NAME',
			'BOMB_NAME',
			'BOMB_WARHEAD_WEIGHT',
			'BOMB_RANGE_MAX',
			'BOMB_PROB_OF_HIT',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_Bomb();
		return $table->fetchAll()->count();
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		$table = new Cms_Model_DbTable_Bomb();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('b' => 'master.M_BOMB'), array(
				'BOMB_ID', // minimalisir kolom yg diretrieve
			))
			->join(array('t' => 'master.M_BOMB_TYPE'), 't."BOMB_TYPE" = b."BOMB_TYPE"', array())
			->join(array('c' => 'master.M_COUNTRY'), 'c."COUNTRY_ID" = b."COUNTRY_ID"', array())
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
		$table = new Cms_Model_DbTable_Bomb();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('b' => 'master.M_BOMB'), array(
				'BOMB_ID', // ID as first column
				't.BOMB_TYPE_NAME','c.COUNTRY_NAME','b.BOMB_NAME',
				'b.BOMB_WARHEAD_WEIGHT','b.BOMB_RANGE_MAX','b.BOMB_PROB_OF_HIT',
			))
			->join(array('t' => 'master.M_BOMB_TYPE'), 't."BOMB_TYPE" = b."BOMB_TYPE"', array())
			->join(array('c' => 'master.M_COUNTRY'), 'c."COUNTRY_ID" = b."COUNTRY_ID"', array())
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
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit.bomb', 'id'=>$id)).'">Ubah</a> | 
				<a href="'.$hUrl->url(array('action'=>'del.bomb', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}