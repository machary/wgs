<?php
class Cms_Model_DbTable_UploadImage extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.slider';
    protected $_tableName = 'master.slider';
    protected $_primary = 'id';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '', $count)
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName);

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'timestamp';
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

    public function del($key = null, $val = null)
    {
        if( empty($key) && empty($val) ) return false;
        try {
            $condition = array( $key .' = ?' => $val);
            $this->delete( $condition );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}