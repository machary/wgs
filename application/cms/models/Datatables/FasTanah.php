<?php
/**
 * Datatables untuk CRUD Fasilitas Bengkel
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_FasTanah extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama_pangkalan',
			'status_tanah',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.fasilitas_tanah');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
//		return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_tanah');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('tanah' => 'master.fasilitas_tanah'), array(
			'idfasilitastanah', // id
			'nama_pangkalan' => 'pangkalan.nama',
			'status_tanah',
		))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = tanah."idpangkalan"', array())
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
						case 'nama_pangkalan':
							$query->orWhere('pangkalan."nama" LIKE ?', "%{$this->_search}%");
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
		$table = new Zend_Db_Table('master.fasilitas_tanah');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('tanah' => 'master.fasilitas_tanah'), array(
				'idfasilitastanah', // id
				'nama_pangkalan' => 'pangkalan.nama',
				'status_tanah',
			))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = tanah."idpangkalan"', array())
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