<?php
/**
 * Model Bomb
 * @author Kanwil
 */
 
class Cms_Model_Bomb
{
	protected $_id;
	protected $_type;
	protected $_countryId;
	protected $_name;
	protected $_warheadWeight;
	protected $_rangeMax;
	protected $_probOfHit;
	protected $_description;
	
	protected $_primary = 'BOMB_ID';
	protected $_table = null;
	
	protected $_objectType = null;
	protected $_objectCountry = null;
	
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
	 * Mengembalikan object dbtable utama Bom
	 * @return Zend_Db_Table
	 */
	public function table() 
	{
		if (!$this->_table) {
			$this->_table = new Cms_Model_DbTable_Bomb();
		}
		return $this->_table;
	}
	
	/**
	 * Apakah Bomb ini ada di database?
	 * @return bool
	 */
	public function exists()
	{
		return $this->_id !== null;
	}
	
	// ------------- GETTERS ---------------
	public function getId() {return $this->_id;}
	public function getTypeId() {return $this->_type;}
	public function getCountryId() {return $this->_countryId;}
	public function getName() {return $this->_name;}
	public function getWarheadWeight() {return $this->_warheadWeight;}
	public function getRangeMax() {return $this->_rangeMax;}
	public function getProbOfHit() {return $this->_probOfHit;}
	public function getDescription() {return $this->_description;}
	
	public function getType() {
		if (!$this->_objectType) {
			$this->_objectType = new Cms_Model_Idname('M_BOMB_TYPE', $this->getTypeId());
		}
		return $this->_objectType;
	}
	
	public function getCountry() {
		if (!$this->_objectCountry) {
			$this->_objectCountry = new Cms_Model_Idname('M_COUNTRY', $this->getCountryId());
		}
		return $this->_objectCountry;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * @param Cms_Form_Bomb|array $form
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		$this->_type = $form['bomb_type'];
		$this->_countryId = $form['country_id'];
		$this->_name = $form['bomb_name'];
		$this->_warheadWeight = $form['bomb_warhead_weight'];
		$this->_rangeMax = $form['bomb_range_max'];
		$this->_probOfHit = $form['bomb_prob_of_hit'];
		$this->_description = $form['description'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'bomb_type' => $this->_type,
			'country_id' => $this->_countryId,
			'bomb_name' => $this->_name,
			'bomb_warhead_weight' => $this->_warheadWeight,
			'bomb_range_max' => $this->_rangeMax,
			'bomb_prob_of_hit' => $this->_probOfHit,
			'description' => $this->_description,
		);
	}
	
	/**
	 * Memetakan kolom-kolom dari row database ke property
	 * @param Zend_Db_Table_Row|array $row
	 */
	public function setFromRow($row)
	{
		if (isset($row['BOMB_ID'])) {
			$this->_id = $row['BOMB_ID'];
		}
		$this->_type = $row['BOMB_TYPE'];
		$this->_countryId = $row['COUNTRY_ID'];
		$this->_name = $row['BOMB_NAME'];
		$this->_warheadWeight = $row['BOMB_WARHEAD_WEIGHT'];
		$this->_rangeMax = $row['BOMB_RANGE_MAX'];
		$this->_probOfHit = $row['BOMB_PROB_OF_HIT'];
		$this->_description = $row['DESCRIPTION'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = array(
			'BOMB_TYPE' => $this->_type,
			'COUNTRY_ID' => $this->_countryId,
			'BOMB_NAME' => $this->_name,
			'BOMB_WARHEAD_WEIGHT' => $this->_warheadWeight,
			'BOMB_RANGE_MAX' => $this->_rangeMax,
			'BOMB_PROB_OF_HIT' => $this->_probOfHit,
			'DESCRIPTION' => $this->_description,
		);
		if ($withId) {
			$row['BOMB_ID'] = $this->_id;
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
	 * Hapus Bom ini
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
			$temp = new Cms_Model_Bomb();
			$temp->setFromRow($row);
			$result[] = $temp;
		}
		return $result;
	}
	
}