<?php
class Cms_Model_Datatables_Mess extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'nama_mess',
            'jabatan',
            'jumlah_kamar',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.fasilitas_mess');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
//        return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_mess');
        $query = $table->select()->setIntegrityCheck(false)
			->from(array('mess' => 'master.fasilitas_mess'))
			->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = mess."idpangkalan"');
		$this->_search($query);
		return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.fasilitas_mess');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('mess' => 'master.fasilitas_mess'), array('mess.idmess', 'mess.nama_mess', 'mess.jabatan', 'mess.jumlah_kamar'))
            ->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = mess."idpangkalan"', array('pangkalan.nama'))
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
            $temp['nama_mess']         = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$row['idmess'])).'">'.$row['nama_mess'].'</a>';
            $temp['jabatan']        = $row['jabatan'];
            $temp['jumlah_kamar']            = $row['jumlah_kamar'];

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idmess'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idmess'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}