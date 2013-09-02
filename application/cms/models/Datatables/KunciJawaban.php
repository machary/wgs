<?php
class Cms_Model_Datatables_KunciJawaban extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'skenario',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.kunci_jawaban');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('master.kunci_jawaban');
        $query = $table->select()
            ->from('master.kunci_jawaban', array(
            'id_kunci_jawaban',
        ))
        ;
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    protected function _search($query)
    {
        if ($this->_search) {
            foreach ($this->_searchable as $col => $isSearchable) {
                if ($isSearchable) {
                    switch ($col) {
                        case 'nama':
                            $query->orWhere('kunci_jawaban."nama" LIKE ?', "%{$this->_search}%");
                            break;
                        case 'skenario':
                            $query->orWhere('skenario."nomor" LIKE ?', "%{$this->_search}%");
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
        $table = new Zend_Db_Table('master.kunci_jawaban');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('key' => 'master.kunci_jawaban'), array(
            'key.id_kunci_jawaban',
            'key.nama',
        ))
            ->join(array('sken' => 'latihan.skenario'), 'sken."id" = key."id_skenario"', array('sken.nomor'))
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
				<a href="'.$hUrl->url(array('action'=>'download', 'id'=>$id)).'">Download</a> |
				<a href="'.$hUrl->url(array('action'=>'read', 'id'=>$id)).'" target="_blank">Baca</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
            $result[] = $t;
        }
        return $result;
    }
}