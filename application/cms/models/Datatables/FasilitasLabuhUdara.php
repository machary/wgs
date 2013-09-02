<?php
/**
 * Datatables untuk CRUD Fasilitas Jaringan Listrik
 * @author Kanwil
 */

class Cms_Model_Datatables_FasilitasLabuhUdara extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'nama_item',
            'jenis_konstruksi',
            'luas',
            'panjang',
            'lebar',
            'kemampuan',
            'kondisi',
            'keterangan',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.fasilitas_labuh_udara');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
		//@edited irfan.muslim@sangkuriang.co.id
//        return $this->getTotalRecords();
		$table = new Zend_Db_Table('master.fasilitas_labuh_udara');
        $query = $table->select()->setIntegrityCheck(false)
			->from(array('lanud' => 'master.fasilitas_labuh_udara'))
			->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = lanud."idpangkalan"', array());
		$this->_search($query);
		return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.fasilitas_labuh_udara');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('lanud' => 'master.fasilitas_labuh_udara'), array(
            'lanud.idlabuh_udara',
            'lanud.idpangkalan',
			'nama' => 'lanal.nama',
            'lanud.nama_item',
            'lanud.jenis_konstruksi',
            'lanud.luas',
            'lanud.panjang',
            'lanud.lebar',
            'lanud.kemampuan',
            'lanud.kondisi',
            'lanud.keterangan'
        ))
            ->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = lanud."idpangkalan"', array())
            ->order($this->_orderColumn.' '.$this->_orderDirection)
            ->limit($this->_limit, $this->_offset)
        ;

		$this->_search($query);

        $raw = $table->fetchAll($query);
        $result = array();
        $hUrl = new Zend_View_Helper_Url();
        foreach ($raw as $row) {
            $temp['nama']                   = $row['nama'];
			$temp['nama_item']         = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$row['idlabuh_udara'])).'">'.$row['nama_item'].'</a>';
//            $temp['nama_item']              = $row['nama_item'];
            $temp['jenis_konstruksi']       = $row['jenis_konstruksi'];
            $temp['luas']                   = $row['luas'];
            $temp['panjang']                = $row['panjang'];
            $temp['lebar']                  = $row['lebar'];
            $temp['kemampuan']              = $row['kemampuan'];
            $temp['kondisi']                = $row['kondisi'];
            $temp['keterangan']             = $row['keterangan'];

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idlabuh_udara'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idlabuh_udara'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}