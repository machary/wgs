<?php
/**
 * Sendiri Rute Laut
 *
 * @author Febi
 */
 
class Ops_Model_Sendiri_Laut extends Ops_Model_RuteSendiri
{
	protected static $_cbTableName = 'ops.sendiri_cb';
	protected static $_ruteTableName = 'ops.sendiri_laut';
	protected static $_formasiTableName = 'ops.sendiri_laut_formasi';
	protected static $_titikTableName = 'ops.sendiri_laut_titik';
	protected static $_durasiColumn = 'durasi_laut';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('kapal')));
	}

}