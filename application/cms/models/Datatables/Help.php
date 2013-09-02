<?php
class Cms_Model_Datatables_Help extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'nama',
            'description',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('master.help');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('master.help');
        $query = $table->select()
            ->from('master.help', array(
            'id',
        ))
        ;
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('master.help');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('help' => 'master.help'), array(
            'help.id',
            'help.nama',
            'help.description',
        ))
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
				<a href="'.$hUrl->url(array('action'=>'read', 'id'=>$id)).'" target="_blank">Baca</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
            $result[] = $t;
        }
        return $result;
    }
}