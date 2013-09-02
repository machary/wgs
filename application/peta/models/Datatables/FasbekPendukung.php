<?php
/**
 * Datatables untuk CRUD Fasbek Pendukung
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Datatables_FasbekPendukung extends App_Datatables
{
	public function get_param()
	{
		return $this->_params;
	}

	public function getColumns()
	{
		return array(
			'nama_pangkalan',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('public.cbl_pangkalan');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
//		return $this->getTotalRecords();
		$table = new Zend_Db_Table('public.cbl_pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('pangpend' => 'public.cbl_pangkalan'))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = pangpend."id_pangkalan"', array());
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
		$x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('pangpend' => 'public.cbl_pangkalan'), array(
				'id', // id
				'nama_pangkalan' => 'pangkalan.nama',
			))
			->join(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = pangpend."id_pangkalan"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;

		$this->_search($query);
		$query->where('id_cb_logistik = ?',$x['cbid']);

		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

//            $t[1] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[1].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}

	public function petaPPendukung()
	{
		$x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ppendukung' => 'public.cbl_pangkalan'))
			->joinLeft(array('mpangkalan' => 'master.pangkalan'), 'mpangkalan."idpangkalan" = ppendukung."id_pangkalan"')
			->joinLeft(array('geopangkalan' => 'public.lanal'), 'geopangkalan."id_master" = ppendukung."id_pangkalan"')
			->where('id_cb_logistik = ?',$x['cbid'])
		;

		return $table->fetchAll($query);
	}
}