<?php
class Cms_Model_Datatables_Pangkat extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'singkatan',
            'level',
            ''
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.pangkat');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
        return $this->getTotalRecords();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.pangkat');
        $query = $table->select()->setIntegrityCheck(false)
            ->from('master.pangkat', array('idpangkat', 'nama', 'singkatan', 'level'))
            //->joinLeft(array('pangkalan' => 'master.pangkalan'), 'pangkalan."idpangkalan" = mess."idpangkalan"', array('pangkalan.nama'))
            ->order($this->_orderColumn.' '.$this->_orderDirection)
            ->limit($this->_limit, $this->_offset)
        ;

        $raw = $table->fetchAll($query);

        $result = array();
        $temp = array();
        $hUrl = new Zend_View_Helper_Url();
        foreach ($raw as $row) {
            $temp['nama']         = $row['nama'];
            $temp['singkatan']    = $row['singkatan'];
            $temp['level']        = $row['level'];

            $temp['Action'] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$row['idpangkat'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$row['idpangkat'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

            array_push($result, array_values($temp));
        }
        return $result;
    }
}