<?php
/**
 * Referensi Controller
 *
 * Untuk lihat dan tambah referensi (file PDF)
 *
 * @author Kanwil
 */
 
class Cms_ReferensiController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
		$jenis = new Cms_Model_JenisReferensi();
		$this->view->jenis = $jenis;
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		
		$dt = new Cms_Model_Datatables_Referensi($this->_request->getParams());
		echo $dt->result();
	}
	
	public function addAction()
	{
		$crud = new Cms_Model_Referensi(null);
		$form = $crud->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				try {
                    $originalFilename = pathinfo($form->filepath->getFileName());
                    $newFilename = rawurlencode($originalFilename['basename']);
					$form->filepath->receive();
				} catch (Exception $e) {
					return $this->_redirector->gotoSimple('index');
				}
				$crud->setFromForm($form, $newFilename);
				$crud->save();
				$this->_redirector->gotoSimple('index');
			}
		}
		$this->view->form = $form;
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_Referensi');
	}

	// online PDF viewer
	public function readAction()
	{
        //print_r($this->_request->getParam('id'));exit;
		// pakai halaman khusus
		$this->getHelper('layout')->disableLayout();
		
		$ref = new Cms_Model_Referensi(null, $this->_request->getParam('id'));
		if ($ref->exists()) {
			$this->view->ref = $ref;
		} else {
			$this->_redirector->gotoSimple('index');
		}
	}
	
	// download lewat sini
	public function downloadAction()
	{
        //print_r($this->_request->getParam('id'));exit;

		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		$ref = new Cms_Model_Referensi(null, $this->_request->getParam('id'));
		if ($ref->exists()) {
			$this->_redirector->gotoUrl($ref->get('filepath'));
		} else {
			$this->_redirector->gotoSimple('index');
		}
	}
	
	// CRUD jenis referensi
	public function jenisAction()
	{
		$jenis = new Cms_Model_JenisReferensi();
		if ($this->_request->isPost()) {
			$jenis->setFromPost($this->_request->getPost());
			if ($jenis->isValid()) {
				$jenis->save();
				$this->view->successAlert = 'Tersimpan';
			}
		}
		
		$this->view->jenis = $jenis;
	}

}