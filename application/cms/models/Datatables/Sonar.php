<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Sonar
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Sonar extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'SONAR_NAME',
			'SONAR_CATEGORY_NAME',
			'SONAR_MAX_DETECT_RANGE',
			'SONAR_MAX_DEPTH',
			'SONAR_MAX_SPEED',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_Sonar();
		return $table->fetchAll()->count();
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		$table = new Cms_Model_DbTable_Sonar();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('s' => 'master.M_SONAR'), array(
				'SONAR_ID', 
			))
			->join(array('c' => 'master.M_SONAR_CATEGORY'), 'c."SONAR_CATEGORY" = s."SONAR_CATEGORY"', array())
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
		$table = new Cms_Model_DbTable_Sonar();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('s' => 'master.M_SONAR'), array(
				'SONAR_ID', // ID as first column
				'SONAR_NAME',
				'c.SONAR_CATEGORY_NAME',
				'SONAR_MAX_DETECT_RANGE',
				'SONAR_MAX_DEPTH',
				'SONAR_MAX_SPEED',
			))
			->join(array('c' => 'master.M_SONAR_CATEGORY'), 'c."SONAR_CATEGORY" = s."SONAR_CATEGORY"', array())
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
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit.sonar', 'id'=>$id)).'">Ubah</a> | 
				<a href="'.$hUrl->url(array('action'=>'del.sonar', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}