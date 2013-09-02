<?php
/** 
 * Generic Controller for Pergerakan Sendiri
 *
 * @author Febi
 */
 
class Ops_SendiriController extends App_CrudController
{
    protected $_kogas = '';
   	protected $_cbTableName = 'ops.sendiri_cb';
   	protected $_cbCrudClass = 'Ops_Model_Sendiri_Cb';
   	protected $_ruteClass = array(
   		'laut' => 'Ops_Model_Sendiri_Laut',
   		'udara' => 'Ops_Model_Sendiri_Udara',
   	);
   	protected $_perbandinganClass = 'Ops_Model_Sendiri_Perbandingan';


    protected $_satuan = array(
   		'udara' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph', 'function'=>'totalJarakInKm()'),
   		'laut' => array('jarak'=>'nautical mile', 'kecepatan'=>'knot', 'function'=>'totalJarakInNauMile()'),
   		'marinir' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph', 'function'=>'totalJarakInKm()'),
   		'darat' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph', 'function'=>'totalJarakInKm()'),
   		'linud' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph', 'function'=>'totalJarakInKm()'),
   	);
	
	public function postDispatch()
	{
		parent::postDispatch();
//		$this->view->kogas = $this->_kogas;
		$this->view->ruteClass = $this->_ruteClass;
	}

	// Decisive Point ga usah dulu
	
	/**
	 * Generic, display list of routes
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteIndex($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index' , 'rencana.operasi.sendiri');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('sendiri/rute-index.phtml');

        $skenarioId = $this->_getParam('skenario_id');
        $cbTable = new Zend_Db_Table($this->_cbTableName);
        $modelClass = $this->_ruteClass[$jenis];

        $this->view->jenis = $jenis;
//        $this->view->cb = $cbTable->find($skenarioId)->current();
        $this->view->items = $modelClass::allRows($skenarioId);

	}

	public function udaraIndexAction() {$this->_ruteIndex('udara');}
	public function lautIndexAction() {$this->_ruteIndex('laut');}
	public function marinirIndexAction() {$this->_ruteIndex('marinir');}
	public function daratIndexAction() {$this->_ruteIndex('darat');}
	public function linudIndexAction() {$this->_ruteIndex('linud');}

	/**
	 * Generic, add/edit a route
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteEdit($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('sendiri/rute-edit.phtml');

		$skenarioId = $this->_getParam('skenario_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($skenarioId, $id);


		if ($this->_request->isPost()) {
			if ($model->isValid($this->_request->getPost())) {
				$model->save();

                $this->view->successAlert = 'Rute Tersimpan';
				$this->_redirector->gotoSimple($jenis.'.index', null, null, array('skenario_id'=>$skenarioId));
			}
		}


		$this->view->jenis = $jenis;
		$this->view->satuan = $this->_satuan[$jenis];
		$this->view->model = $model;
	}
	
	public function udaraEditAction() {$this->_ruteEdit('udara');}
	public function lautEditAction() {$this->_ruteEdit('laut');}
	public function marinirEditAction() {$this->_ruteEdit('marinir');}
	public function daratEditAction() {$this->_ruteEdit('darat');}
	public function linudEditAction() {$this->_ruteEdit('linud');}
	
	/**
	 * Generic, delete a route
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteDel($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		
		$skenarioId = $this->_getParam('skenario_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($skenarioId, $id);
		
		if ($model->exists()) {
			$model->delete();
		}
		
		$this->_redirector->gotoSimple($jenis.'.index', null, null, array('skenario_id'=>$skenarioId));
	}
	
	public function udaraDelAction() {$this->_ruteDel('udara');}
	public function lautDelAction() {$this->_ruteDel('laut');}
	public function marinirDelAction() {$this->_ruteDel('marinir');}
	public function daratDelAction() {$this->_ruteDel('darat');}
	public function linudDelAction() {$this->_ruteDel('linud');}
	
	/**
	 * Generic, simulate movement of a route
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteSimulasi($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('sendiri/rute-simulasi.phtml');
		
		$skenarioId = $this->_getParam('skenario_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($skenarioId, $id);
		
		if (!$model->exists()) {
			return $this->_redirector->gotoSimple($jenis.'.index', null, null, array('skenario_id'=>$skenarioId));
		}
		
		$this->view->jenis = $jenis;
		$this->view->satuan = $this->_satuan[$jenis];
		$this->view->model = $model;
	}
	
	public function udaraSimulasiAction() {$this->_ruteSimulasi('udara');}
	public function lautSimulasiAction() {$this->_ruteSimulasi('laut');}
	public function marinirSimulasiAction() {$this->_ruteSimulasi('marinir');}
	public function daratSimulasiAction() {$this->_ruteSimulasi('darat');}
	public function linudSimulasiAction() {$this->_ruteSimulasi('linud');}
	
	/**
	 * Generic, simulate movement of all routes in a CB
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteSimulasiSemua($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('sendiri/rute-simulasi-semua.phtml');
		
		$skenarioId = $this->_getParam('skenario_id');
		$cbTable = new Zend_Db_Table($this->_cbTableName);
		$skenarioTable = new Zend_Db_Table('latihan.skenario');
		$modelClass = $this->_ruteClass[$jenis];
		
		$this->view->jenis = $jenis;
		$this->view->satuan = $this->_satuan[$jenis];
		$this->view->skenario = $skenarioTable->find($skenarioId)->current();
		$this->view->skenarioId = $skenarioId;
		$this->view->items = $modelClass::allObjects($skenarioId);
	}
	
	public function udaraSimulasiSemuaAction() {$this->_ruteSimulasiSemua('udara');}
	public function lautSimulasiSemuaAction() {$this->_ruteSimulasiSemua('laut');}
	public function marinirSimulasiSemuaAction() {$this->_ruteSimulasiSemua('marinir');}
	public function daratSimulasiSemuaAction() {$this->_ruteSimulasiSemua('darat');}
	public function linudSimulasiSemuaAction() {$this->_ruteSimulasiSemua('linud');}

//	// Menentukan CB pilihan
//	public function perbandinganAction()
//	{
//		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan.phtml');
//
//		$teamId = Zend_Auth::getInstance()->getStorage()->read()->id_team;
//		$modelClass = $this->_perbandinganClass;
//		$model = new $modelClass($teamId);
//
//		if ($this->_request->isPost()) {
//			// simpan cb terpilih
//			$model->saveCbTerpilih($this->_getParam('cb_pilihan', array()));
//			$this->view->successAlert = 'Tersimpan';
//		}
//
//		$this->view->cbList = $model->allCb();
//	}
//
//	/**
//	 * Generic, statistics detail of one CB
//	 * @param string $jenis udara/laut/marinir/...
//	 */
//	protected function _rutePerbandinganDetail($jenis)
//	{
//		if (!isset($this->_ruteClass[$jenis])) {
//			return $this->_redirector->gotoSimple('index');
//		}
//		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan-detail.phtml');
//
//		$cbId = $this->_getParam('cb_id');
//		$cbTable = new Zend_Db_Table($this->_cbTableName);
//		$modelClass = $this->_ruteClass[$jenis];
//
//		$this->view->jenis = $jenis;
//		$this->view->satuan = $this->_satuan[$jenis];
//		$this->view->cb = $cbTable->find($cbId)->current();
//		$this->view->items = $modelClass::allObjects($cbId);
//	}
//
//	public function udaraPerbandinganDetailAction() {$this->_rutePerbandinganDetail('udara');}
//	public function lautPerbandinganDetailAction() {$this->_rutePerbandinganDetail('laut');}
//	public function marinirPerbandinganDetailAction() {$this->_rutePerbandinganDetail('marinir');}
//	public function daratPerbandinganDetailAction() {$this->_rutePerbandinganDetail('darat');}
//	public function linudPerbandinganDetailAction() {$this->_rutePerbandinganDetail('linud');}

}