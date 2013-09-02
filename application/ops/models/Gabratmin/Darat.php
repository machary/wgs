<?php
/**
 * Kogasgabratmin Rute Darat
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabratmin_Darat extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabratmin_cb';
	protected static $_ruteTableName = 'ops.gabratmin_darat';
	protected static $_formasiTableName = 'ops.gabratmin_darat_formasi';
	protected static $_titikTableName = 'ops.gabratmin_darat_titik';
    protected static $_rudalTableName = 'ops.gabratmin_darat_rudal';
	protected static $_durasiColumn = 'durasi_darat';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'infantri', 'infantri mekanis', 'kavaleri roda', 'kavaleri tank', 'zeni',
		)));
	}
}