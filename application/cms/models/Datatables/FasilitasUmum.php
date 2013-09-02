<?php
class Cms_Model_Datatables_FasilitasUmum extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama_lanal',
            'nama',
            'jenis',
            'jumlah_unit',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.fasilitas_umum');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
//        return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_umum');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('umum' => 'master.fasilitas_umum'), array('umum.idfasum', 'umum.nama', 'umum.jenis', 'umum.jumlah_unit'))
			->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = umum."idpangkalan"', array('nama_lanal' => 'pangkalan.nama'))
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
						case 'nama':
							$query->orWhere('umum."nama" LIKE ?', "%{$this->_search}%");
							break;
						case 'nama_lanal':
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
        $table = new Zend_Db_Table('master.fasilitas_umum');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('umum' => 'master.fasilitas_umum'), array('umum.idfasum', 'umum.nama', 'umum.jenis', 'umum.jumlah_unit'))
            ->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = umum."idpangkalan"', array('nama_lanal' => 'pangkalan.nama'))
            ->order($this->_orderColumn.' '.$this->_orderDirection)
            ->limit($this->_limit, $this->_offset)
        ;

		$this->_search($query);

        $raw = $table->fetchAll($query);

        $result = array();
        $temp = array();
        $hUrl = new Zend_View_Helper_Url();
        foreach ($raw as $row) {
            //            $t = array_values($row->toArray());
            //            $id = array_shift($t);
            //            $t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
            //				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
            //            $result[] = $t;
            $temp['nama_lanal']         = $row['nama_lanal'];
            $temp['nama']         = $row['nama'];
            $temp['jenis']        = $row['jenis'];
            $temp['jumlah_unit']  = $row['jumlah_unit'];

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idfasum'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idfasum'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}