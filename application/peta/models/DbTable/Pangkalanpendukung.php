<?php
class Peta_Model_DbTable_PangkalanPendukung extends Zend_Db_Table_Abstract
{
    protected $_name = 'cbl_pangkalan';
    protected $_tableName = 'cbl_pangkalan';
    protected $_primary = 'id';

    public function getByID( $id = null )
    {
        if(empty($id)) return false;

        $query = $this->select()->setIntegrityCheck(false)
            ->from($this->_tableName, array('*'))
            ->where('id = ?', $id)
        ;

        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }
}