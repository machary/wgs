<?php
/**
 * Datatables untuk CRUD Fasilitas Helling Dock
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_FasDock extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama_pangkalan',
			'nama_dock',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.fasilitas_dock');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
//		return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_dock');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('dock' => 'master.fasilitas_dock'))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = dock."idpangkalan"', array());
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
						case 'nama_pangkalan':
							$query->orWhere('pangkalan."nama" LIKE ?', "%{$this->_search}%");
							break;
						case 'nama_dock':
							$query->orWhere('dock."nama" LIKE ?', "%{$this->_search}%");
							break;
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
		$table = new Zend_Db_Table('master.fasilitas_dock');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('dock' => 'master.fasilitas_dock'), array(
				'iddock', // id
				'nama_pangkalan' => 'pangkalan.nama',
				'nama_dock' => 'dock.nama',
			))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = dock."idpangkalan"', array())
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

            $t[1] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[1].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}