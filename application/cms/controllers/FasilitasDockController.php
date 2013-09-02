<?php
/**
 * CRUD untuk Fasilitas Dock
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_FasilitasDockController extends App_CrudController
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
		$dt = new Cms_Model_Datatables_FasDock($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_FasDock');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_FasDock');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_FasDock');
	}

    public function viewAction()
    {
        $obj = new Cms_Model_FasDock(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }
	
	/**
	 * Import from excel
	 * @author Kanwil
	 */
	public function importAction()
	{
		$form = new Zend_Form();
		$form->addElement('file', 'import_file', array(
			'required' => true,
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'xls,xlsx'),
			),
		));
		
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				if ($form->import_file->receive()) {
					$this->_processExcel($form->import_file->getFileName());
					$this->view->announcement = 'Import succeeded';
				} else {
					$form->import_file->addError('Upload failed');
				}
			}
		}
		$this->view->form = $form;
	}
	
	/**
	 * Membaca file excel dan menyimpan isinya
	 * @author Kanwil
	 */
	protected function _processExcel($filename)
	{
		$excel = PHPExcel_IOFactory::load($filename);
		$excel->setActiveSheetIndex(0);
		$worksheet = $excel->getActiveSheet();
		
		$maxRow = $worksheet->getHighestRow();
		$maxCol = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
		$columns = array( // kolom database table sesuai urutan kolom excel
			'idpangkalan', 'jenis',
			'nama', 'kondisi', 
			'kapasitas', 'panjang', 'lebar',
		);
		
		$table = new Zend_Db_Table('master.fasilitas_dock');
		// Validasi pakai form milik CRUD model
		$model = new Cms_Model_FasDock();
		$form = $model->form();
		// iterasi semua baris, skip baris pertama (dianggap header)
		for ($y=2; $y<=$maxRow; $y++) {
			// grab data for 1 row
			$row = array();
			foreach ($columns as $x => $field) {
				$row[$field] = $worksheet->getCellByColumnAndRow($x, $y)->getValue();
			}
			if ($form->isValid($row)) {
				$table->insert($row);
			}
		}
	}

}