<?php
/**
 * @author Kanwil
 */
class Cms_Model_DbTable_RadarEccmType extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'M_RADAR_ECCM_TYPE';
	protected $_primary = 'ECCM_TYPE_ID';

    public function getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck( false )
            ->from( $this->_schema.'.'.$this->_name )
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'ECCM_TYPE_CODE';
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
                    $query->where('ECCM_TYPE_CODE like ' . "'" . '%'.$search.'%' . "'");
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
}