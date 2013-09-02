<?php
class Cms_Model_Datatables_Rumah extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'tipe_rumah',
            'jenis_rumah',
            'jabatan',
            'jumlah_unit',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.fasilitas_rumah');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
//        return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_rumah');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('rumah' => 'master.fasilitas_rumah'), array('rumah.idrumah', 'rumah.tipe_rumah', 'rumah.jenis_rumah', 'rumah.jabatan', 'rumah.jumlah_unit'))
			->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = rumah."idpangkalan"', array('pangkalan.nama'))
		;
		$this->_search($query);
		return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.fasilitas_rumah');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('rumah' => 'master.fasilitas_rumah'), array('rumah.idrumah', 'rumah.tipe_rumah', 'rumah.jenis_rumah', 'rumah.jabatan', 'rumah.jumlah_unit'))
            ->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = rumah."idpangkalan"', array('pangkalan.nama'))
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
            $temp['nama']               = $row['nama'];
            $temp['tipe_rumah']         = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$row['idrumah'])).'">'.$row['tipe_rumah'].'</a>';
            $temp['jenis_rumah']        = $row['jenis_rumah'];
            $temp['jabatan']            = $row['jabatan'];
            $temp['jumlah_unit']        = $row['jumlah_unit'];

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idrumah'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idrumah'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}