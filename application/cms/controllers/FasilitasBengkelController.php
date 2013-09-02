<?php
/**
 * CRUD untuk Fasilitas Bengkel
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_FasilitasBengkelController extends App_CrudController
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
		$dt = new Cms_Model_Datatables_FasBengkel($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_FasBengkel');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_FasBengkel');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_FasBengkel');
	}

    public function viewAction()
    {
        $obj = new Cms_Model_FasBengkel(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }

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
		$importer = new Cms_Model_Import_Excel('master.fasilitas_bengkel', array(
			'idpangkalan', 'jenis_bengkel', 'panjang',
			'lebar',
			'kondisi',
			'jumlah',
			'luas',
			'keterangan',
		));
		/*
		Model Import Excel akan mengecek kevalidan tiap baris data excel menggunakan suatu Zend_Form
		Defaultnya, yang digunakan adalah Zend_Form hasil generate dari Model Crud
		Kalau mau pakai form lain:
		*/
		$model = new Cms_Model_FasBengkel();
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