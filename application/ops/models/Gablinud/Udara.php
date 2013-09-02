<?php
/**
 * Kogasgablinud Rute Udara
 *
 * @author Kanwil
 */
 
class Ops_Model_Gablinud_Udara extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gablinud_cb';
	protected static $_ruteTableName = 'ops.gablinud_udara';
	protected static $_formasiTableName = 'ops.gablinud_udara_formasi';
	protected static $_titikTableName = 'ops.gablinud_udara_titik';
    protected static $_rudalTableName = 'ops.gablinud_udara_rudal';
	protected static $_durasiColumn = 'durasi_udara';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('sayap putar', 'sayap tetap')));
	}
}