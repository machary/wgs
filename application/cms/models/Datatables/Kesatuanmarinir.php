<?php
/**
 * Datatables untuk CRUD Kesatuan
 * @author tajhul.faijin@sangkuriang.co.id
 */
 
class Cms_Model_Datatables_Kesatuanmarinir extends App_Datatables
{
    protected $_divisi = null;
    public function setDivisi($divisi){
        $this->_divisi = $divisi;
    }

    public function table(){
        return new Zend_Db_Table('master.kesatuan_marinir');
    }

	public function getColumns() 
	{
		return array(
            'id',
            'divisi',
            'nama',
            'longitude',
            'latitude',
		);
	}
	
	public function getTotalRecords() 
	{
        $table = $this->table();
		$query = $table->select()
			->from(array('kesatuan' => 'master.kesatuan_marinir'), array('COUNT(id) as amount'))
            ->where("kesatuan.divisi = '$this->_divisi'")
        ;
        $result = $table->fetchRow($query);
        return $result['amount'];
	}
	
	public function getTotalDisplayRecords()
	{
        $table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('kesatuan' => 'master.kesatuan_marinir'), array(
            'id',
            'divisi',
            'nama',
            'longitude',
            'latitude',
		));
        $query->where("kesatuan.divisi = '$this->_divisi'");

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
						case 'nama':
							$query->orWhere('kesatuan."nama" LIKE ?', "%{$this->_search}%");
							break;
					}
				}
			}
		}
	}

	public function retrieveData()
	{
		$table = $this->table();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('kesatuan' => 'master.kesatuan_marinir'), array(
                'id',
                'divisi',
                'nama',
                'longitude',
                'latitude',
			))
            ->where("kesatuan.divisi = '$this->_divisi'")
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;
		$this->_search($query);

		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
            unset($t[1]); //hilangakan kolom divisi
			$id = array_shift($t);

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id,'divisi' => $this->_divisi)).'" onclick="return confirm(\'Apakah anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
        return $result;
	}
}