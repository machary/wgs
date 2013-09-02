<?php
/**
 * Pasrat Rute Darat
 *
 * @author Kanwil
 */
 
class Ops_Model_Pasrat_Darat extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.pasrat_cb';
	protected static $_ruteTableName = 'ops.pasrat_darat';
	protected static $_formasiTableName = 'ops.pasrat_darat_formasi';
	protected static $_titikTableName = 'ops.pasrat_darat_titik';
    protected static $_rudalTableName = 'ops.pasrat_darat_rudal';
	protected static $_durasiColumn = 'durasi_darat';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'infantri', 'infantri mekanis', 'kavaleri roda', 'kavaleri tank', 'zeni',
		)));
	}
}