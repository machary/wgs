<?php
class Cms_Model_DbTable_Jabatan extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.M_JABATAN';
    protected $_tableName = 'master.M_JABATAN';
    protected $_primary = 'id_jabatan';

    public function getjabatan()
    {
        $result = $this->fetchAll($this->select()->order('nama_jabatan'));

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function getNama($kogasID)
    {
        $result = $this->fetchRow($this->select()->where('id_jabatan = ?', $kogasID));

        if(!empty($result))
        {
            return $result->nama_jabatan;
        }
        else
        {
            return null;
        }
    }
}
