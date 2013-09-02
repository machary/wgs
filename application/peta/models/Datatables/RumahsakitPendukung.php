<?php
/**
 * Datatables untuk CRUD Rumahsakit Pendukung
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Datatables_RumahsakitPendukung extends App_Datatables
{
	public function get_param()
	{
		return $this->_params;
	}

	public function getColumns()
	{
		return array(
			'nama_rs',
		);
	}
	
	public function getTotalRecords() 
	{
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_rumahsakit');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('rspend' => 'public.cbl_rumahsakit'))
            ->where('id_cb_logistik = ?',$x['cbid']);
        return $table->fetchAll($query)->count();
	}
	
	public function getTotalDisplayRecords()
	{
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_rumahsakit');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('rspend' => 'public.cbl_rumahsakit'))
			->joinLeft(array('geors' => 'public.rumahsakit'), 'geors."gid" = rspend."id_rumahsakit"', array())
            ->where('id_cb_logistik = ?',$x['cbid']);;
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
						case 'nama_rs':
							$query->Where('geors."nama" LIKE ?', "%{$this->_search}%");
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
		$table = new Zend_Db_Table('public.cbl_rumahsakit');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('rspend' => 'public.cbl_rumahsakit'), array(
				'id', // id
                'nama_rs' => 'geors.nama',
                'kelas',
                'daya_tampung',
                'attribute',
			))
			->joinLeft(array('geors' => 'public.rumahsakit'), 'geors."gid" = rspend."id_rumahsakit"', array())
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

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}

	public function petaPendukung()
	{
		$x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_rumahsakit');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('rspend' => 'public.cbl_rumahsakit'))
			->joinLeft(array('geors' => 'public.rumahsakit'), 'geors."gid" = rspend."id_rumahsakit"')
			->where('id_cb_logistik = ?',$x['cbid'])
		;
		return $table->fetchAll($query);
	}
}