<?php
/**
 * Model Idname
 *
 * Model for all simple two-columns table (id (int) & name (varchar-100))
 * Membungkus tabel menjadi object dengan 2 property: id dan name
 *
 * @author Kanwil
 */
 
class Cms_Model_Idname
{
	protected $_id;
	protected $_name;
	
	protected $_tableName;
	protected $_primary = null; // nama kolom ID
	protected $_secondary = null; // nama kolom name
	protected $_schema = 'master'; // nama schema database
	
	protected $_db = null;
	protected $_table = null;
	protected $_form = null;
	
	public static function factory($tableName, $id = null)
	{
		return new Cms_Model_Idname($tableName, $id);
	}
	
	/**
	 * Constructor
	 * @param string $tableName nama table
	 * @param int $id nilai primary key
	 * @throw Exception jika nama table tidak dikenali
	 */
	public function __construct($tableName, $id = null)
	{
		switch ($tableName) {
			case 'M_BOMB_TYPE':
				$this->_primary = 'BOMB_TYPE';
				$this->_secondary = 'BOMB_TYPE_NAME';
				break;
			case 'M_EMITTER_BAND':
				$this->_primary = 'EMITTER_BAND_ID';
				$this->_secondary = 'EMITTER_BAND_CODE';
				break;
			case 'M_RADAR_ECCM_TYPE':
				$this->_primary = 'ECCM_TYPE_ID';
				$this->_secondary = 'ECCM_TYPE_CODE';
				break;
			case 'M_RADAR_MTI':
				$this->_primary = 'MTI_ID';
				$this->_secondary = 'MTI_CODE';
				break;
			case 'M_RADAR_TYPE':
				$this->_primary = 'RADAR_TYPE_ID';
				$this->_secondary = 'RADAR_TYPE_CODE';
				break;
			case 'M_RADAR_TYPE_SURFACE':
				$this->_primary = 'RADAR_SURFACE_ID';
				$this->_secondary = 'RADAR_SURFACE_NAME';
				break;
			case 'M_SONAR_CATEGORY':
				$this->_primary = 'SONAR_CATEGORY';
				$this->_secondary = 'SONAR_CATEGORY_NAME';
				break;
			case 'M_SONAR_OPERATE_MODE':
				$this->_primary = 'SONAR_OP_MODE';
				$this->_secondary = 'SONAR_OP_MODE_NAME';
				break;
            case 'M_COUNTRY':
                $this->_primary = 'COUNTRY_ID';
                $this->_secondary = 'COUNTRY_NAME';
                break;
            case 'M_SHIP_CLASS':
                $this->_primary = 'SHIP_CLASS_ID';
                $this->_secondary = 'SHIP_CLASS_NAME';
                break;
            case 'pesawat_jenis':
                $this->_primary = 'pesawat_jenis_id';
                $this->_secondary = 'nama';
                break;
			// hanya untuk getters:
			case 'M_BOMB':
				$this->_primary = 'BOMB_ID';
                $this->_secondary = 'BOMB_NAME';
                break;
			case 'M_RADAR':
				$this->_primary = 'RADAR_ID';
                $this->_secondary = 'RADAR_NAME';
                break;
			case 'M_SONAR':
				$this->_primary = 'SONAR_ID';
                $this->_secondary = 'SONAR_NAME';
                break;
			case 'pangkalan':
				$this->_primary = 'idpangkalan';
				$this->_secondary = 'nama';
				break;
			case 'ranpur':
				$this->_primary = 'idranpur';
				$this->_secondary = 'jenis';
				break;
			case 'kesatuan':
				$this->_primary = 'idkesatuan';
				$this->_secondary = 'nama_kesatuan';
				break;
			case 'komando':
				$this->_primary = 'idkomando';
				$this->_secondary = 'nama_komando';
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
	
	public function db()
	{
		if (!$this->_db) {
			$this->_db = Zend_Registry::get('main_db');
		}
		return $this->_db;
	}
	
	/**
	 * Mengembalikan object dbtable yang digunakan
	 * @return Zend_Db_Table
	 */
	public function table()
	{
		if (!$this->_table) {
			$param = array(
				'db' => $this->db(),
				'schema' => $this->_schema,
				'name' => $this->_tableName,
				'primary' => $this->_primary,
			);
			$this->_table = new Zend_Db_Table($param);
		}
		return $this->_table;
	}
	
	/**
	 * Mengembalikan object form yang digunakan
	 * @return Cms_Form_Idname
	 */
	public function form()
	{
		if (!$this->_form) {
			$this->_form = new Cms_Form_Idname();
		}
		return $this->_form;
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
	public function getId()
	{
		return $this->_id;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * @param Zend_Form|array $form
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		$this->_name = $form['name'];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'name' => $this->_name,
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
		$this->_name = $row[$this->_secondary];
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = array(
			$this->_secondary => $this->_name,
		);
		if ($withId) {
			$row[$this->_primary] = $this->_id;
		}
		return $row;
	}
	
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
	 * Hapus object ini dari database
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
	 * 
	 * @return array of Cms_Model_Idname
	 */
	public function all()
	{
		$table = $this->table();
		$raw = $table->fetchAll();
		$result = array();
		$className = get_class($this);
		foreach ($raw as $row) {
			$temp = new $className($this->_tableName);
			$temp->setFromRow($row);
			$result[] = $temp;
		}
		return $result;
	}
	
	/**
	 * Mengembalikan array berbentuk [id] => "name"
	 * @return array
	 */
	public function allAsOptions()
	{
		$table = $this->table();
		$select = $table->select()->order($this->_secondary.' ASC');
		$raw = $table->fetchAll($select);
		$result = array();
		foreach ($raw as $row) {
			$result[$row[$this->_primary]] = $row[$this->_secondary];
		}
		return $result;
	}
}