<?php
/**
 * Kogasgabhantai Rute Darat
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabhantai_Darat extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabhantai_cb';
	protected static $_ruteTableName = 'ops.gabhantai_darat';
	protected static $_formasiTableName = 'ops.gabhantai_darat_formasi';
	protected static $_titikTableName = 'ops.gabhantai_darat_titik';
    protected static $_rudalTableName = 'ops.gabhantai_darat_rudal';
	protected static $_durasiColumn = 'durasi_darat';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array(
			'infantri', 'infantri mekanis', 'kavaleri roda', 'kavaleri tank', 'zeni',
		)));
	}
}