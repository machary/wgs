<?php
/**
 * Datatables untuk CRUD Fasilitas Perbekalan
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Fasbek extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama_pangkalan',
			'jumlah',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.pangkalan');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		return $this->getTotalRecords();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('pangkalan' => 'master.pangkalan'), array(
				'pangkalan.idpangkalan', // id
				'nama_pangkalan' => 'MIN(pangkalan.nama)', 
				'jumlah' => 'coalesce(COUNT(fasbek.idfasbek), 0)',
			))
			->joinLeft(array('fasbek' => 'master.fasbek'), 'fasbek."idpangkalan" = pangkalan."idpangkalan"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
			->group('pangkalan.idpangkalan')
		;
		
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a>';
			$result[] = $t;
		}
		return $result;
	}
}