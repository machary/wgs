<?php
/**
 * Kogasgabfib Rute Udara
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabfib_Udara extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabfib_cb';
	protected static $_ruteTableName = 'ops.gabfib_udara';
	protected static $_formasiTableName = 'ops.gabfib_udara_formasi';
	protected static $_titikTableName = 'ops.gabfib_udara_titik';
    protected static $_rudalTableName = 'ops.gabfib_udara_rudal';
	protected static $_durasiColumn = 'durasi_udara';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'sayap putar', 'sayap tetap',
		)));
	}
}