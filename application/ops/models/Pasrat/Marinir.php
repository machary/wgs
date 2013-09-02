<?php
/**
 * Pasrat Rute Marinir
 *
 * @author Kanwil
 */
 
class Ops_Model_Pasrat_Marinir extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.pasrat_cb';
	protected static $_ruteTableName = 'ops.pasrat_marinir';
	protected static $_formasiTableName = 'ops.pasrat_marinir_formasi';
	protected static $_titikTableName = 'ops.pasrat_marinir_titik';
    protected static $_rudalTableName = 'ops.pasrat_marinir_rudal';
	protected static $_durasiColumn = 'durasi_marinir';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'marinir', 'zeni marinir',
		)));
	}
}