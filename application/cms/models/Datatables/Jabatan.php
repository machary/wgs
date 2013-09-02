<?php
/**
 * DataTables untuk Jabatan
 *
 * @author Febi
 */
 
class Cms_Model_Datatables_Jabatan extends App_Datatables
{
	// bisa digunakan untuk method _table()
	protected $_tableName = 'master.M_JABATAN';
	protected $_table = null;
	
	/**
	 * @return Zend_Db_Table table utama yang digunakan
	 */
	public function table() 
	{
		if (!isset($this->_table)) {
			$this->_table = new Zend_Db_Table($this->_tableName);
		}
		return $this->_table;
	}
	
	/**
	 * @return Zend_Db_Table_Select terhadap table utama dengan 1 kolom 'amount'
	 */
	protected function _countQuery()
	{
		$table = $this->table();
		return $table->select()
			->from($this->_tableName, array(
				'amount' => 'COUNT(*)',
			))
		;
	}
	
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'nama_jabatan',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		return $this->table()->fetchRow($this->_countQuery())->amount;
	}
	
	/**
	 * Return the total number of data with current filtering (if any)
	 * @return int|string
	 */
	public function getTotalDisplayRecords()
	{
		$query = $this->_countQuery();
		$this->_search($query);
		return $this->table()->fetchRow($query)->amount;
	}
	
	/**
	 * Needs to be overriden
	 * Return the data based on DataTables parameters acquired
	 * @return array an array of rows as sequential arrays
	 */
	public function retrieveData()
	{
		$table = $this->table();
		$query = $table->select()
			->from($this->_tableName, array(
				'id_jabatan', // first column is ID
				'nama_jabatan'
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

			$t[] = '
				<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>	
			';
			$result[] = $t;
		}
		return $result;
	}
}