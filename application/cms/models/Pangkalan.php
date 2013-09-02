<?php
/**
 * Demo pemakaian CRUD Pangkalan
 * Model untuk table Pangkalan
 * 
 * @author Kanwil
 */
 
class Cms_Model_Pangkalan extends Cms_Model_Crud
{
	protected $_primary = 'idpangkalan';
	protected $_tableSchema = 'master';
	protected $_tableName = 'pangkalan';
	
	public function form()
	{
		$f = new Zend_Form();
		$f->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'partials/form-crud.phtml')) 
		))
		->addElement('select', 'idparent', array(
			'label' => 'Lantamal (jika kosong artinya ini adalah Lantamal)',
			'required' => false,
			'multiple' => false,
			'multiOptions' => $this->lantamalAsOptions(),
			'validators' => array(
				array('Db_RecordExists', false, array(
					'schema' => 'master',
					'table' => 'pangkalan',
					'field' => 'idpangkalan',
				)),
			),
		))
		->addElement('radio', 'jenis_pangkalan', array(
			'label' => 'Jenis Pangkalan',
			'required' => true,
			'multiOptions' => array(
				'Lanal' => 'Lanal/Lantamal',
				'Lanudal' => 'Lanudal',
				'Lanmar' => 'Lanmar',
			),
			'separator' => ' ',
		))
		->addElement('text', 'nama', array(
			'label' => 'Nama',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('StringLength', false, array('min'=>0, 'max'=>100)),
			),
		))
		->addElement('textarea', 'puan_fasharkan', array(
			'label' => 'Kemampuan Fasharkan',
			'required' => false,
			'filters' => array('StringTrim'),
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
	
	public function lantamalAsOptions()
	{
		$table = $this->table();
		$select = $table->select()
			->from('master.pangkalan', array('idpangkalan', 'nama'))
			->order('nama ASC')
			->where('idparent IS NULL');
		$raw = $table->fetchAll($select);
		$result = array('' => '-');
		foreach ($raw as $row) {
			$result[$row['idpangkalan']] = $row['nama'];
		}
		return $result;
	}
}