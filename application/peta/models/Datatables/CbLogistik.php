<?php
/**
 * Datatables untuk Cb Logistik
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Datatables_CbLogistik extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'no_cb',
			'waktu_pembuatan',
			'nomor_skenario',
		);
	}
	
	public function getTotalRecords() 
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $table = new Zend_Db_Table('public.cb_logistik');
        $query = $table->select()->setIntegrityCheck(false);

        if ($identity->id_team > 0) {
            $query->where('"id_team" = '.$identity->id_team);
        }
		return $table->fetchAll($query)->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$table = new Zend_Db_Table('public.cb_logistik');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('logistik' => 'public.cb_logistik'), array(
			'idcb_logistik', // id
		))
			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = logistik."id_skenario"', array())
		;

		if ($identity->id_team > 0) {
			$query->where('logistik."id_team" = '.$identity->id_team);
		}

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
			$str = '';
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						case 'nomor_skenario':
                            $query->Where('skenario."nomor" LIKE ?', "%{$this->_search}%");
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
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$table = new Zend_Db_Table('public.cb_logistik');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('logistik' => 'public.cb_logistik'), array(
				'idcb_logistik', // id
				'no_cb',
				'waktu_pembuatan',
				'nomor_skenario' => 'skenario.nomor',
			))
			->join(array('skenario' => 'latihan.skenario'), 'skenario."id" = logistik."id_skenario"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;


		if ($identity->id_team > 0) {
			$query->where('logistik."id_team" = '.$identity->id_team);
		}

		$this->_search($query);

		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

            $t[0] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a> |
				<a href="'.$hUrl->url(array('action'=>'detail', 'cbid'=>$id)).'">Detail</a>
				';

			$result[] = $t;
		}
		return $result;
	}

}