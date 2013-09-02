<?php
/**
 * Demo pemakaian CRUD Referensi
 * Model untuk table Referensi
 * 
 * @author Kanwil
 */
 
class Cms_Model_Referensi extends Cms_Model_Crud
{
	protected $_primary = 'idreferensi';
	protected $_tableSchema = 'master';
	protected $_tableName = 'referensi';
	
	public function form()
	{
		$f = new Zend_Form();
		$f->setMethod('post')
		->setEnctype('multipart/form-data')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'referensi/form.phtml')) 
		))
		->addElement('file', 'filepath', array(
			'label' => 'Upload File',
			'required' => true,
			'destination' => './upload/referensi/', // hardcoded lokasi upload
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'pdf'),
			),
		))
		->addElement('text', 'nama', array(
			'label' => 'Nama',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('select', 'jenis', array(
			'label' => 'Jenis',
			'required' => true,
			'multiple' => false,
			'multiOptions' => $this->_jenisOptions(),
			'validators' => array(
			),
		))
		// terakhir ditambahkan tombol submit
		->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		))
		;
		return $f;
	}
	
	protected function _jenisOptions()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$query = $db->select()
			->from('master.jenis_referensi', array('nama'))
			->order('id ASC')
		;
		$jenis = $db->fetchCol($query);
		return array_combine($jenis, $jenis);
	}
	
	/**
	 * @override tambahkan path pada file
	 */
	public function setFromForm($form, $file)
	{
		parent::setFromForm($form);
		$this->_values['filepath'] = './upload/referensi/' . $file;
	}
	
	/**
	 * @override tambahkan penghapusan file
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		// hapus file
		@unlink($this->_values['filepath']);
		// hapus row
		$table = $this->table();
		$table->delete($this->where());
		$this->_id = null;
	}
}