<?php
class Management_Model_DbTable_Role extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.roles';
    protected $_tableName = 'user.roles';
    protected $_primary = 'id';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('role' =>$this->_tableName))
            ->joinLeft(array('jabatan' => 'master.M_JABATAN'), 'role."kogas" = jabatan."id_jabatan"', array('jabatan.nama_jabatan'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'role.name';
            break;
            case 1: $string = 'jabatan.nama_jabatan';
            break;
            case 2: $string = 'role.panglima';
            break;
        }

        if((strtolower($order) == 'asc') || (strtolower($order) == 'undefined'))
        {
            $string .= ' ASC';
        }
        else
        {
            $string .= ' DESC';
        }

        if($filter != '' && $search != '')
        {
            switch($filter)
            {
                case 0 :
                    $query->where('role."name" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('jabatan."nama_jabatan" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('role."panglima" like ' . "'" . '%'.$search.'%' . "'");
                    break;
            }
        }

        $query->order($string);

        if( $count == false ) {

            $query->limit( $limit, $offset );
            $result = $this->fetchAll( $query );

            if( empty( $result ) ) return false;
            return $result->toArray();

        } else {

            $result = $this->fetchAll( $query );

            if( empty( $result ) ) return false;
            return count( $result );

        }
    }

    public function selectRole($id)
    {
        $query = $this->select()
                        ->from($this->_tableName)
                        ->where("id = '".$id."'")
        ;

        $result = $this->fetchRow($query);
        return (!empty($result)) ? $result->toArray() : null;
    }

    public function addRole($value)
    {
        $data = array(
            'name' => $value['name'],
            'kogas' => $value['kogas'],
            'panglima' => $value['panglima']
        );
        $this->insert($data);
    }

    public function updateRole($value, $id)
    {
        $data = array(
            'name' => $value['name'],
            'kogas' => $value['kogas'],
            'panglima' => $value['panglima']
        );
        $this->update($data, "id = '".$id."'");
    }

    public function deleteRole($id)
    {
        $this->delete("id = '".$id."'");
    }
}