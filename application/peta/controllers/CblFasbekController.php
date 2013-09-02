<?php
/**
 * Manajemen Fasilitas Bekal
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_CblFasbekController extends App_CrudController
{
	protected $_cblogistik = null;
	
	/**
	 * @override pastikan ada cbl_id
	 */
	public function init()
	{
		parent::init();
		$cblId = $this->_request->getParam('cbid');
		if (!$cblId) {
			return $this->_redirector->gotoSimple('index', 'cb.logistik');
		}
		$cbl = new Peta_Model_Crud_CbLogistik(null, $cblId);
		if (!$cbl->exists()) {
			return $this->_redirector->gotoSimple('index', 'cb.logistik');
		}
		$this->_cblogistik = $cbl;
	}

	public function postDispatch()
	{
		$this->view->cbid = $this->_cblogistik;
	}

	// tampilan peta dan tombol2
	public function indexAction()
	{
		$theModel = new Peta_Model_Datatables_PangkalanPendukung($this->_request->getParams('cbid'));
		$this->view->ppendukung = $theModel->petaPPendukung();
	}

	// tampilan list
	public function listAction()
	{
	}

	public function addAction()
	{
		$crud = new Peta_Model_Crud_PangkalanPendukung();
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

		$dt = new Peta_Model_Datatables_FasbekPendukung($this->_request->getParams('cbid'));
		echo $dt->result();
	}

	public function editAction()
	{
		$this->_edit(null, 'list', 'Peta_Model_Crud_PangkalanPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}
	
	public function delAction()
	{
		$this->_del(null, 'list', 'Peta_Model_Crud_PangkalanPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}

	public function zazazaAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		// get koordinat
		$longitude = $this->_request->getParam('lon');
		$latitude = $this->_request->getParam('lat');


		$aa['ss'] = 'dsfsdf';

		echo json_encode($aa);
	}
}