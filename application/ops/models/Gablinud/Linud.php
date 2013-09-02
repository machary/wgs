<?php
/**
 * Kogasgablinud Rute Linud
 *
 * @author Kanwil
 */
 
class Ops_Model_Gablinud_Linud extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gablinud_cb';
	protected static $_ruteTableName = 'ops.gablinud_linud';
	protected static $_formasiTableName = 'ops.gablinud_linud_formasi';
	protected static $_titikTableName = 'ops.gablinud_linud_titik';
    protected static $_rudalTableName = 'ops.gablinud_linud_rudal';
	protected static $_durasiColumn = 'durasi_linud';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('linud')));
	}
}