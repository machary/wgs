<?php
/**
 * Model Generik
 * @author Kanwil
 */
 
class Cms_Model_<nama>
{
	protected $_id;
	protected $_<atribut lainnya>
	
	protected $_primary = '<primary key>';
	protected $_table = null;
	
	protected $_object<relasi> = null;
	
	public function __construct($id = null)
	{
		if (isset($id)) {
			// coba cari 
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
	
	/**
	 * Mengembalikan object dbtable utama
	 * @return Zend_Db_Table
	 */
	public function table() 
	{
		if (!$this->_table) {
			$this->_table = new Cms_Model_DbTable_<nama>();
		}
		return $this->_table;
	}
	
	/**
	 * Apakah object ini ada di database?
	 * @return bool
	 */
	public function exists()
	{
		return $this->_id !== null;
	}
	
	// ------------- GETTERS ---------------
	public function getId() {return $this->_id;}
	public function get<atribut lainnya>() {return $this-><atribut lainnya>;}
	
	public function get<relasi>() {
		if (!$this->_object<relasi>) {
			$this->_object<relasi> = new Cms_Model_Idname(<tabel relasi>, $this->_<atribut>());
		}
		return $this->_object<relasi>;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * @param Cms_Form_<nama>|array $form
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		$this->_<atribut> = $form['<nama field>'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'<nama field>' => $this->_<atribut>,
		);
	}
	
	/**
	 * Memetakan kolom-kolom dari row database ke property
	 * @param Zend_Db_Table_Row|array $row
	 */
	public function setFromRow($row)
	{
		if (isset($row[$this->_primary])) {
			$this->_id = $row[$this->_primary];
		}
		$this->_<atribut> = $row['<nama field>'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = array(
			'<nama field>' => $this->_<atribut>,
		);
		if ($withId) {
			$row[$this->_primary] = $this->_id;
		}
		return $row;
	}
	
	/**
	 * Mengembalikan kondisi where untuk object ini
	 * @return string
	 */
	public function where()
	{
		$db = $this->table()->getAdapter();
		return $db->quoteInto($db->quoteIdentifier($this->_primary) . " = ?", $this->_id);
	}
	
	/**
	 * Simpan penambahan/perubahan ke database
	 */
	public function save()
	{
		$table = $this->table();
		if ($this->exists()) {
			$table->update($this->toRowArray(), $this->where());
		} else {
			$table->insert($this->toRowArray());
			$this->_id = $table->getAdapter()->lastInsertId();
		}
	}
	
	/**
	 * Hapus object ini
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		$table = $this->table();
		$table->delete($this->where());
		$this->_id = null;
	}
	
	// -------------- Table Scope --------------
	/**
	 * Mengembalikan array berisi semua baris dibungkus dalam object ini
	 * @return array of __CLASS__ instances
	 */
	public function all()
	{
		$table = $this->table();
		$raw = $table->fetchAll();
		$result = array();
		foreach ($raw as $row) {
			$temp = new Cms_Model_<nama>();
			$temp->setFromRow($row);
			$result[] = $temp;
		}
		return $result;
	}
	
}