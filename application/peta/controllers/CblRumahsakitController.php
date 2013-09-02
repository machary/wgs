<?php
/**
 * Manajemen Depo Rumahsakit Pendukung
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_CblRumahsakitController extends App_CrudController
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


        $cb_data = $cbl->toRowArray();

        $this->view->nocb = $cb_data['no_cb'];

		$this->_cblogistik = $cbl;
	}

	public function postDispatch()
	{
		$this->view->cbid = $this->_cblogistik;
	}

	// tampilan peta dan tombol2
	public function indexAction()
	{
		$theModel = new Peta_Model_Datatables_RumahsakitPendukung($this->_request->getParams('cbid'));
		$this->view->rspendukung = $theModel->petaPendukung();
	}

	// tampilan list
	public function listAction()
	{
	}

	public function addAction()
	{
        $form = new Peta_Form_Logistik_Rs();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
                parent::disableViewAndLayout();
                $model = new Peta_Model_DbTable_Rspendukung();
                $_POST['id_cb_logistik'] = $this->_cblogistik->getId();
                $model->insert( $_POST );
				$this->_redirector->gotoSimple('list', null, null, array('cbid'=>$this->_cblogistik->getId()));
			}
		}
        $this->view->form = $form;
	}

	// mendapatkan koordinat rumah sakit
	public function rsLocationAction()
	{
        $this->disableViewAndLayout(); //karena ajax
        $id = $this->_request->getPost('id', null);

        $model = new Peta_Model_Rumahsakit( $id );
        $koordinat = $model->get();

        $data['lon'] = $koordinat['x'];
        $data['lat'] = $koordinat['y'];

        echo json_encode( $data );

	}

	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$dt = new Peta_Model_Datatables_RumahsakitPendukung($this->_request->getParams('cbid'));
		echo $dt->result();
	}

	public function editAction()
	{
        $id = $this->_request->getParam('id');

        $form = new Peta_Form_Logistik_Rs();
        $model = new Peta_Model_DbTable_Rspendukung();

		if ($this->_request->isPost()) {
                $model->update( $_POST, "id = '". $id ."'");
				$this->_redirector->gotoSimple('list', null, null, array('cbid'=>$this->_cblogistik->getId()));
		} else {
            $data = $model->getByID( $id );
            $this->view->form = $form->populate($data);
            $this->view->id = $id;
            $this->render('add');
        }
	}
	
	public function delAction()
	{
		$this->_del(null, 'list', 'Peta_Model_Crud_RumahsakitPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}
}