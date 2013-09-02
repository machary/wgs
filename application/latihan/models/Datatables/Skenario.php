<?php
/**
 * Datatables untuk CRUD Skenario Latihan
 * @author Kanwil
 */
 
class Latihan_Model_Datatables_Skenario extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'tanggal',
			'nama_prosrenmil',
			'nomor',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('latihan.skenario');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('latihan.skenario');
		$query = $table->select()
			->from('latihan.skenario', array(
				'id',
			))
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('latihan.skenario');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('sk' => 'latihan.skenario'), array(
				'id', // first column is ID
				'tanggal',
				'pr.nama_prosrenmil',
				'nomor',
			))
			->joinLeft(array('pr' => 'master.M_PROSRENMIL'), 'pr."id_prosrenmil" = sk."prosrenmil_id"', array())
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
				<a href="'.$hUrl->url(array('action'=>'index', 'skenario_id'=>$id, 'controller'=>'overview')).'">Overview</a> |
				<a href="'.$hUrl->url(array('action'=>'index', 'skenario_id'=>$id, 'controller'=>'kekuatan.sendiri')).'">Kekuatan Sendiri</a> |
				<a href="'.$hUrl->url(array('action'=>'index', 'skenario_id'=>$id, 'controller'=>'kekuatan.musuh')).'">Kekuatan Musuh</a> |
				<a href="'.$hUrl->url(array('action'=>'buku', 'skenario_id'=>$id, 'controller'=>'skenario')).'">Dokumen</a>
			';
			$result[] = $t;
		}
		return $result;
	}
}