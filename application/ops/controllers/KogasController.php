<?php
/** 
 * Generic Controller for Kogas Operasional
 *
 * @author Kanwil
 */
 
class Ops_KogasController extends App_CrudController
{
	protected $_kogas = '';
	protected $_cbTableName = '';
	protected $_cbCrudClass = '';
	protected $_ruteClass = array();
	protected $_perbandinganClass = '';
	protected $_panglima = false;
	
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
		$this->view->kogas = $this->_kogas;
		$this->view->ruteClass = $this->_ruteClass;
		$this->view->cbId = $this->_getParam('cb_id');
	}
	
	// List CB Operasional
	public function indexAction()
	{
        $this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/index.phtml');
		
		$table = new Zend_Db_Table($this->_cbTableName);
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		
		$this->view->items = $table->fetchAll($table->getAdapter()->quoteInto('team_id = ?', $identity->id_team));
	}

	// List CB Operasional
	public function setPanglimaAction()
	{
        $this->_redirector->gotoSimple('index', 'index', null, array());
	}

	// CRUD CB OPERASIONAL
	public function addCbAction()
	{
        $registry = Zend_Auth::getInstance()->getStorage()->read();
	    $this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/add-cb.phtml');
		$this->_add(null, 'index', $this->_cbCrudClass);
        if($registry->panglima)
        {
            $this->view->form->getElement('status_panglima')->setValue( $registry->panglima);
        }
        else
        {
            $this->view->form->getElement('status_panglima')->setValue( 0);
        }
	}
	
	public function editCbAction()
	{
        $registry = Zend_Auth::getInstance()->getStorage()->read();
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/edit-cb.phtml');
		$this->_edit(null, 'index', $this->_cbCrudClass);
        if($registry->panglima)
        {
            $this->view->form->getElement('status_panglima')->setValue( $registry->panglima);
        }
        else
        {
            $this->view->form->getElement('status_panglima')->setValue( 0);
        }
	}
	
	public function delCbAction()
	{
		$this->_del(null, 'index', $this->_cbCrudClass);
	}
	
	// Decisive Point ga usah dulu
	
	/**
	 * Generic, display list of routes
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteIndex($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-index.phtml');
		
		$cbId = $this->_getParam('cb_id');
		$cbTable = new Zend_Db_Table($this->_cbTableName);
		$modelClass = $this->_ruteClass[$jenis];
		
		$this->view->jenis = $jenis;
		$this->view->cb = $cbTable->find($cbId)->current();
		$this->view->items = $modelClass::allRows($cbId);
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
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-edit.phtml');
		
		$cbId = $this->_getParam('cb_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($cbId, $id);

		if ($this->_request->isPost()) {
            if ($model->isValid($this->_request->getPost())) {
                $model->save();
				$this->view->successAlert = 'Rute Tersimpan';
				$this->_redirector->gotoSimple($jenis.'.index', null, null, array('cb_id'=>$cbId));
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
		
		$cbId = $this->_getParam('cb_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($cbId, $id);
		
		if ($model->exists()) {
			$model->delete();
		}
		
		$this->_redirector->gotoSimple($jenis.'.index', null, null, array('cb_id'=>$cbId));
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
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-simulasi.phtml');
		
		$cbId = $this->_getParam('cb_id');
		$id = $this->_getParam('id');
		$modelClass = $this->_ruteClass[$jenis];
		$model = new $modelClass($cbId, $id);
		
		if (!$model->exists()) {
			return $this->_redirector->gotoSimple($jenis.'.index', null, null, array('cb_id'=>$cbId));
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
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-simulasi-semua.phtml');
		
		$cbId = $this->_getParam('cb_id');
		$cbTable = new Zend_Db_Table($this->_cbTableName);
		$modelClass = $this->_ruteClass[$jenis];
		
		$this->view->jenis = $jenis;
		$this->view->satuan = $this->_satuan[$jenis];
		$this->view->cb = $cbTable->find($cbId)->current();
		$this->view->cbId = $cbId;
		$this->view->items = $modelClass::allObjects($cbId);
	}
	
	public function udaraSimulasiSemuaAction() {$this->_ruteSimulasiSemua('udara');}
	public function lautSimulasiSemuaAction() {$this->_ruteSimulasiSemua('laut');}
	public function marinirSimulasiSemuaAction() {$this->_ruteSimulasiSemua('marinir');}
	public function daratSimulasiSemuaAction() {$this->_ruteSimulasiSemua('darat');}
	public function linudSimulasiSemuaAction() {$this->_ruteSimulasiSemua('linud');}
	
	// Menentukan CB pilihan
	public function perbandinganAction()
	{
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan.phtml');
		
		$teamId = Zend_Auth::getInstance()->getStorage()->read()->id_team;
		$modelClass = $this->_perbandinganClass;
		$model = new $modelClass($teamId);
		
		if ($this->_request->isPost()) {
			// simpan cb terpilih
			$model->saveCbTerpilih($this->_getParam('cb_pilihan', array()));
			$this->view->successAlert = 'Tersimpan';
		}
		
		$this->view->cbList = $model->allCb();
	}

	public function perbandinganPanglimaAction()
	{
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan-panglima.phtml');

		$teamId = Zend_Auth::getInstance()->getStorage()->read()->id_team;
		$modelClass = $this->_perbandinganClass;
		$model = new $modelClass($teamId);

		$this->view->cbPanglimaList = $model->allPanglimaCb();
		$this->view->cbNotPanglimaList = $model->allNotPanglimaCb();
        $this->view->cbClassName = $this->_cbTableName;
        $this->view->ruteclass = json_encode($this->_ruteClass);
        $this->view->satuan = json_encode($this->_satuan);
	}
	
	/**
	 * Generic, statistics detail of one CB
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _rutePerbandinganDetail($jenis)
	{
		if (!isset($this->_ruteClass[$jenis])) {
			return $this->_redirector->gotoSimple('index');
		}
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan-detail.phtml');
		
		$cbId = $this->_getParam('cb_id');
		$cbTable = new Zend_Db_Table($this->_cbTableName);
		$modelClass = $this->_ruteClass[$jenis];
		
		$this->view->jenis = $jenis;
		$this->view->satuan = $this->_satuan[$jenis];
		$this->view->cb = $cbTable->find($cbId)->current();
		$this->view->items = $modelClass::allObjects($cbId);
	}
	
	public function udaraPerbandinganDetailAction() {$this->_rutePerbandinganDetail('udara');}
	public function lautPerbandinganDetailAction() {$this->_rutePerbandinganDetail('laut');}
	public function marinirPerbandinganDetailAction() {$this->_rutePerbandinganDetail('marinir');}
	public function daratPerbandinganDetailAction() {$this->_rutePerbandinganDetail('darat');}
	public function linudPerbandinganDetailAction() {$this->_rutePerbandinganDetail('linud');}


    /**
   	 * List CB Operasional For View
   	 * @author Febi
   	 */
    public function penilaianAction()
    {
        $this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/penilaian-index.phtml');

        $table = new $this->_cbCrudClass();

        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $this->view->items = $table->getallps($identity->id);
    }

    /**
   	 * Generic, display list of routes for penilaian
     * difference only in the link
   	 * @param string $jenis udara/laut/marinir/...
     * @author Febi
   	 */
   	protected function _ruteNilaiIndex($jenis)
   	{
   		if (!isset($this->_ruteClass[$jenis])) {
   			return $this->_redirector->gotoSimple('penilaian');
   		}
   		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-penilaian-index.phtml');

   		$cbId = $this->_getParam('cb_id');
   		$cbTable = new Zend_Db_Table($this->_cbTableName);
   		$modelClass = $this->_ruteClass[$jenis];

   		$this->view->jenis = $jenis;
   		$this->view->cb = $cbTable->find($cbId)->current();
   		$this->view->items = $modelClass::allRows($cbId);
        $this->view->modelNilai = new Ops_Model_Penilaian();
   	}

   	public function udaraNilaiIndexAction() {$this->_ruteNilaiIndex('udara');}
   	public function lautNilaiIndexAction() {$this->_ruteNilaiIndex('laut');}
   	public function marinirNilaiIndexAction() {$this->_ruteNilaiIndex('marinir');}
   	public function daratNilaiIndexAction() {$this->_ruteNilaiIndex('darat');}
   	public function linudNilaiIndexAction() {$this->_ruteNilaiIndex('linud');}

    /**
     * Generic, simulate movement of a route
     * @param string $jenis udara/laut/marinir/...
     */
    protected function _ruteNilaiSimulasi($jenis)
    {
        if (!isset($this->_ruteClass[$jenis])) {
            return $this->_redirector->gotoSimple('penilaian');
        }
        $this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-nilai-simulasi.phtml');

        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $cbId = $this->_getParam('cb_id');
        $id = $this->_getParam('id');

        $modelClass = $this->_ruteClass[$jenis];
        $model = new $modelClass($cbId, $id);


        $Ptable = new Management_Model_DbTable_Penilai();
        $penilai = $Ptable->selectpenilaiwithlogin($identity->id);

        $team = new Management_Model_DbTable_Team();
        $teamDet = $team->gaetidteam($penilai['id_team']);

        $modelro = new Ops_Model_Ro($penilai['id_team']);
		$musuh = $modelro->musuhData($teamDet['kode_skenario']);

        if (!$model->exists()) {
            return $this->_redirector->gotoSimple($jenis.'.nilai.index', null, null, array('cb_id'=>$cbId));
        }

        $crud = new Ops_Model_Penilaian();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $crud->set('id_cb', $cbId);
            $crud->set('id_rute', $id);
            $crud->set('nilai', $data['nilai']);
            $crud->set('keterangan', $data['keterangan']);
            $crud->set('jenis', $jenis);

            $crud->save();

            $this->_helper->flashMessenger->addMessage('Data Tersimpan');

            return $this->_redirector->gotoSimple('rekap.index', null, null, array('cb_id'=>null));
        }

        $this->view->jenis = $jenis;
        $this->view->satuan = $this->_satuan[$jenis];
        $this->view->model = $model;
        $this->view->musuh = $musuh;
        $this->view->dataNilai = $crud->getScore( $cbId, $id, $jenis);
        $this->view->backUrl = $this->_getParam('backurl');
    }

    public function udaraNilaiSimulasiAction() {$this->_ruteNilaiSimulasi('udara');}
    public function lautNilaiSimulasiAction() {$this->_ruteNilaiSimulasi('laut');}
    public function marinirNilaiSimulasiAction() {$this->_ruteNilaiSimulasi('marinir');}
    public function daratNilaiSimulasiAction() {$this->_ruteNilaiSimulasi('darat');}
    public function linudNilaiSimulasiAction() {$this->_ruteNilaiSimulasi('linud');}

    // List CB Operasional
   	public function rekapIndexAction()
   	{
   		$this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rekap-index.phtml');

   		$table = new Zend_Db_Table($this->_cbTableName);
   		$identity = Zend_Auth::getInstance()->getStorage()->read();

        $Ptable = new Management_Model_DbTable_Penilai();
        $penilai = $Ptable->selectpenilaiwithlogin($identity->id);

        $data = array();

        foreach($table->fetchAll($table->getAdapter()->quoteInto('team_id = ?', $penilai['id_team'])) as $dat)
        {
            $detail = array();
            foreach($this->_ruteClass as $jenis => $jenisModel)
            {
                $table = new Zend_Db_Table($this->_cbTableName);

                $cbTable = new Zend_Db_Table($this->_cbTableName);
                $modelClass = $this->_ruteClass[$jenis];

                $modelNilai = new Ops_Model_Penilaian();
                foreach($modelClass::allRows($dat->id) as $ruteDetail)
                {
                    $detail['cb'] = $dat->nomor;
                    $detail['cb_id'] = $dat->id;
                    $detail['jenis'] = $jenis;
                    $detail['rute'] = $ruteDetail->nama;
                    $detail['rute_id'] = $ruteDetail->id;
                    $detail['nilai'] = $modelNilai->getScore( $dat->id, $ruteDetail->id, $jenis, 'nilai');

                    array_push($data, $detail);
                }
            }
        }

        $this->view->data = $data;
   	}
}