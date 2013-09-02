<?php
/**
 * Datatables untuk CRUD Skenario Latihan
 * @author Kanwil
 */

class Management_Model_Login extends App_Datatables
{
    public function getColumns()
    {
        return array(
            'name',
            'username',
            'nama',
            '',
        );
    }

    public function getTotalRecords()
    {
        $table = new Zend_Db_Table('user.logins');
        $query = $table->select()->setIntegrityCheck(false)->from(array( 'logins' => 'user.logins'),array('id'))
                    ->join(array('roles' => 'user.roles'), 'roles."id" = logins."role_id"', null);
        return $table->fetchAll($query)->count();
    }

    public function getTotalDisplayRecords()
    {
        $table = new Zend_Db_Table('user.logins');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('logins' => 'user.logins'), array('id'))
            ->join(array('roles' => 'user.roles'), 'roles."id" = logins."role_id"',null);
        ;
        $this->_search($query);

        return $table->fetchAll($query)->count();
    }

    public function retrieveData()
    {
        $table = new Zend_Db_Table('user.logins');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('logins' => 'user.logins'), array(
            'id', // first column is ID
            'roles.name',
            'username',
            'nama'
        ))
            ->join(array('roles' => 'user.roles'), 'roles."id" = logins."role_id"', null)
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