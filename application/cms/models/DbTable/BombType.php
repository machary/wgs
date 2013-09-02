<?php
/**
 * @author Kanwil
 */
class Cms_Model_DbTable_BombType extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'M_BOMB_TYPE';
	protected $_primary = 'BOMB_TYPE';

    public function getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck( false )
            ->from( $this->_schema.'.'.$this->_name )
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'BOMB_TYPE_NAME';
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
                    $query->where('BOMB_TYPE_NAME like ' . "'" . '%'.$search.'%' . "'");
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