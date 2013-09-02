<?php
class Default_Model_DbTable_Link extends Zend_Db_Table_Abstract
{
    protected $_name = 'public.link';
    protected $_tableName = 'public.link';
    protected $_primary = 'id';

    public function getAll()
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName);

        $result = $this->fetchAll( $query );

        if( empty( $result ) ) return null;

        return $result->toArray();
    }

}