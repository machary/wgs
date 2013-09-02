<?php
class Management_Model_DbTable_Login extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.logins';
    protected $_tableName = 'user.logins';
    protected $_primary = 'id';

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0:
                $string = $this->_tableName.'.nrp';
                break;
            case 1:
                $string = $this->_tableName.'.nama';
                break;
            default:
                $string = $this->_tableName.'.username';
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
                    $query->where($this->_tableName.'."nrp" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where($this->_tableName.'."nama" like ' ."'". '%'.$search.'%' . "'");
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

    public function getnrp($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName, array('id','nrp','nama'))
                      ->where("id = '".$id."'")
        ;

        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function updatelogin($stat, $id, $tim)
    {
        if(empty($id))return false;
        if($stat == 'T')
        {
            $data = array(
                'id_team' => $tim
            );
        }
        else if($stat == 'F')
        {
            $data = array(
                'id_team' => 0
            );
        }
        $this->update($data, "id = '".$id."'");
    }

    public function getteam($idTeam)
    {
        $query = $this->select()
                    ->from($this->_tableName)
                    ->where("id_team = '".$idTeam."'")
        ;

        $result = $this->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getpenilai()
    {
        $query = $this->select()
            ->from($this->_tableName)
            ->where("role_id = 3")
        ;

        $result = $this->fetchAll($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getAllPlusRole()
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('logins' => $this->_tableName))
            ->join(array('roles' => 'user.roles'), 'roles."id" = logins."role_id"', array('roles.name'))
            ->order('id')
        ;

        $result = $this->fetchAll( $query );

        return $result;
    }

    public function getAllPlusRoleByTeamKogas( $idTeam, $kogas)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('logins' => $this->_tableName))
            ->join(array('roles' => 'user.roles'), 'roles."id" = logins."role_id"', array('roles.name'))
            ->where("logins.id_team = ?", $idTeam)
        ;

        if($kogas != 0)
        {
            $query->where("roles.kogas = ?", $kogas);
        }

        $query->order('username');

        $result = $this->fetchAll( $query )->toArray(); 

        if(!empty($result))
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    public function getAllLogin()
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName, array('id', 'username'))
        ;

        $result = $this->fetchAll($query);

        return (!empty($result)) ? $result->toArray() : null;
    }

    public function getByLogin($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName)
                    ->where('id = ?', $id)
        ;

        $result = $this->fetchRow($query);

        return (!empty($result)) ? $result->toArray() : null;
    }
}