<?php
/**
 * Musuh Rute Laut
 *
 * @author Febi
 */
 
class Ops_Model_Musuh_Laut extends Ops_Model_RuteMusuh
{
	protected static $_cbTableName = 'ops.musuh_cb';
	protected static $_ruteTableName = 'ops.musuh_laut';
	protected static $_formasiTableName = 'ops.musuh_laut_formasi';
	protected static $_titikTableName = 'ops.musuh_laut_titik';
	protected static $_durasiColumn = 'durasi_laut';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('m kapal')));
	}

}