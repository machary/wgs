<?php
/**
 * Datatables untuk CRUD Kesatuan
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_Kesatuan extends App_Datatables
{
	public function getColumns() 
	{
		return array(
            'markas',
            'nama',
            'lokasi',
			'matra',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('public.yonif');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
//		return $this->getTotalRecords();
		$table = new Zend_Db_Table('public.yonif');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('kesatuan' => 'public.yonif'), array(
			'gid', // id
            'markas',
            'nama',
            'lokasi',
            'matra',
		));
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
						case 'nama_kesatuan':
							$query->orWhere('kesatuan."nama_kesatuan" LIKE ?', "%{$this->_search}%");
							break;
						case 'jenis_kesatuan':
							$query->orWhere('kesatuan."jenis_kesatuan" LIKE ?', "%{$this->_search}%");
							break;
						case 'jenis_pasukan':
							$query->orWhere('kesatuan."jenis_pasukan" LIKE ?', "%{$this->_search}%");
							break;
						case 'matra':
							$query->orWhere('kesatuan."matra" LIKE ?', "%{$this->_search}%");
							break;
						case 'nama_atasan':
							$query->orWhere('kesatuanparent."nama_kesatuan" LIKE ?', "%{$this->_search}%");
							break;
						case 'nama_komando':
							$query->orWhere('komando."nama_komando" LIKE ?', "%{$this->_search}%");
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
		$table = new Zend_Db_Table('public.yonif');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('kesatuan' => 'public.yonif'), array(
                'gid', // id
                'markas',
                'nama',
                'lokasi',
                'matra',
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

            $t[2] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[2].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Hati-hati!, menghapus data ini akan berakibat hilangnya data lain. Apakah anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}

		return $result;
	}
}