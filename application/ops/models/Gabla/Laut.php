<?php
/**
 * Kogasgabla Rute Laut
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabla_Laut extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabla_cb';
	protected static $_ruteTableName = 'ops.gabla_laut';
	protected static $_formasiTableName = 'ops.gabla_laut_formasi';
	protected static $_titikTableName = 'ops.gabla_laut_titik';
	protected static $_rudalTableName = 'ops.gabla_laut_rudal';
	protected static $_durasiColumn = 'durasi_laut';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('kapal')));
	}
}