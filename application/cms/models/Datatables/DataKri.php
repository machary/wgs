<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Pokok KRI
 * @author Febi
 */
 
class Cms_Model_Datatables_DataKri extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'nama_kri',
  			'singkatan_kri',
  			'no_lamb_kri',
  			'jenis',
  			'type',
  			'negara_asal',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_DataKri();
		return $table->fetchAll()->count();
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		$table = new Cms_Model_DbTable_DataKri();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('b' => 'master.data_pokok_kri'), array(
				'id_kapal', // minimalisir kolom yg diretrieve
			))
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
		$table = new Cms_Model_DbTable_DataKri();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('b' => 'master.data_pokok_kri'), array(
				'id_kapal', // ID as first column
				'nama_kri',
				'singkatan_kri',
				'no_lamb_kri',
				'jenis',
				'type',
				'negara_asal'
			))
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
			$t[] = '<a href="'.$hUrl->url(array('action'=>'detail', 'id'=>$id)).'">Detail</a> | <a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> | <a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}