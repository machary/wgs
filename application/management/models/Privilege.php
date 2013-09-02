<?php
/**
 * Datatables untuk CRUD Skenario Latihan
 * @author Kanwil
 */

class Management_Model_Privilege extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'name',
            'module',
            'controller',
            'actions',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('user.privileges');
        return $table->fetchAll()->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('user.privileges');
        $query = $table->select()
            ->from('user.privileges', array(
            'id',
        ))
        ;
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('user.privileges');
        $query = $table->select()
            ->from('user.privileges', array(
            'id', // first column is ID
            'name',
            'module',
            'controller',
            'actions',
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
				<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
            $result[] = $t;
        }
        return $result;
    }
}