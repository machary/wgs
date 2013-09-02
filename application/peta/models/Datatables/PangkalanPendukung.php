<?php
/**
 * Datatables untuk CRUD PAngkalan Pendukung
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Datatables_PangkalanPendukung extends App_Datatables
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
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_pangkalan');
        $query = $table->select()->setIntegrityCheck(false)
            ->where('id_cb_logistik = ?',$x['cbid']);
		return $table->fetchAll($query)->count();
	}
	
	public function getTotalDisplayRecords()
	{
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
            ->from(array('pangpend' => 'public.cbl_pangkalan'))
            ->joinLeft(array('pangkalan' => 'public.lanal'), 'pangkalan."gid" = pangpend."id_pangkalan"', array())
            ->where('id_cb_logistik = ?',$x['cbid']);
		$this->_search($query);
//        echo $query;exit;
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
							$query->Where('pangkalan."lanal" LIKE ?', "%{$this->_search}%");
							break;
						default:
							$query->Where('"'.$col.'" LIKE ?', "%{$this->_search}%");
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
				'nama_pangkalan' => 'pangkalan.lanal',
			))
			->joinLeft(array('pangkalan' => 'public.lanal'), 'pangkalan."gid" = pangpend."id_pangkalan"', array())
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

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah Data</a> |
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
			->joinLeft(array('geopangkalan' => 'public.lanal'), 'geopangkalan."gid" = ppendukung."id_pangkalan"')
			->where('id_cb_logistik = ?',$x['cbid'])
		;
		if ($table->fetchAll($query)->count()) :
			return $table->fetchAll($query);
		else:
			return null;
		endif;

	}
}