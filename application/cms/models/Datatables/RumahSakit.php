<?php
class Cms_Model_Datatables_Rumahsakit extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'nama_rs',
            'kelas',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.fasilitas_rumah_sakit');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
//        return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_rumah_sakit');
        $query = $table->select()->setIntegrityCheck(false)
			->from(array('rs' => 'master.fasilitas_rumah_sakit'), array('rs.idrs', 'rs.nama_rs', 'rs.kelas'))
			->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = rs."idpangkalan"', array('pangkalan.nama'));
		$this->_search($query);
		return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.fasilitas_rumah_sakit');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('rs' => 'master.fasilitas_rumah_sakit'), array('rs.idrs', 'rs.nama_rs', 'rs.kelas'))
            ->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = rs."idpangkalan"', array('pangkalan.nama'))
            ->order($this->_orderColumn.' '.$this->_orderDirection)
            ->limit($this->_limit, $this->_offset)
        ;

		$this->_search($query);

        $raw = $table->fetchAll($query);

        $result = array();
        $hUrl = new Zend_View_Helper_Url();
        foreach ($raw as $row) {
			$temp = array();
            //            $t = array_values($row->toArray());
            //            $id = array_shift($t);
            //            $t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
            //				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
            //            $result[] = $t;
            $temp['nama']       = $row['nama'];
            $temp['nama_rs']    = $row['nama_rs'];

			//@edited irfan.muslim@sangkuriang.co.id
			$temp['kelas'] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$row['idrs'])).'">'.$row['kelas'].'</a>';
//            $temp['kelas']      = $row['kelas'];
			//endedited

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idrs'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idrs'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}