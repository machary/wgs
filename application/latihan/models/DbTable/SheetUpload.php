<?php

class Latihan_Model_DbTable_SheetUpload extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.rol_excel_uploaded';
    protected $_tableName = 'latihan.rol_excel_uploaded';
    protected $_primary = 'id';

    public function getRolByRolID($rolID)
    {
        $query = $this->select()
                 ->from(array('rol' => $this->_tableName))
                 ->where('rol.id = ?', $rolID);
        $result = $this->fetchRow($query);

        if(count($result))
        {
            return $result->toArray();
        }else{
            return null;
        }
    }

}

?>