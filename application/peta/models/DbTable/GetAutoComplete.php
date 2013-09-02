<?php
class Peta_Model_DbTable_GetAutoComplete extends Zend_Db_Table_Abstract
{
    protected $_name = 'kabupaten';
    protected $_tableName = 'kabupaten';
    protected $_primary = 'gid';

    public function addAutoComplete()
    {
        $data = $this->select()->setIntegrityCheck(false)
             ->from($this->_tableName, 'kabupaten')
        ;

        $result = $this->fetchAll($data);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
//        print_r($result);
    }
}