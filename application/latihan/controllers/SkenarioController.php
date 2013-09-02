<?php
/**
 * CRUD untuk Skenario Latihan
 * @author Kanwil
 */
 
class Latihan_SkenarioController extends App_CrudController
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

		$dt = new Latihan_Model_Datatables_Skenario($this->_request->getParams());
		echo $dt->result();
	}

    public function daftarlatihanAction()
    {

    }

    public function dataapilatihanAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $dt = new Latihan_Model_Datatables_DaftarLatihan($this->_request->getParams());
        echo $dt->result();
    }
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Latihan_Model_Crud_Skenario');
	}

	public function editAction()
	{
		$this->_edit(null, 'index', 'Latihan_Model_Crud_Skenario');
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Latihan_Model_Crud_Skenario');
	}
	
	// daftar link 
	public function detailAction()
	{
		$id = $this->_request->getParam('skenario_id');
		$this->view->id = $id;
	}
	
	// upload/download buku
	public function bukuAction()
	{
		$id = $this->_request->getParam('skenario_id');
		$table = new Zend_Db_Table('latihan.skenario');
		$skenario = $table->find($id);
		if (!count($skenario)) return $this->_redirector->gotoSimple('index');
		$skenario = $skenario->current();
		
		$form = new Zend_Form();
		$form
		->addElement('file', 'buku1', array(
			'required' => false,
			'destination' => './upload', // hardcoded lokasi upload
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'pdf,doc,docx'),
			),
		))
		->addElement('file', 'buku2', array(
			'required' => false,
			'destination' => './upload', // hardcoded lokasi upload
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'pdf,doc,docx'),
			),
		))
		;
		
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				if ($form->buku1->isUploaded()) {
					$parts = pathinfo($form->buku1->getFileName());
					$buku1name = 'skenario_'.$id.'_buku_I.'.$parts['extension'];
					$form->buku1->addFilter('Rename', $buku1name);
				}
				if ($form->buku2->isUploaded()) {
					$parts = pathinfo($form->buku2->getFileName());
					$buku2name = 'skenario_'.$id.'_buku_II.'.$parts['extension'];
					$form->buku2->addFilter('Rename', $buku2name);
				}
				
				try {
					$form->buku1->receive();
					$form->buku2->receive();
				} catch (Exception $e) {
					return $this->_redirector->gotoSimple('index');
				}
				
				$newData = array();
				if ($form->buku1->isUploaded()) { // buku1 is uploaded
					if ($skenario->buku1) @unlink($skenario->buku1);
					$newData['buku1'] = './upload/'.$buku1name;
				}
				if ($form->buku2->isUploaded()) { // buku2 is uploaded
					if ($skenario->buku2) @unlink($skenario->buku2);
					$newData['buku2'] = './upload/'.$buku2name;
				}
				if ($newData) {
					$table->update($newData, $table->getAdapter()->quoteInto('id = ?', $id));
					// refresh skenario object
					$skenario = $table->find($id)->current();
				}
			}
		}

		$this->view->id = $id;
		$this->view->skenario = $skenario;
		$this->view->form = $form;
	}

	public function closeAction()
	{
		$id = $this->_getParam('skenario_id');

		$model = new Latihan_Model_Crud_Skenario();
//		$tabLog = new Management_Model_Crud_Login();
//
//		$dataLog = $model->getlogskenario($id);
//
//		foreach($dataLog as $log)
//		{
//			$tabLog->closelogin($log['id']);
//		}

        $model->closeSkenario($id);

		$this->_redirect('latihan/skenario/index');
	}

	// online PDF viewer
	public function readAction()
	{
		// pakai halaman khusus
		$this->getHelper('layout')->disableLayout();

		$model = new Cms_Model_DbTable_Skenario();
		$ref = $model->download($this->_request->getParam('id'), $this->_request->getParam('field'));
		if ($ref!=null) {
			$this->view->ref = $ref[$this->_request->getParam('field')];
		} else {
			$this->_redirector->gotoSimple('index');
		}
	}

    public function delbookAction()
    {
        $model = new Cms_Model_DbTable_Skenario();
        $model->deletebuku($this->_request->getParam('id'), $this->_request->getParam('field'));
        $this->_redirector->gotoSimple('index');
    }
	
	// Memilih CB Operasional tiap kogas untuk tim sendiri
	public function pilihCbAction()
	{
		// harus punya team id
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		if (!$identity->id_team) {
			return $this->_redirector->gotoSimple('index');
		}
		$model = new Latihan_Model_PemilihanCbOperasional($identity->id_team);
		// simpan cb terpilih
		if ($this->_request->isPost()) {	
			$model->setFromPost($this->_request->getPost());
			if ($model->isValid()) {
				$model->save();
				$this->view->successAlert = 'Tersimpan';
			}
		}
		// ambil cb pilihan tiap kogas
		$this->view->choices = $model->allChoices();
		$this->view->model = $model;
	}

    public function bukupasisAction()
    {

    }

    public function dataapibukuAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $this->_request->setParam('siteUrl',$this->view->siteUrl());

        $dt = new Latihan_Model_Datatables_Buku($this->_request->getParams());
        echo $dt->result();
    }
}
