<?php
/**
 * Manajemen Depo Pertamina Pendukung
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_CblDepoController extends App_CrudController
{
	protected $_cblogistik = null;
	
	/**
	 * @override pastikan ada cbl_id
	 */
//	public function init()
//	{
//		parent::init();
//		$cblId = $this->_request->getParam('cbid');
//		if (!$cblId) {
//			return $this->_redirector->gotoSimple('index', 'cb.logistik');
//		}
//		$cbl = new Peta_Model_Crud_CbLogistik(null, $cblId);
//		if (!$cbl->exists()) {
//			return $this->_redirector->gotoSimple('index', 'cb.logistik');
//		}
//		$this->_cblogistik = $cbl;
//	}

	public function postDispatch()
	{
		$this->view->cbid = $this->_cblogistik;
	}

	// tampilan peta dan tombol2
	public function indexAction()
	{

//		$theModel = new Peta_Model_Datatables_DepoPendukung($this->_request->getParams('cbid'));
//		$this->view->pendukung = $theModel->petaPendukung();
	}

	// tampilan list
	public function listAction()
	{
	}

	public function addAction()
	{
		$crud = new Peta_Model_Crud_DepoPendukung();
		$form = $crud->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$crud->setFromForm($form);
				$crud->set('id_cb_logistik', $this->_cblogistik->getId()); // tambahan
				$crud->save();
				$this->_redirector->gotoSimple('list', null, null, array('cbid'=>$this->_cblogistik->getId()));
			}
		}
		$this->view->form = $form;

	}

	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$dt = new Peta_Model_Datatables_DepoPendukung($this->_request->getParams('cbid'));
		echo $dt->result();
	}

	public function editAction()
	{
		$this->_edit(null, 'list', 'Peta_Model_Crud_DepoPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}
	
	public function delAction()
	{
		$this->_del(null, 'list', 'Peta_Model_Crud_DepoPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}
}