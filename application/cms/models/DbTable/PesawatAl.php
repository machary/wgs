<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_PesawatAl extends App_Model_DbTable
{
    protected $_name = 'master.pesawat_al';
    protected $_tableName = 'master.pesawat_al';
    protected $_primary = 'pesawat_al_id';

    public function getById( $id )
    {
        $query = $this->select()
              ->from(array('pesal' => $this->_tableName), array('*'))
               ->where('pesal.pesawat_al_id = ?', $id)
        ;

        $result = $this->fetchRow( $query );

        return $result->toArray();
    }

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $search = '', $count)
    {
        $query = $this->select()
                      ->from(array('pesal' => $this->_tableName),
                             array(
                                 'pesal.pesawat_al_id',
                                 'pesal.no_lamb',
                                 'pesal.tahun_pakai'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'no_lamb';
                break;
//            case 1: $string = 'model';
//                break;
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
            $whereString = 'LOWER("no_lamb") like \'%'.$cari.'%\'';
//            $whereString .= ' OR LOWER("nama") like \'%'.$cari.'%\'';
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