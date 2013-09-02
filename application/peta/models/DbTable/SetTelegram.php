<?php
class Peta_Model_DbTable_SetTelegram extends Zend_Db_Table
{
    protected $_name = 'master.set_folder_telegram';
    protected $_tableName = 'master.set_folder_telegram';
    protected $_primary = 'id';

    public function saveSetFolder($value)
    {
        $data = array(
            'parent_id_folder_telegram' => $value['parent_id_folder_telegram'],
            'child_id_folder_telegram' => $value['child_id_folder_telegram']
        );

        $this->insert($data);
    }

    public function selectSetFolder()
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from($this->_tableName);

        $result = $this->fetchAll($query);
        return (!empty($result)) ? $result->toArray() : null;
    }

    public function updateSetFolder($value)
    {
        $data = array(
            'parent_id_folder_telegram' => $value['parent_id_folder_telegram'],
            'child_id_folder_telegram' => $value['child_id_folder_telegram']
        );
        $this->update($data, 'id = 1');
    }
}