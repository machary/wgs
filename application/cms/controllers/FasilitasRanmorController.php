<?php
/**
 * CRUD untuk Fasilitas Ranmor
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_FasilitasRanmorController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		/**/
		$dt = new Cms_Model_Datatables_FasRanmor($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_FasRanmor');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_FasRanmor');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_FasRanmor');
	}

    public function viewAction()
    {
        $obj = new Cms_Model_FasRanmor(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }

//	/**
//	 * Import from excel
//	 */
//	public function importAction()
//	{
//		$form = new Zend_Form();
//		$form->addElement('file', 'import_file', array(
//			'required' => true,
//			'validators' => array(
//				array('Count', false, 1),
//				array('Extension', false, 'xls,xlsx'),
//			),
//		));
//
//		if ($this->_request->isPost()) {
//			if ($form->isValid($this->_request->getPost())) {
//				if ($form->import_file->receive()) {
//					$this->_processExcel($form->import_file->getFileName());
//					$this->view->announcement = 'Import succeeded';
//				} else {
//					$form->import_file->addError('Upload failed');
//				}
//			}
//		}
//		$this->view->form = $form;
//	}
//
//	/**
//	 * Membaca file excel dan menyimpan isinya
//	 */
//	protected function _processExcel($filename)
//	{
//		$excel = PHPExcel_IOFactory::load($filename);
//		$excel->setActiveSheetIndex(0);
//		$worksheet = $excel->getActiveSheet();
//
//		$maxRow = $worksheet->getHighestRow();
//		$maxCol = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
//		$columns = array( // kolom database table sesuai urutan kolom excel
//			'idpangkalan', 'jenis',
//			'tipe', 'jumlah_unit',
//		);
//
//		$table = new Zend_Db_Table('master.fasilitas_ranmor');
//
//		// Validasi pakai form milik CRUD model
//		$model = new Cms_Model_FasRanmor();
//		$form = $model->form();
//
//		// iterasi semua baris, skip baris pertama (dianggap header)
//		for ($y=2; $y<=$maxRow; $y++) {
//			// grab data for 1 row
//			$row = array();
//			foreach ($columns as $x => $field) {
//				$row[$field] = $worksheet->getCellByColumnAndRow($x, $y)->getValue();
//			}
//			if ($form->isValid($row)) {
//				$table->insert($row);
//			}
//		}
//	}

	/**
	 * Import from excel
	 */
	public function importAction()
	{
		/*
		Constructor Model Import Excel menerima 2 parameter:
			1. nama table tempat menyimpan data
			2. daftar kolom table sesuai urutan kolom di excel
		Semua property sebenarnya bisa diset menggunakan method set<namaProperty>
		Contoh:
			dalam source code cms\models\Import\Excel.php
			ada `protected $_table`
			bisa diset dengan cara `$importer->setTable($tableSendiri)`

		*/
		$importer = new Cms_Model_Import_Excel('master.fasilitas_ranmor', array(
			'idpangkalan', 'jenis', 'tipe', 'jumlah_unit'
		));
		/*
		Model Import Excel akan mengecek kevalidan tiap baris data excel menggunakan suatu Zend_Form
		Defaultnya, yang digunakan adalah Zend_Form hasil generate dari Model Crud
		Kalau mau pakai form lain:
		*/
		$model = new Cms_Model_FasRanmor();
		$importer->setRowForm($model->form());
		/*
		Untuk lebih tau cara kerja class ini ya liat aja source codenya
		*/
		$form = $importer->importForm();

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				if ($form->import_file->receive()) {
					$importer->parse($form->import_file->getFileName());
					if ($importer->isValid()) {
						$importer->save();
						$this->view->announcement = 'Import succeeded';
					} else {
						$form->import_file->addError('Import failed');
					}
				} else {
					$form->import_file->addError('Upload failed');
				}
			}
		}
		$this->view->importer = $importer;
		$this->view->form = $form;
	}



}