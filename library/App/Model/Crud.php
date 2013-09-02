<?php
/**
 * Model Dasar CRUD
 *
 * Bisa generate secara otomatis Form yang digunakan
 * Batasan dari model ini:
	- table harus punya 1 kolom yang jadi primary key
	- semua kolom akan dijadikan field form (kalau pakai default form)
	- foreign key diset secara manual (hanya akan jadi form field text biasa jika tidak)
 *
 * @author Kanwil
 */
 
class App_Model_Crud
{
	protected $_id;
	protected $_values; // array associative menyimpan data pada baris database
	
	protected $_primary = null;
	protected $_tableSchema = null;
	protected $_tableName = '';
	protected $_table = null;
	protected $_tableInfo = array(); // schema,name,cols,primary,metadata
	
	// nama kolom yg di dalam sini tidak akan dimasukkan form
	protected $_ignoreCols = array();
	// kolom yg masuk di sini akan dibuatkan element select
	protected $_foreignKeys = array();
	/* contoh pemakaian:
	protected $_foreignKeys = array(
		'idparent' => array( // nama kolom yg merupakan foreign key
			'label' => 'Komando Pelaksana',
			'schema' => 'master',
			'table' => 'kesatuan', // nama table yang ditunjuk
			'field' => 'idkesatuan', // nama kolom yang ditunjuk
			'display' => 'nama_kesatuan', // nama kolom dari table yg dijadikan display option
		),
	);
	*/
	// setting form element langsung tanpa generate
	protected $_customElements = array();
	/* contoh pemakaian:
	protected $_customElements = array(
		'fullname' => array(
			'text', // element pertama adalah jenis form element
			// element sisanya akan dijadikan option untuk form element
			'label' => 'Nama Lengkap',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		),
	);
	*/


	public function __construct($tableName = null, $id = null)
	{
		if ($tableName) {
			if (strpos($tableName, '.') !== false) {
				preg_match('/^(.*)\.(.*)$/', $tableName, $m);
				$this->_tableSchema = $m[1];
				$this->_tableName = $m[2];
			} else {
				$this->_tableName = $tableName;
			}
		}
		$this->_tableInfo = $this->table()->info();
		$this->_primary = isset($this->_primary) ? $this->_primary : current($this->_tableInfo['primary']);
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
		$this->_init();
	}
	
	/**
	 * Any extra things to do at the end of constructor
	 */
	protected function _init()
	{
	}
	
	/**
	 * Mengembalikan object dbtable utama
	 * @return Zend_Db_Table
	 */
	public function table() 
	{
		if (!$this->_table) {
			$this->_table = new Zend_Db_Table(array(
				'schema' => $this->_tableSchema,
				'name' => $this->_tableName,
			));
		}
		return $this->_table;
	}
	
	/**
	 * Mengembalikan object form untuk CRUD object ini
	 * @override menambah fitur custom element form
	 * @return Zend_Form
	 */
	public function form()
	{
		$f = new Zend_Form();
		$f->setMethod('post')
			->setDecorators(array(
				array('ViewScript', array('viewScript' => 'partials/form-crud.phtml')) 
			));
		// Generate form element berdasarkan $this->_tableInfo
		foreach ($this->_tableInfo['metadata'] as $column => $colInfo) {
			// primary key tidak dibuatkan form element
			if (!$colInfo['PRIMARY'] && !in_array($column, $this->_ignoreCols)) { 
				if (isset($this->_customElements[$column])) {
				// apakah menggunakan custom option
					$options = $this->_customElements[$column];
					$type = $options[0];
					unset($options[0]);
					$f->addElement($type, $column, $options);
				} else if (isset($this->_foreignKeys[$column])) {
				// langsung mengenali foreign key dan membuat element select
					$setting = $this->_foreignKeys[$column];
					$f->addElement('select', $column, array(
						'label' => (isset($setting['label'])) ? $setting['label'] : $this->fieldToName($column),
						'required' => !$colInfo['NULLABLE'],
						'multiple' => false,
						'multiOptions' => $this->_generateOptions($setting),
						'validators' => array(
							array('Db_RecordExists', false, array(
								'schema' => $setting['schema'],
								'table' => $setting['table'],
								'field' => $setting['field'],
							)),
						),
					));
				} else switch ($colInfo['DATA_TYPE']) {
				// generate form element berdasarkan deskripsi kolom table database
					case 'int4':
					case 'int8':
						$f->addElement('text', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
							'filters' => array('Int'),
							'validators' => array(
							),
						));
						break;
					case 'float4':
					case 'float8':
						$f->addElement('text', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
							'filters' => array('StringTrim'),
							'validators' => array(
								array('Float'),
							),
						));
						break;
					case 'bool':
						$f->addElement('checkbox', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
						));
						break;
					case 'varchar':
						$f->addElement('text', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
							'filters' => array('StringTrim'),
							'validators' => array(
								array('StringLength', false, array('min'=>0, 'max'=>$colInfo['LENGTH'])),
							),
						));
						break;
					case 'text':
						$f->addElement('textarea', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
							'filters' => array('StringTrim'),
						));
						break;
					default:
						$f->addElement('text', $column, array(
							'label' => $this->fieldToName($column),
							'required' => !$colInfo['NULLABLE'],
							'filters' => array('StringTrim'),
						));
						break;
				}
			}
		}
		// terakhir ditambahkan tombol submit
		$f->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		));
		return $f;
	}
	
	/**
	 * Mengembalikan array berisi pilihan untuk dijadikan option select element
	 * @param array $setting salah satu settingan foreign key
	 */
	protected function _generateOptions($setting)
	{
		$db = $this->table()->getAdapter();
		$select = $db->select()
			->from("{$setting['schema']}.{$setting['table']}", 
				array($setting['field'], $setting['display']))
			->order($setting['display'].' ASC')
		;
		$raw = $db->fetchAll($select);
		if (isset($setting['allowEmpty']) && !$setting['allowEmpty']) {
			$result = array();
		} else {
			$result = array('' => '-');
		}
		foreach ($raw as $row) {
			$result[$row[$setting['field']]] = $row[$setting['display']];
		}
		return $result;
	}
	
	/**
	 * Convert string seperti "sonar_name" menjadi "Sonar Name"
	 */
	public function fieldToName($field)
	{
		// di lowercase, ganti '_' jadi ' ', uppercase huruf awal tiap kata
		return ucwords(str_replace('_', ' ', strtolower($field)));
	}
	
	/**
	 * Apakah object ini ada di database?
	 * @return bool
	 */
	public function exists()
	{
		return $this->_id !== null;
	}
	
	// ================ GETTERS ==================
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Mengembalikan nilai satu kolom
	 * @param string $col nama kolom
	 * @return null|mixed
	 */
	public function get($col)
	{
		return isset($this->_values[$col]) ? $this->_values[$col] : null;
	}
	
	/**
	 * Men-set nilai satu kolom
	 * @param string $col nama kolom
	 * @param mixed $val nilai 
	 */
	public function set($col, $val)
	{
		$this->_values[$col] = $val;
	}
	
	/**
	 * Memetakan input field milik form ke property sendiri
	 * Konvensi: Nama field milik form harus sama dengan nama field milik table
	 * @param Zend_Form|array $form
	 */
	public function setFromForm($form, $meetingID = '', $creatorID = '')
	{
		if (is_a($form, 'Zend_Form')) {
			$values = $form->getValues();
			foreach ($values as $name => $val) {
				if ($val === '' && !$form->getElement($name)->isRequired()) {
					unset($values[$name]);
				}
			}
			$form = $values;
		}
		$this->_values = $form;
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * Konvensi: Nama field milik form harus sama dengan nama field milik table
	 * @return array
	 */
	public function toFormArray()
	{
		return $this->_values;
	}
	
	/**
	 * Memetakan kolom-kolom dari row database ke property
	 * Konvensi: Nama field milik form harus sama dengan nama field milik table
	 * @param Zend_Db_Table_Row|array $row
	 */
	public function setFromRow($row)
	{
		if (is_a($row, 'Zend_Db_Table_Row')) {
			$row = $row->toArray();
		}
		if (isset($row[$this->_primary])) {
			$this->_id = $row[$this->_primary];
		}
		unset($row[$this->_primary]);
		$this->_values = $row;
	}
	
	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * Konvensi: Nama field milik form harus sama dengan nama field milik table
	 * @return array
	 */
	public function toRowArray($withId = false)
	{
		$row = $this->_values;
		if ($withId) {
			$row[$this->_primary] = $this->_id;
		}
		return array_intersect_key($row, $this->_tableInfo['metadata']);
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

	/**
	 * Kembalikan array dengan nama key sesuai nama kolom
	 * Konvensi: Nama field milik form harus sama dengan nama field milik table
	 * @return array
	 * @author irfan.muslim@sangkuriang.co.id
	 */

	public function toViewArray()
	{
		$result = array();
		foreach ($this->_values as $key => $val) {
			$name = $this->fieldToName($key);
			
			if (isset($this->_foreignKeys[$key])) {
				$setting = $this->_foreignKeys[$key];
				if ($val) {
					$db = $this->table()->getAdapter();
					$select = $db->select()
						->from($setting['schema'].'.'.$setting['table'], array(
							$setting['field'], 
							$setting['display'],
						))
						->where('"'.$setting['field'].'" = ?',$val)
					;

					$row = $db->fetchRow($select);

					if ($row != null) {
						$val = $row[$setting['display']];
					}
				}
				$name = $setting['label'];
			}
			$result[$name] = $val;
		}
		return $result;
	}

}