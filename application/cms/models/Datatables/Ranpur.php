<?php
/**
 * Datatables untuk CRUD Ranpur
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_Ranpur extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'jenis',
			'tahun',
			'negara_asal',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.ranpur');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
//		return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.ranpur');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ranpur' => 'master.ranpur'), array(
			'idranpur', // id
			'jenis',
			'tahun',
			'negara_asal',
		))
		;
		$this->_search($query);
		return $table->fetchAll($query)->count();
	}

	/**
	 * Di-override karena postgresql tidak bisa menggunakan column alias di
	 * where clause
	 */
	protected function _search($query)
	{
		if ($this->_search) {
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						default:
							$query->orWhere('"'.$col.'" LIKE ?', "%{$this->_search}%");
							break;
					}
				}
			}
		}
	}

	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.ranpur');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ranpur' => 'master.ranpur'), array(
				'idranpur', // id
				'jenis',
				'tahun',
				'negara_asal',
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

            $t[0] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}