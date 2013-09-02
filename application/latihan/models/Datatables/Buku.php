<?php
/**
 * Datatables untuk CRUD Skenario Latihan
 * @author Kanwil
 */

class Latihan_Model_Datatables_Buku extends App_Datatables
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
            'nomor',
            'buku1',
            'buku2'
        ))
            ->where('closed = 0')
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
                <a href="'.$this->_params['siteUrl'].$t[2].'" target="_blank">Download Buku I</a> |
                <a href="'.$this->_params['siteUrl'].$t[3].'" target="_blank">Download Buku II</a>
			';
            $t[2] = $t[4];
            unset($t[3]);
            unset($t[4]);
            $result[] = $t;
        }
        return $result;
    }
}