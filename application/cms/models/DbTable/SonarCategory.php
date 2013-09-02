<?php
/**
 * @author Kanwil
 */
class Cms_Model_DbTable_SonarCategory extends Zend_Db_Table_Abstract
{
	protected $_schema = 'master';
	protected $_name = 'M_SONAR_CATEGORY';
	protected $_primary = 'SONAR_CATEGORY';

    public function getalldata($limit, $offset, $sortColumn, $order, $filter, $search, $count)
    {
        $query = $this->select()->setIntegrityCheck( false )
            ->from( $this->_schema.'.'.$this->_name )
        ;

        $string = '';
        switch($sortColumn)
        {
            case 0: $string = 'SONAR_CATEGORY_NAME';
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
                    $query->where('SONAR_CATEGORY_NAME like ' . "'" . '%'.$search.'%' . "'");
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