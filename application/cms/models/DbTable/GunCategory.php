<?php
class Cms_Model_DbTable_GunCategory extends Zend_Db_Table_Abstract
{
    protected $_scema = 'master';
    protected $_name = 'M_GUN_CATEGORY';
    protected $_tableName = 'M_GUN_CATEGORY';
    protected $_primary = 'GUN_CATEGORY';

    public function getalldata($limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $filter = '', $search = '')
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_scema.'.'.$this->_tableName)
        ;

        if($search != '')
        {
            $query->where("GUN_CATEGORY like '%$search%'");
        }

        $result = $this->fetchAll($query);

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