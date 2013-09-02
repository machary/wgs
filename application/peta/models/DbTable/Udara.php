<?php
class Peta_Model_DbTable_Udara extends Peta_Model_RuteMusuh
{
    protected static $_cbTableName = 'latihan.intelijen_musuh_cd';
    protected static $_ruteTableName = 'latihan.intelijen_musuh_udara';
    protected static $_formasiTableName = 'latihan.intelijen_udara_formasi';
    protected static $_titikTableName = 'latihan.intelijen_musuh_udara_titik';
    protected static $_durasiColumn = 'durasi_udara';

    public function symbols() {
        $table = new Zend_Db_Table('master.simbol_taktis');
        return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
            'm sayap putar', 'm sayap tetap',
        )));
    }
}