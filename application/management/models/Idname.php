<?php
/**
 * Model Idname
 *
 * kloningan class Cms_Model_Idname untuk module Management
 * untuk deskripsi lebih lengkap baca Cms_Model_Idname
 * 
 * @author Kanwil
 */
 
class Management_Model_Idname extends Cms_Model_Idname
{
	protected $_schema = 'user';
	
	public static function factory($tableName, $id = null)
	{
		return new Management_Model_Idname($tableName, $id);
	}
	
	/**
	 * Constructor
	 * @param string $tableName nama table
	 * @param int $id nilai primary key
	 * @throw Exception jika nama table tidak dikenali
	 * @override sesuaikan nama table
	 */
	public function __construct($tableName, $id = null)
	{
		if (strpos($tableName, '.') !== false) {
			preg_match('/^(.*)\.(.*)$/', $tableName, $m);
			$this->_schema = $m[1];
			$tableName = $m[2];
		}
		switch ($tableName) {
			case 'roles':
				$this->_primary = 'id';
				$this->_secondary = 'name';
				break;
			default:
				// unknown table
				// @TODO deteksi otomatis mana primary key mana yg name
				throw new Exception('Unknown table name');
		}
		$this->_tableName = $tableName;
		
		if (isset($id)) {
			$table = $this->table();
			$rowset = $table->find($id);
			if (count($rowset) > 0) {
				$this->_id = $id;
				$this->setFromRow($rowset->current());
			} else {
				$this->_id = null;
			}
		} else {
			$this->_id = null;
		}
	}
}