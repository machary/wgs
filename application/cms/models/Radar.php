<?php
/**
 * Model Radar
 * @author Kanwil
 */
 
class Cms_Model_Radar
{
	protected $_id;
	protected $_name;
	protected $_elevation;
	protected $_typeId;
	protected $_maxRange;
	protected $_freq;
	protected $_jammRange;
	protected $_description;
	
	protected $_primary = 'RADAR_ID';
	protected $_table = null;
	
	protected $_objectType = null;
	
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
			$this->_table = new Cms_Model_DbTable_Radar();
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
	
	public function getType() {
		if (!$this->_objectType) {
			$this->_objectType = new Cms_Model_Idname('M_RADAR_TYPE', $this->_typeId);
		}
		return $this->_objectType;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * @param Cms_Form_Radar|array $form
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		$this->_name = $form['name'];
		$this->_elevation = $form['elevation'];
		$this->_typeId = $form['type_id'];
		$this->_maxRange = $form['max_range'];
		$this->_freq = $form['freq'];
		$this->_jammRange = $form['jamm_range'];
		$this->_description = $form['description'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'name' => $this->_name,
			'elevation' => $this->_elevation,
			'type_id' => $this->_typeId,
			'max_range' => $this->_maxRange,
			'freq' => $this->_freq,
			'jamm_range' => $this->_jammRange,
			'description' => $this->_description,
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
		$this->_name = $row['RADAR_NAME'];
		$this->_elevation = $row['RADAR_ELEVATION'];
		$this->_typeId = $row['RADAR_TYPE'];
		$this->_maxRange = $row['RADAR_MAX_RANGE'];
		$this->_freq = $row['RADAR_FREQ'];
		$this->_jammRange = $row['RADAR_JAMM_RANGE'];
		$this->_description = $row['DESCRIPTION'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = array(
			'RADAR_NAME' => $this->_name,
			'RADAR_ELEVATION' => $this->_elevation,
			'RADAR_TYPE' => $this->_typeId,
			'RADAR_MAX_RANGE' => $this->_maxRange,
			'RADAR_FREQ' => $this->_freq,
			'RADAR_JAMM_RANGE' => $this->_jammRange,
			'DESCRIPTION' => $this->_description,
		);
		if ($withId) {
			$row[$this->_primary] = $this->_id;
		}
		return $row;
	}
	
	/**
	 * Mengembalikan kondisi where untuk object ini
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
	public function all()
	{
		$table = $this->table();
		$raw = $table->fetchAll();
		$result = array();
		foreach ($raw as $row) {
			$temp = new Cms_Model_Radar();
			$temp->setFromRow($row);
			$result[] = $temp;
		}
		return $result;
	}
	
}