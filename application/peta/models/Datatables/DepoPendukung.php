<?php
/**
 * Datatables untuk CRUD Depo Pendukung
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Datatables_DepoPendukung extends App_Datatables
{
	public function get_param()
	{
		return $this->_params;
	}

	public function getColumns()
	{
		return array(
			'nama_depo',
		);
	}
	
	public function getTotalRecords() 
	{
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_depo');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('depopend' => 'public.cbl_depo'));
        $query->where('id_cb_logistik = ?',$x['cbid']);
        return $table->fetchAll($query)->count();
	}
	
	public function getTotalDisplayRecords()
	{
        $x = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_depo');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('depopend' => 'public.cbl_depo'))
			->joinLeft(array('depoper' => 'public.pertamina'), 'depoper."gid" = depopend."id_pertamina"', array());
		$query->where('id_cb_logistik = ?',$x['cbid']);
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
						case 'nama_depo':
							$query->Where('depoper."nama_depo" LIKE ?', "%{$this->_search}%");
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
		$table = new Zend_Db_Table('public.cbl_depo');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('depopend' => 'public.cbl_depo'), array(
				'id', // id
				'nama_depo' => 'depoper.nama_depo',
			))
			->join(array('depoper' => 'public.pertamina'), 'depoper."gid" = depopend."id_pertamina"', array())
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
		$param = $this->get_param();
		$table = new Zend_Db_Table('public.cbl_depo');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('depo' => 'public.cbl_depo'))
			->joinLeft(array('pertamina' => 'public.pertamina'), 'pertamina."gid" = depo."id_pertamina"')
			->where('id_cb_logistik = ?',$param['cbid'])
		;

		return $table->fetchAll($query);
	}
}