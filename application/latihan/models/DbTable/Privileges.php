<?php
class Latihan_Model_DbTable_Privileges extends Zend_Db_Table_Abstract
{
    protected $_name = 'user.roles';
    protected $_tableName = 'user.roles';
    protected $_primary = 'id';

    public function checkRolesPrivileges($module, $controller, $action, $roleID)
    {
        $query = $this->select()
                 ->setIntegrityCheck(false)
                 ->from(array('rl' => $this->_tableName), array('COUNT(*) AS amount'))
                 ->join(array('rlp' => 'user.roles_privileges'), 'rl.id = rlp.role_id', array())
                 ->join(array('p' => 'user.privileges'), 'rlp.privilege_id = p.id', array())
                 ->where('p.module = ?', $module)
                 ->where('p.controller = ?', $controller)
                 ->where('p.actions = ?', $action)
                 ->where('rl.id = ?', $roleID);
        $result = $this->fetchRow($query);
        if(count($result))
        {
            $result = $result->toArray();
            return $result['amount'];
        }else{
            return null;
        }
    }

}