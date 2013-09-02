<?php
/**
 * Yang berkomunikasi dengan Datatables seputar data Kesatuan Ranpur
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_KesatuanRanpur extends App_Datatables
{
	/**
	 * Return an array containing list of column names used by this class
	 * @return array sequential
	 */
	public function getColumns() 
	{
		return array(
			'nama_kesatuan',
			'jumlah',
			'',
		);
	}
	
	/**
	 * Return the total number of data without any filtering
	 * @return int|string
	 */
	public function getTotalRecords() 
	{
		$table = new Cms_Model_DbTable_Kesatuan();
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
		$table = new Cms_Model_DbTable_Kesatuan();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('a' => 'master.kesatuan'), array(
				'a.idkesatuan', // ID as first column
				'a.nama_kesatuan',
				'jml' => 'count(c."idranpur")'
			))
			->joinLeft(array('b' => 'master.pvt_ranpur'), 'b."idkesatuan" = a."idkesatuan"', array())
			->joinLeft(array('c' => 'master.ranpur'), 'c."idranpur" = b."idranpur"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
			->group(array(
				'a.idkesatuan',
				'a.nama_kesatuan'
			))
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