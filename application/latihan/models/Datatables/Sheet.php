<?php
/**
 * Datatables untuk CRUD rol sheet
 * @author Febi
 */
 
class Latihan_Model_Datatables_Sheet extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'id_login',
			'updated',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('latihan.rol_excel');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('latihan.rol_excel');
		$query = $table->select()
			->from('latihan.rol_excel', array(
				'id_rol_excel',
			))
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('latihan.rol_excel');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('re' => 'latihan.rol_excel'), array(
				'id_rol_excel',
				'id_login',
				"(lu.nrp || ' - ' ||lu.nama) AS pembuat",
				'updated'
			))
			->joinLeft(array('lu' => 'user.logins'), 'lu."id" = re."id_login"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;
		$this->_search($query);
		
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();

		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$activeUser = $identity->id;
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);
			$login = array_shift($t);

			$t[] = ($login == $activeUser) ? '
					<a href="'.$hUrl->url(array('action'=>'edit', 'rol_id'=>$id)).'">Ubah</a> |
					<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a> |
					<a href="'.$hUrl->url(array('action'=>'detail', 'rol_id'=>$id)).'">Detail</a>
				'
					:
				'
					<a href="'.$hUrl->url(array('action'=>'detail', 'rol_id'=>$id)).'">Detail</a>
				';

			$result[] = $t;
		}

		return $result;
	}
}