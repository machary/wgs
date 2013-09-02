<?php
/**
 * Class Model Import Excel
 *
 * Limitasi: 
	- 1 file excel untuk 1 table
	- 1 kolom excel untuk 1 kolom table 
	- default hanya bisa insert (override method save() kalau beda)
 * 
 * @uses PHPExcel
 * @author Kanwil
 */
 
class Cms_Model_Import_Excel
{
	// full path file excel yang akan diimport
	protected $_filename;
	// PHPExcel worksheet
	protected $_sheet = null;
	// urutan kolom pada file excel, sesuai nama kolom table
	protected $_columns;
	// nama table tempat menyimpan hasil import
	protected $_tableName;
	
	// Zend_Db_Table cache
	protected $_table = null;
	// Zend_Form cache
	protected $_rowForm = null;
	
	// menyimpan array of row
	protected $_data = array();
	
	protected $_validated = false;
	protected $_errors = array();
	
	/**
	 * Constructor
	 * @param string $tableName nama table tempat menyimpan data dari Excel
	 * @param array $columns urutan kolom table sesuai urutan pada file Excel
	 */
	public function __construct($tableName, $columns)
	{
		$this->_tableName = $tableName;
		$this->_columns = $columns;
	}
	
	// ============== GETTER/SETTER ==============
	/**
	 * Semua property diberikan getter dan setter
	 * Misal: $_filename - getFilename() - setFilename($value)
	 */
	public function __call($name, $args)
	{
		if (preg_match('/^[gs]et[A-Z]/', $name)) {
			$propertyName = '_'.lcfirst(substr($name, 3)); // WARNING: lcfirst() needs PHP >= 5.3
			if (property_exists($this, $propertyName)) {
				$method = substr($name, 0, 3);
				if ($method == 'set') {
					$this->$propertyName = $args[0];
				} else { // == 'get'
					return $this->$propertyName;
				}
			} else {
				throw new Exception('Unknown property');
			}
		} else {
			throw new Exception('Unknown method');
		}
	}
	
	/**
	 * Mengembalikan object Zend_Form yang dipakai untuk mengupload file
	 * Berisi 1 elemen input file saja
	 * Override method ini kalau mau menggunakan form yang lain
	 * @return Zend_Form
	 */
	public function importForm()
	{
		$form = new Zend_Form();
		$form
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'partials/form-import.phtml')) 
		))
		->addElement('file', 'import_file', array(
			'required' => true,
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'xls,xlsx'),
			),
		))
		;
		return $form;
	}
	
	/**
	 * Mengembalikan object Zend_Db_Table yang dipakai untuk mengakses table
	 * @return Zend_Db_Table
	 */
	public function table()
	{
		if (!isset($this->_table)) {
			$this->_table = new Zend_Db_Table($this->_tableName);
		}
		return $this->_table;
	}
	
	/**
	 * Mem-parsing isi file Excel ke bentuk array of row
	 * Mengisi property $this->_data
	 * @param string $filename file path ke file excel
	 */
	public function parse($filename)
	{
		$excel = PHPExcel_IOFactory::load($filename);
		$excel->setActiveSheetIndex(0);
		$worksheet = $excel->getActiveSheet();
		
		$this->_filename = $filename;
		$this->_sheet = $worksheet;
		$this->_data = array();
		
		$maxRow = $worksheet->getHighestRow();
		$maxCol = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
		$columns = $this->_columns;
		$table = $this->table();
		
		// iterasi semua baris, skip baris pertama (dianggap header)
		for ($y=2; $y<=$maxRow; $y++) {
			// grab data for 1 row
			$row = array();
			foreach ($columns as $x => $field) {
				$row[$field] = $worksheet->getCellByColumnAndRow($x, $y)->getValue();
			}
			$this->_data[] = $row;
		}
	}
	
	/**
	 * Mengembalikan object Zend_Form yang dipakai untuk validasi data per row
	 * Override method ini kalau mau menggunakan form yang lain
	 * @return Zend_Form
	 */
	public function rowForm()
	{
		if (!isset($this->_rowForm)) {
			// default pakai generated form
			$model = new Cms_Model_Crud($this->_tableName);
			$this->_rowForm = $model->form();
		}
		return $this->_rowForm;
	}
	
	/**
	 * Mengembalikan true jika seluruh baris sudah valid
	 * @return bool
	 */
	public function isValid()
	{
		if (!$this->_validated) {
			$this->_validated = true;
			$this->_errors = array();
			
			$form = $this->rowForm();
			foreach ($this->_data as $i => $row) {
				if (!$form->isValid($row)) {
					$this->_errors[$i] = $form->getMessages();
				} else {
					// filter nilai kosong
					foreach ($row as $key => $val) {
						if ($val === '' && !$form->getElement($key)->isRequired()) {
							unset($row[$key]);
						}
					}
					$this->_data[$i] = $row;
				}
			}
		}
		return count($this->_errors) === 0;
	}
	
	/**
	 * Menyimpan isi excel ke database
	 * Secara default hanya mendukung insertion
	 */
	public function save()
	{
		if ($this->isValid()) {
			$table = $this->table();
			foreach ($this->_data as $row) {
				$table->insert($row);
			}
		}
	}
}