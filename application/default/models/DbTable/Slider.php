<?php
class Default_Model_DbTable_Slider extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.slider';
    protected $_tableName = 'master.slider';
    protected $_primary = 'id';

    public function topSlider( $limit = null )
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
            ->order('timestamp DESC')
        ;

        if( !empty($limit) ){
            $query->limit( $limit );
        }

        $result = $this->fetchAll( $query );

        if( empty( $result ) ) return null;

        return $result->toArray();
    }

    public function getByID($id = null)
    {
        if( empty($id) ) return false;

        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
            ->where( 'id = ?', $id);

        $result = $this->fetchRow( $query );

        if( empty( $result ) ) return null;
        return $result->toArray();
    }

}