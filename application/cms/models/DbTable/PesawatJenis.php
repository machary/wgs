<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_PesawatJenis extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.pesawat_jenis';
    protected $_tableName = 'master.pesawat_jenis';
    protected $_primary = 'pesawat_jenis_id';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $search = '', $count)
    {
        $query = $this->select()
                      ->from(array('pjenis' => $this->_tableName),
                             array(
                                 'pjenis.pesawat_jenis_id',
                                 'pjenis.nama',
                                 'pjenis.model'))
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'nama';
                break;
            case 1: $string = 'model';
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
            $whereString = 'LOWER("model") like \'%'.$cari.'%\'';
            $whereString .= ' OR LOWER("nama") like \'%'.$cari.'%\'';
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