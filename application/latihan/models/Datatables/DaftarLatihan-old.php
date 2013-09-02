<?php
class Latihan_Model_Datatables_DaftarLatihan extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'tanggal',
            'nama_prosrenmil',
            'nomor',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $table->select()
            ->from('latihan.skenario', array(
            'id',
        ))
        ;
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('latihan.skenario');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('sk' => 'latihan.skenario'), array(
            'id', // first column is ID
            'tanggal',
            'pr.nama_prosrenmil',
            'nomor',
        ))
            ->joinLeft(array('pr' => 'master.M_PROSRENMIL'), 'pr."id_prosrenmil" = sk."prosrenmil_id"', array())
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

            $t[] = '
				<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a> |
				<a href="'.$hUrl->url(array('action'=>'close', 'skenario_id'=>$id)).'">Tutup</a>
			';
            $result[] = $t;
        }
        return $result;
    }
}