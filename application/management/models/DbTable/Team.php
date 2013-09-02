<?php
class Management_Model_DbTable_Team extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.Team';
    protected $_tableName = 'user.Team';
    protected $_primary = 'id';

    public function getAllData($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('team' => $this->_tableName))
            ->join(array('sken' => 'latihan.skenario'), 'sken."id" = team."kode_skenario"', array('sken.nomor'))
        ;

        $string = '';
        switch($sortColumn)
        {
//            case 0:
//                $string = 'log.nama';
//                break;
//            case 1:
//                $string = 'role.name';
//                break;
            case 0:
                $string = 'team.team_name';
                break;
            case 1:
                $string = 'sken.nomor';
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
//                case 0 :
//                    $query->where('log."nama" like ' . "'" . '%'.$search.'%' . "'");
//                    break;
//                case 1 :
//                    $query->where('role."name" like ' ."'". '%'.$search.'%' . "'");
//                    break;
                case 0 :
                    $query->where('team."team_name" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('sken."nomor" like ' ."'". '%'.$search.'%' . "'");
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

    public function getAllDataAdd($limit, $offset, $sortColumn, $order, $filter, $search, $count, $id_team)
    {
        $table = new Zend_Db_table('user.logins');
        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('log' => 'user.logins'), array('id_log' => 'log.id','log.nrp', 'log.nama','log.username','log.id_team'))
            ->join(array('role' => 'user.roles'), 'role."id" = log."role_id"', array('role.name'))
            ->where('log."role_id" != 1 AND log."role_id" != 2 AND log."role_id" != 3 AND log."role_id" != 9')
            ->where('log."id_team" = '."'".$id_team."'".' OR log."id_team" = '."'0'".'')
        ;

        $string = '';
        switch($sortColumn)
        {
            case 1:
                $string = 'log.nrp';
                break;
            case 2:
                $string = 'log.nama';
                break;
            case 3:
                $string = 'role.name';
                break;
            default:
                $string = 'log.username';
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
                case 1 :
                    $query->where('log."nrp" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('log."nama" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('role."name" like ' ."'". '%'.$search.'%' . "'");
                    break;
            }
        }

        $query->order($string);

        if( $count == false ) {

            $query->limit( $limit, $offset );
            $result = $table->fetchAll( $query );

            if( empty( $result ) ) return false;
            return $result->toArray();

        } else {

            $result = $table->fetchAll( $query );

            if( empty( $result ) ) return false;
            return count( $result );

        }
    }

    public function saveteam($team, $kode)
    {
        $data = array(
            'team_name'     => $team,
            'kode_skenario' => $kode
        );
        $this->insert($data);
    }

    public function gaetteam($tim)
    {
        $query = $this->select()
                    ->from($this->_tableName)
                    ->where("team_name like '%".$tim."%'")
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

    public function gaetidteam($id)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('tim' => $this->_tableName))
            ->join(array('sken' => 'latihan.skenario'), 'sken."id" = tim."kode_skenario"', array('id_skenario' => 'sken.id','sken.nomor'))
            ->where('tim."id" = '."'".$id."'")
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

    public function deleteteam($id)
    {
        $this->delete("id = '".$id."'");
    }

    public function getallteam()
    {
        $query = $this->select()
                    ->from($this->_tableName)
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

    public function updateskenteam($id_team, $id_sken, $nama_team)
    {
        if(empty($id_sken) && empty($nama_team))return false;
        if(empty($id_sken))
        {
            $data = array(
                'team_name'     => $nama_team
            );
        }
        else if(empty($nama_team))
        {
            $data = array(
                'kode_skenario' => $id_sken
            );
        }
        else if(!empty($id_sken) && !empty($nama_team))
        {
            $data = array(
                'kode_skenario' => $id_sken,
                'team_name'     => $nama_team
            );
        }
        $this->update($data, "id = '".$id_team."'");
    }
}