<?php
/**
 * Model Sonar
 * @author Kanwil
 */
 
class Cms_Model_Sonar
{
	protected $_id;
	protected $_name;
	protected $_categoryId;
	protected $_maxDetectRange;
	protected $_maxDepth;
	protected $_maxSpeed;
	protected $_description;
	
	protected $_primary = 'SONAR_ID';
	protected $_table = null;
	
	protected $_objectCategory = null;
	
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
			$this->_table = new Cms_Model_DbTable_Sonar();
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
	public function getName() {return $this->_name;}
	public function getCategoryId() {return $this->_categoryId;}
	public function getMaxDetectRange() {return $this->_maxDetectRange;}
	public function getMaxDepth() {return $this->_maxDepth;}
	public function getMaxSpeed() {return $this->_maxSpeed;}
	public function getDescription() {return $this->_description;}
	
	public function getCategory() {
		if (!$this->_objectCategory) {
			$this->_objectCategory = new Cms_Model_Idname('M_SONAR_CATEGORY', $this->getCategoryId());
		}
		return $this->_objectCategory;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * @param Cms_Form_Sonar|array $form
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		$this->_name = $form['sonar_name'];
		$this->_categoryId = $form['sonar_category'];
		$this->_maxDetectRange = $form['sonar_max_detect_range'];
		$this->_maxDepth = $form['sonar_max_depth'];
		$this->_maxSpeed = $form['sonar_max_speed'];
		$this->_description = $form['description'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'sonar_name' => $this->_name,
			'sonar_category' => $this->_categoryId,
			'sonar_max_detect_range' => $this->_maxDetectRange,
			'sonar_max_depth' => $this->_maxDepth,
			'sonar_max_speed' => $this->_maxSpeed,
			'sonar_description' => $this->_description,
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
		$this->_name = $row['SONAR_NAME'];
		$this->_categoryId = $row['SONAR_CATEGORY'];
		$this->_maxDetectRange = $row['SONAR_MAX_DETECT_RANGE'];
		$this->_maxDepth = $row['SONAR_MAX_DEPTH'];
		$this->_maxSpeed = $row['SONAR_MAX_SPEED'];
		$this->_description = $row['DESCRIPTION'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = array(
			'SONAR_NAME' => $this->_name,
			'SONAR_CATEGORY' => $this->_categoryId,
			'SONAR_MAX_DETECT_RANGE' => $this->_maxDetectRange,
			'SONAR_MAX_DEPTH' => $this->_maxDepth,
			'SONAR_MAX_SPEED' => $this->_maxSpeed,
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
			$temp = new Cms_Model_Sonar();
			$temp->setFromRow($row);
			$result[] = $temp;
		}
		return $result;
	}
	
}