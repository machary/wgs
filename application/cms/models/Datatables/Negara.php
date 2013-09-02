<?php
/**
 * Datatables untuk CRUD Negara
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Negara extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'COUNTRY_NAME',
			'CAPITAL',
			'CONTINENT',
			'POP_TOTAL',
			'POP_MALE',
			'POP_FEM',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.M_COUNTRY');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('master.M_COUNTRY');
		$query = $table->select();
		$this->_search($query);
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.M_COUNTRY');
		$query = $table->select()
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
			$t[0] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';
			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> | 
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}