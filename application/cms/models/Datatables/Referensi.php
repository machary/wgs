<?php
/**
 * Datatables untuk CRUD Referensi
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Referensi extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama',
			'jenis',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.referensi');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('master.referensi');
		$query = $table->select()
			->from('master.referensi', array(
				'idreferensi',
			))
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.referensi');
		$query = $table->select()
			->from('master.referensi', array(
				'idreferensi',
				'nama',
				'jenis',
			))
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;
		$this->_search($query);
		// jenis
		if (isset($this->_params['jenis'])) {
			$jenis = $this->_params['jenis'];
			$query->where('jenis = ?', $jenis);
		}
		
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

			$t[] = '
				<a href="'.$hUrl->url(array('action'=>'download', 'id'=>$id)).'">Download</a> |
				<a href="'.$hUrl->url(array('action'=>'read', 'id'=>$id)).'" target="_blank">Baca</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
			$result[] = $t;
		}
		return $result;
	}
}