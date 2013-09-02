<?php
/**
 * Datatables untuk CRUD rol sheet
 * @author Febi
 */
 
class Latihan_Model_Datatables_SheetUpload extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'id_user',
			'datetime',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('latihan.rol_excel_uploaded');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('latihan.rol_excel_uploaded');
		$query = $table->select()
			->from('latihan.rol_excel_uploaded', array(
				'id',
			))
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('latihan.rol_excel_uploaded');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('re' => 'latihan.rol_excel_uploaded'), array(
				'id',
				'id_user',
				"(lu.nrp || ' - ' ||lu.nama) AS pembuat",
				'datetime'
			))
			->joinLeft(array('lu' => 'user.logins'), 'lu."id" = re."id_user"', array())
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
					<a href="'.$hUrl->url(array('action'=>'delsheetupload', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a> |
					<a href="'.$hUrl->url(array('action'=>'read-sheet', 'rol_id'=>$id)).'">Detail</a>
				'
					: 
				'
					<a href="'.$hUrl->url(array('action'=>'read-sheet', 'rol_id'=>$id)).'">Detail</a>
				';

			$result[] = $t;
		}

		return $result;
	}
}