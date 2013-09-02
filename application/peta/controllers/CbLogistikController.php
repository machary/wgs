<?php
/**
 * CRUD untuk Cb Logistik
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_CbLogistikController extends App_CrudController
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
		$dt = new Peta_Model_Datatables_CbLogistik($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$crud = new Peta_Model_Crud_CbLogistik();
		$form = $crud->form();
//		$form->setDefaults(array('nomor_telegram' => $crud->generate_NoTel()));
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$crud->setFromForm($form);
				$crud->save();
				$this->_redirector->gotoSimple($listAction);
			}
		}
		$this->view->form = $form;
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Peta_Model_Crud_CbLogistik');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Peta_Model_Crud_CbLogistik');
	}

	public function viewAction()
	{
		$obj = new Peta_Model_Crud_CbLogistik(null,$this->_request->getParam('id'));
		$this->view->obj = $obj;
	}

	public function detailAction()
	{
        $cb_id = $this->_request->getParam('cbid');
		$obj = new Peta_Model_Crud_CbLogistik(null,$cb_id);
		$row = $obj->toFormArray();
		$nomor_cb = $row['no_cb'];
		$this->view->nocb = $nomor_cb;

        $theModel = new Peta_Model_Datatables_RumahsakitPendukung(array( 'cbid' => $cb_id));
        $this->view->rspendukung = $theModel->petaPendukung();

        $pangkalanModel = new Peta_Model_Datatables_PangkalanPendukung(array( 'cbid' => $cb_id));
        $this->view->ppendukung = $pangkalanModel->petaPPendukung();

        $theModel = new Peta_Model_Datatables_DepoPendukung(array( 'cbid' => $cb_id));
        $this->view->depopendukung = $theModel->petaPendukung();
	}

}