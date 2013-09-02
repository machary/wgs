<?php

class Latihan_Model_DbTable_Sheet extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.rol_excel';
    protected $_tableName = 'latihan.rol_excel';
    protected $_primary = 'id_rol_excel';
	protected $_lastChanged;

    public function getSheetByRolID($rolID)
    {
        $query = $this->select()
                 ->from(array('rol' => $this->_tableName))
                 ->where('rol.id_rol_excel = ?', $rolID);
        $result = $this->fetchRow($query);

        if(count($result))
        {
            $result = $result->toArray();
			return $result['sheet'];
        }else{
            return null;
        }
    }

    public function getRolByRolID($rolID)
    {
        $query = $this->select()
                 ->from(array('rol' => $this->_tableName))
                 ->where('rol.id_rol_excel = ?', $rolID);
        $result = $this->fetchRow($query);

        if(count($result))
        {
            return $result;
        }else{
            return null;
        }
    }

}

?>