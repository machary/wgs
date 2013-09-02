<?php
class Peta_Model_DbTable_LogTelegram extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.telegrams';
    protected $_tableName = 'master.telegrams';
    protected $_primary = 'idtelegram';

    public function datalog($limit, $offset, $sortColumn, $order, $filter, $search, $count, $id)
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from( array( 'log' => $this->_tableName ) )
            ->joinLeft( array( 'login' => 'user.logins' ), 'login."id"::text like log."pengirim_id"', array( 'login.username' ) )
            ->joinLeft( array( 'tim' => 'user.Team' ), 'tim."id" = log."team_id"', array( 'tim.team_name' ) )
            ->where( 'log.isdraft = false' )
        ;

        if((string)$id != 0)
        {
            $query->where('log."pengirim_id" = '. "'".$id."'");
        }

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'log.datetime';
            break;
            case 1: $string = 'log.nomor_telegram';
            break;
            case 2: $string = 'log.rahasia';
            break;
            case 3: $string = 'log.segera';
            break;
            case 4: $string = 'login.username';
            break;
            case 7: $string = 'log.jadwal';
            break;
            case 8: $string = 'tim.team_name';
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
                    $query->where('log."datetime" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 1 :
                    $query->where('log."nomor_telegram" like ' ."'". '%'.$search.'%' . "'");
                    break;
                case 2 :
                    $query->where('log."rahasia" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 3 :
                    $query->where('log."segera" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 4 :
                    $query->where('login."username" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 7 :
                    $query->where('log."jadwal" like ' . "'" . '%'.$search.'%' . "'");
                    break;
                case 8 :
                    $query->where('tim."team_name" like ' . "'" . '%'.$search.'%' . "'");
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

    public  function countAll()
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName);

        $result = $this->fetchAll( $query );

        if( empty( $result ) ) return null;
        return count($result->toArray());
    }

    public function getRoleName($id)
    {
        $table = new Zend_Db_Table( 'user.roles' );
        $query = $table->select()->setIntegrityCheck( false )
                    ->from( 'user.roles' )
                    ->where( 'id = ?', $id )
        ;

        $result = $table->fetchRow($query);

        return ( !empty( $result ) ) ? $result->toArray() : null;
    }
}