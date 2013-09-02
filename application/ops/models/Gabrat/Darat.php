<?php
/**
 * Kogasgabrat Rute Darat
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabrat_Darat extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabrat_cb';
	protected static $_ruteTableName = 'ops.gabrat_darat';
	protected static $_formasiTableName = 'ops.gabrat_darat_formasi';
	protected static $_titikTableName = 'ops.gabrat_darat_titik';
    protected static $_rudalTableName = 'ops.gabrat_darat_rudal';
	protected static $_durasiColumn = 'durasi_darat';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'infantri', 'infantri mekanis', 'kavaleri roda', 'kavaleri tank', 'zeni',
		)));
	}
}