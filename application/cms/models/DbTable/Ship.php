<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_Ship extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.M_SHIP';
    protected $_tableName = 'master.M_SHIP';
    protected $_primary = 'SHIP_ID';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $search = '', $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from(array('ship' => $this->_tableName),
                             array(
                                 'ship.SHIP_ID',
                                 'ship.SHIP_NAME',
                                 'ship.SHIP_NO',
                                 'ship.SHIP_TYPE_DESC'))
                      ->join(array('shipclass' => 'master.M_SHIP_CLASS'), 'shipclass."SHIP_CLASS_ID" = ship."SHIP_CLASS_ID"', array('shipclass.SHIP_CLASS_NAME'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'SHIP_NAME';
                break;
            case 1: $string = 'SHIP_NO';
                break;
            case 2: $string = 'SHIP_CLASS_NAME';
                break;
            case 3: $string = 'SHIP_TYPE_DESC';
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
            $whereString = 'LOWER("SHIP_NAME") like \'%'.$cari.'%\'';
            $whereString .= ' OR LOWER("SHIP_NO") like \'%'.$cari.'%\'';
            $whereString .= ' OR LOWER("SHIP_TYPE_DESC") like \'%'.$cari.'%\'';
            $whereString .= ' OR LOWER("SHIP_CLASS_NAME") like \'%'.$cari.'%\'';
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