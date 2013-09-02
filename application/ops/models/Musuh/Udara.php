<?php
/**
 * Musuh Rute Udara
 *
 * @author Febi
 */
 
class Ops_Model_Musuh_Udara extends Ops_Model_RuteMusuh
{
	protected static $_cbTableName = 'ops.musuh_cb';
	protected static $_ruteTableName = 'ops.musuh_udara';
	protected static $_formasiTableName = 'ops.musuh_udara_formasi';
	protected static $_titikTableName = 'ops.musuh_udara_titik';
	protected static $_durasiColumn = 'durasi_udara';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'm sayap putar', 'm sayap tetap',
		)));
	}
}