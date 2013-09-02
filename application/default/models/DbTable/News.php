<?php
class Default_Model_DbTable_News extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.berita';
    protected $_tableName = 'master.berita';
    protected $_primary = 'id';

    public function topNews( $limit = null )
    {
        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName)
           // ->order('tanggal DESC')
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