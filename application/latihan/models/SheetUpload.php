<?php
/**
 * Model untuk table FEbi
 *
 * @author Febi
 */

class Latihan_Model_SheetUpload extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'rol_excel_uploaded';

	public function form()
	{
		$f = new Zend_Form();
		$f->setMethod('post')
			->setEnctype('multipart/form-data')
			->setDecorators(array(
			array('ViewScript', array('viewScript' => 'sheet/form.phtml'))
		))
			->addElement('file', 'filepath', array(
			'label' => 'File ROL ( xls, xlsx, ods )',
			'required' => true,
			'destination' => './rol/', // hardcoded lokasi upload
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, array( 'xls', 'xlsx', 'ods')),
			),
		))
			->addElement('text', 'nama', array(
			'label' => 'Nama File Sheet',
			'required' => true,
			'multiple' => false,
			'size' => 100,
			'max-length' => 255,
			'validators' => array(
			),
		))
			->addElement('hidden', 'id_user', array(
			'required' => true,
		))
		// terakhir ditambahkan tombol submit
			->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Upload',
		))
		;

		return $f;
	}

	public function getRolByRolID($rolID)
	{
		$query = $this->select()
			->from(array('rol' => $this->_tableName))
			->where('rol.id_rol_excel = ?', $rolID);
		$result = $this->fetchRow($query);

		if(count($result))
		{
			return $result;
		}else{
			return null;
		}
	}

	/**
	 * @override tambahkan path pada file
	 */
	public function setFromPost($post, $file)
	{
		$this->_values = $post;
		//$this->_values['filepath'] = './rol/converted/' . $file;
        $this->_values['filepath'] = './rol/' . $file;
	}

	/**
	 * @override tambahkan penghapusan file
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		// hapus file
		@unlink($this->_values['filepath']);

		$realFile = str_replace( '.htm', '', str_replace( 'converted/', '', $this->_values['filepath']));

		@unlink( $realFile . '.xls');
		@unlink( $realFile . '.xlsx');
		@unlink( $realFile . '.ods');

		// hapus row
		$table = $this->table();
		$table->delete($this->where());
		$this->_id = null;
	}

}