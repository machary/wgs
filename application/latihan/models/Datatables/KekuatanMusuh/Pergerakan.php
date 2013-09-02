<?php
/**
 * Datatables untuk CRUD Kekuatan Musuh - Pergerakan
 * @author Kanwil
 */
 
class Latihan_Model_Datatables_KekuatanMusuh_Pergerakan extends App_Datatables
{
	// main table name used
	public $tname = 'latihan.kekuatan_musuh_pergerakan';
	public function getColumns() 
	{
		return array(
			'tanggal',
			'waktu',
			'nama_lokasi',
			'longitude',
			'latitude',
			'keterangan',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table($this->tname);
		return $table->fetchAll(
			$table->getAdapter()->quoteInto('id_skenario = ?', $this->_params['skenario_id'])
		)->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table($this->tname);
		$query = $table->select()
			->from($this->tname, array(
				'id',
			))
			->where('id_skenario = ?', $this->_params['skenario_id'])
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table($this->tname);
		$query = $table->select()
			->from($this->tname, array(
				'id', // first column is ID
				'tanggal',
				'waktu',
				'nama_lokasi',
				'longitude',
				'latitude',
				'keterangan',
			))
			->where('skenario_id = ?', $this->_params['skenario_id'])
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
				<a href="'.$hUrl->url(array('action'=>'pergerakan.edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'pergerakan.del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
			$result[] = $t;
		}
		return $result;
	}
}