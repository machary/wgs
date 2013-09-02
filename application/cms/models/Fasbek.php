<?php
/**
 * Model Fasbek
 *
 * Merepresentasikan kumpulan Fasilitas Perbekalan yang dimiliki oleh satu pangkalan
 * 
 * @author Kanwil
 */
 
class Cms_Model_Fasbek
{
	protected $_pangkalanId;
	protected $_objectPangkalan;
	
	// isi fasbek milik object ini sekarang
	protected $_fasbeks = null;
	// isi fasbek yang tersimpan di database
	protected $_savedFasbeks = null;
	protected $_savedIds = null;
	
	/**
	 * @param int|string $pid pangkalan.idpangkalan, harus ada di database
	 * @throw Exception jika pangkalan ID tidak ada di database
	 */
	public function __construct($pid)
	{
		$pangkalan = new Cms_Model_Pangkalan(null, $pid);
		if ($pangkalan->exists()) {
			$this->_pangkalanId = $pid;
			$this->_objectPangkalan = $pangkalan;
		} else {
			throw new Exception('Invalid Pangkalan ID');
		}
	}
	
	public function table()
	{
		return new Zend_Db_Table('master.fasbek');
	}
	
	protected function _where()
	{
		$db = $this->table()->getAdapter();
		return $db->quoteInto($db->quoteIdentifier('idpangkalan') . " = ?", $this->_pangkalanId);
	}
	
	/**
	 * @return Cms_Model_Pangkalan
	 */
	public function getPangkalan()
	{
		return $this->_objectPangkalan;
	}
	
	/**
	 * Mengembalikan semua fasbek yang dimiliki pangkalan
	 * @return array
	 */
	public function getFasbeks()
	{
		if (!isset($this->_fasbeks)) {
			$table = $this->table();
			$this->_savedFasbeks = $this->_fasbeks = $table->fetchAll($this->_where())->toArray();
		}
		return $this->_fasbeks;
	}
	
	/**
	 * Mengembalikan object Form yang digunakan
	 * @return Zend_Form
	 */
	public function form()
	{
		$f = new Zend_Form();
		$f->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'fasbek/form.phtml')) 
		))
		->addElement('text', 'nama_item', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('text', 'jenis_item', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>50)),
			),
		))
		->addElement('text', 'puan_item', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('text', 'jumlah_unit', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		->addElement('text', 'keterangan', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('hidden', 'idfasbek', array(
			'isArray' => true,
			'required' => false,
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))
		;
		return $f;
	}
	
	/**
	 * Mengubah data sesuai input dari form
	 * @param Zend_Form|array
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		
		// transpose form data
		$transformedValues = array();
		$keys = array_keys($form);
		$this->_savedIds = array();
		foreach ($form['nama_item'] as $i => $namaItem) { // iterasi vertical
			$tempRow = array();
			$allNull = true;
			foreach ($keys as $k) { // iterasi horizontal
				if (!$form[$k][$i]) {
					$tempRow[$k] = null;
				} else {
					$tempRow[$k] = $form[$k][$i];
					$allNull = false;
				}
			}
			if (!$allNull) {
				$transformedValues[] = $tempRow;
			}
			if ($form['idfasbek'][$i]) {
				$this->_savedIds[] = $form['idfasbek'][$i];
			}
		}
		// fill fasbeks
		$this->_fasbeks = array();
		foreach ($transformedValues as $row) {
			$this->_fasbeks[] = array_merge(
				array('idpangkalan' => $this->_pangkalanId),
				$row
			);
		}
	}
	
	/**
	 * Menyimpan seluruh informasi ini ke dalam database
	 */
	public function save()
	{
		$table = $this->table();
		// hapus yg tidak ada
		if ($this->_savedIds) {
			// hapus yg tidak ada di submittan sekarang
			$table->delete($this->_where() . ' AND "idfasbek" <> ALL(ARRAY['.implode(',', $this->_savedIds).'])');
		} else {
			// hapus semua
			$table->delete($this->_where());
		}
		// simpan data yg ada
		foreach ($this->_fasbeks as $row) {
			$id = $row['idfasbek'];
			unset($row['idfasbek']);
			if ($id) {
				// update yg sudah ada
				$table->update($row, $table->getAdapter()->quoteInto('"idfasbek" = ?', $id));
			} else {
				// tambahkan yang belum ada
				$table->insert($row);
			}
		}
	}
}