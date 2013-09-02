<?php
class Peta_Model_DbTable_Cb extends Peta_Model_Cb
{
    protected $_tableName = 'intelijen_musuh_cb';

    public function skenario() {
        $table = new Zend_Db_Table('latihan.skenario');
        $raw = $table->fetchAll($table->getAdapter()->quoteInto('closed <> ?', 1));
        $result = array('' => 'Pilih Skenario');
        foreach ($raw as $row) {
            $result[$row['id']] = $row['nomor'];
        }
        return $result;
    }
}