<?php
/**
 * Pengaturan Rute Operasional untuk satu CB Operasional
 *
 * @author Kanwil
 */
 
class Peta_RuteOperasionalController extends App_Controller
{
	protected $_cbId;
	
	public function init()
	{
		parent::init();
		$this->_cbId = $this->_getParam('cbid');
	}
	
	// daftar rute
	public function indexAction()
	{
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($this->_cbId)->current();
		$this->view->items = Peta_Model_RuteOperasional::allRows($this->_cbId);
	}
	
	// edit/add rute operasi 
	public function editAction()
	{
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteOperasional($this->_cbId, $id);

		if ($this->_request->isPost()) {
			if ($model->isValid($this->_request->getPost())) {
				$model->save();
				$this->view->successAlert = 'Rute Tersimpan';
				$this->_redirector->gotoSimple('index', null, null, array('cbid'=>$this->_cbId));
			}
		}
		
		$this->view->model = $model;
	}
	
	// hapus 
	public function delAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteOperasional($this->_cbId, $id);
		if ($model->exists()) {
			$model->delete();
		}
		$this->_redirector->gotoSimple('index', null, null, array('cbid'=>$this->_cbId));
	}
	
	// simulasi gerak formasi mengikuti rute
	public function simulasiAction()
	{
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteOperasional($this->_cbId, $id);
		if (!$model->exists()) {
			return $this->_redirector->gotoSimple('index', null, null, array('cbid'=>$this->_cbId));
		}
		$this->view->model = $model;
	}
	
	// simulasi gerak semua formasi dalam 1 CB
	public function simulasiSemuaAction()
	{
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($this->_cbId)->current();
		$this->view->cbId = $this->_cbId;
		$this->view->items = Peta_Model_RuteOperasional::allObjects($this->_cbId);
	}
	
	// ==============================================================
	// ========================= RUTE UDARA =========================
	// ==============================================================
	
	// daftar rute
	public function udaraIndexAction()
	{
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($this->_cbId)->current();
		$this->view->items = Peta_Model_RuteUdaraOperasional::allRows($this->_cbId);
	}
	
	// edit/add rute operasi
	public function udaraEditAction()
	{
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteUdaraOperasional($this->_cbId, $id);

		if ($this->_request->isPost()) {
			if ($model->isValid($this->_request->getPost())) {
				$model->save();
				$this->view->successAlert = 'Rute Tersimpan';
				$this->_redirector->gotoSimple('udara.index', null, null, array('cbid'=>$this->_cbId));
			}
		}
		
		$this->view->model = $model;
	}
	
	// hapus
	public function udaraDelAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteUdaraOperasional($this->_cbId, $id);
		if ($model->exists()) {
			$model->delete();
		}
		$this->_redirector->gotoSimple('udara.index', null, null, array('cbid'=>$this->_cbId));
	}
	
	// simulasi gerak formasi 1 rute
	public function udaraSimulasiAction()
	{
		$id = $this->_getParam('id');
		$model = new Peta_Model_RuteUdaraOperasional($this->_cbId, $id);
		if (!$model->exists()) {
			return $this->_redirector->gotoSimple('udara.index', null, null, array('cbid'=>$this->_cbId));
		}
		$this->view->model = $model;
	}
	
	// simulasi gerak semua formasi dalam 1 CB
	public function udaraSimulasiSemuaAction()
	{
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($this->_cbId)->current();
		$this->view->cbId = $this->_cbId;
		$this->view->items = Peta_Model_RuteUdaraOperasional::allObjects($this->_cbId);
	}
}