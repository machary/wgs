<?php
class Peta_Model_DbTable_Laut extends Peta_Model_RuteMusuh
{
    protected static $_cbTableName = 'latihan.intelijen_musuh_cd';
    protected static $_ruteTableName = 'latihan.intelijen_musuh_laut';
    protected static $_formasiTableName = 'latihan.intelijen_laut_formasi';
    protected static $_titikTableName = 'latihan.intelijen_musuh_laut_titik';
    protected static $_durasiColumn = 'durasi_laut';

    public function symbols() {
        $table = new Zend_Db_Table('master.simbol_taktis');
        return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('m kapal')));
    }

}