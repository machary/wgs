<?php
/**
 * Kogasgabfib Rute Laut
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabfib_Laut extends Ops_Model_Rute
{
	protected static $_cbTableName = 'ops.gabfib_cb';
	protected static $_ruteTableName = 'ops.gabfib_laut';
	protected static $_formasiTableName = 'ops.gabfib_laut_formasi';
	protected static $_titikTableName = 'ops.gabfib_laut_titik';
    protected static $_rudalTableName = 'ops.gabfib_laut_rudal';
	protected static $_durasiColumn = 'durasi_laut';
	
	public function symbols() {
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('kapal')));
	}
	
	// simpan latum
	public function isValid($post)
	{
		$ret = parent::isValid($post);
		if ($ret) {
			$this->_row->latum = $post['latum'];
		}
		return true;
	}
}