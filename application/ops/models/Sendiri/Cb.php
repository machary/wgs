<?php
/**
 * Sendiri CRUD CB
 *
 * @author Sendiri
 */
 
class Ops_Model_Sendiri_Cb extends Ops_Model_Cb
{
	protected $_tableName = 'sendiri_cb';

    public function skenario() {
   		$table = new Zend_Db_Table('latihan.skenario');
        $raw = $table->fetchAll($table->getAdapter()->quoteInto('closed <> ?', 1));
        $result = array('' => 'Pilih Skenario');
        foreach ($raw as $row) {
            $result[$row['id']] = $row['nomor'];
        }
        return $result;
   	}
}