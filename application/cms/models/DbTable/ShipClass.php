<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_ShipClass extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.M_SHIP_CLASS';
    protected $_tableName = 'master.M_SHIP_CLASS';
    protected $_primary = 'SHIP_CLASS_ID';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $search = '', $count)
    {
        $query = $this->select()
                      ->from(array('class' => $this->_tableName),
                             array(
                                 'class.SHIP_CLASS_ID',
                                 'class.SHIP_CLASS_NAME',
                                 'class.SHIP_CLASS_DESCRIPTION'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'SHIP_CLASS_NAME';
                break;
            case 1: $string = 'SHIP_CLASS_DESCRIPTION';
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

        if($search != '')
        {
            $cari = strtolower($search);
            $whereString = 'LOWER("SHIP_CLASS_NAME") like \'%'.$cari.'%\'';
            $whereString .= ' OR LOWER("SHIP_CLASS_DESCRIPTION") like \'%'.$cari.'%\'';
            $query->where($whereString);
        }

        if( $count == false ) {

            if( $string != '')
            {
                $query->order( $string );
            }

            $query->limit( $limit, $offset );
            $result = $this->fetchAll( $query );

//            echo $query;
//            exit;

            if( empty( $result ) ) return false;
            return $result->toArray();

        } else {

            $result = $this->fetchAll( $query );

            if( empty( $result ) ) return false;
            return count( $result );

        }
    }

}