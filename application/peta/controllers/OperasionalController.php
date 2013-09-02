<?php
class Peta_OperasionalController extends Zend_Controller_Action
{
    public function init()
    {

    }

    public function indexAction()
    {

    }

    public function decisiveAction()
    {
        $request = $this->getRequest();
        $form = new Peta_Form_Operasional_Decisive();
        $model = new Peta_Model_DbTable_Operasional();

        $normal_no_cb = $this->_getParam('no_cb_operasional');
        $get_nocb = str_replace('-','/',$normal_no_cb);
        $no_cb = array(
            'no_cb_operasional' => $get_nocb
        );

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                //$form->getElement('id_target')->setValue('1'); //hardcode
                $model->adddecisive($form->getValues(), $no_cb['no_cb_operasional']);
                $this->_redirect('peta/operasional/decisive/no_cb_operasional/' . $normal_no_cb);
            }
        }
        else
        {
            //$this->errorMessage = 'Penyimpanan Gagal Dilakukan';
            $form->populate($no_cb);
        }

        //$this->decisivedataapiAction($no_cb['no_cb_operasional']);
        $this->view->no_cb = $no_cb['no_cb_operasional'];
        $this->view->form = $form;
    }

    public function decisivedataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_Operasional();
        // Param
        $sEcho = intval( $this->_getParam( 'sEcho' ) );
        $sSearch = $this->_getParam( 'sSearch' );
        // Paging
        $offset = $this->_getParam( 'iDisplayStart' );
        $limit = $this->_getParam( 'iDisplayLength' );
        // Sort Order
        $sortColumn = $this->_getParam( 'iSortCol_0' );
        $order = $this->_getParam( 'sSortDir_0' );
        //custom filter
        $filter = $this->_getParam( 'filter' );
        $areaLevel = $this->_getParam( 'arealevel' );
        $areaID = $this->_getParam( 'areaid' );

        $no_cb = $this->_getParam( 'param_nocb' );

        //get rows
        $jsonString = $model->datatablesJSONApidecisive($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view, $no_cb);

        echo  $jsonString;
    }

    public function cblistAction()
    {

    }

    public function cbdataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_Operasional();
        // Param
        $sEcho = intval( $this->_getParam( 'sEcho' ) );
        $sSearch = $this->_getParam( 'sSearch' );
        // Paging
        $offset = $this->_getParam( 'iDisplayStart' );
        $limit = $this->_getParam( 'iDisplayLength' );
        // Sort Order
        $sortColumn = $this->_getParam( 'iSortCol_0' );
        $order = $this->_getParam( 'sSortDir_0' );
        //custom filter
        $filter = $this->_getParam( 'filter' );
        $areaLevel = $this->_getParam( 'arealevel' );
        $areaID = $this->_getParam( 'areaid' );

        //get rows
        $jsonString = $model->datatablesJSONApi($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view);

        echo  $jsonString;
    }

    public function addcboperasionalAction()
    {
        $request = $this->getRequest();
        $form = new Peta_Form_Operasional_AddCb();
        $model = new Peta_Model_DbTable_CbOperasional();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $identity = Zend_Auth::getInstance()->getStorage()->read();
                $model->addCb($form->getValues(), $identity->id_team);
                $this->_redirect('peta/operasional/cblist');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $this->view->form = $form;
    }

    public function editcbAction()
    {
        $request = $this->getRequest();
        $id = $this->_getParam('id');

        $userForm = new Peta_Form_Operasional_AddCb();
        $userModel = new Peta_Model_DbTable_CbOperasional();

        $result = $userModel->getCb($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $userModel->updateCb($userForm->getValues(), $id, $identity->id_team);
            $this->_redirect('peta/operasional/cblist');
        }else{
            if($result != null)
            {
                $userForm->populate($result);
            }
        }

        $this->view->form = $userForm;
    }
	
	/**
	 * Edit rute operasi milik suatu cb
	 * OBSOLETE!!!!
	 * @author Kanwil
	 */
	public function ruteAction()
	{
		$cbid = $this->_getParam('id');
		$model = new Peta_Model_RuteOperasional($cbid);
		
		if ($this->_request->isPost()) {
			$model->setPoints($this->_request->getPost('point'));
			$model->save();
			$this->view->successAlert = 'Rute Tersimpan';
		}
		
		$this->view->model = $model;
	}

	public function deletedecisiveAction()
    {
        $id = $this->_getParam('id');
        $no_cb = $this->_getParam('no_cb');
        $model = new Peta_Model_DbTable_Operasional();
        $model->removeDecisive($id);
        $this->_redirect('peta/operasional/decisive/no_cb_operasional/' . $no_cb);

    }
	
	/**
	 * Membandingkan rute yang dimiliki tiap CB
	 * @author Kanwil
	 */
	public function perbandinganAction()
	{
		$teamId = Zend_Auth::getInstance()->getStorage()->read()->id_team;
		$model = new Peta_Model_PerbandinganCbOperasional($teamId);
		
		if ($this->_request->isPost()) {
			// simpan cb terpilih
			$model->saveCbTerpilih($this->_getParam('cb_pilihan', array()));
			$this->view->successAlert = 'Tersimpan';
		}
		
		$this->view->cbList = $model->allCb();
	}
	
	/**
	 * Melihat detail statistik satu CB operasional
	 * @author Kanwil
	 */
	public function perbandinganDetailAction()
	{
		$cbId = $this->_getParam('cb_id');
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($cbId)->current();
	}
	
	// ==============================================================
	// ========================= RUTE UDARA =========================
	// ==============================================================
	
	public function udaraPerbandinganAction()
	{
		$teamId = Zend_Auth::getInstance()->getStorage()->read()->id_team;
		$model = new Peta_Model_PerbandinganCbOperasional($teamId);
		
		if ($this->_request->isPost()) {
			// simpan cb terpilih
			$model->saveCbTerpilih($this->_getParam('cb_pilihan', array()));
			$this->view->successAlert = 'Tersimpan';
		}
		
		$this->view->cbList = $model->allCb();
	}
	
	public function udaraPerbandinganDetailAction()
	{
		$cbId = $this->_getParam('cb_id');
		$cbTable = new Zend_Db_Table('public.cb_operasional');
		$this->view->cb = $cbTable->find($cbId)->current();
	}
}