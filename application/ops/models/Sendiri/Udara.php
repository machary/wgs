<?php
/**
 * Musuh Rute Udara
 *
 * @author Febi
 */
 
class Ops_Model_Sendiri_Udara extends Ops_Model_RuteSendiri
{
	protected static $_cbTableName = 'ops.sendiri_cb';
	protected static $_ruteTableName = 'ops.sendiri_udara';
	protected static $_formasiTableName = 'ops.sendiri_udara_formasi';
	protected static $_titikTableName = 'ops.sendiri_udara_titik';
	protected static $_durasiColumn = 'durasi_udara';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'sayap putar', 'sayap tetap',
		)));
	}
}