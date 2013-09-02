<?php
/**
 * Manajemen Pertamina Pendukung
 *
 * @author irfan.muslim@sangkuriang.co.id
 */

class Peta_CblPertaminaController extends App_CrudController
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
        $this->view->id = $this->_request->getParam('id', null);
   	}

   	// tampilan peta dan tombol2
   	public function indexAction()
   	{
   		$theModel = new Peta_Model_Datatables_DepoPendukung($this->_request->getParams('cbid'));
   		$this->view->list = $theModel->petaPendukung();
   	}

   	// tampilan list
   	public function listAction()
   	{
   	}

   	public function addAction()
   	{
       $this->_add(null, 'list', 'Peta_Model_CbldepoCrud', array('cbid'=>$this->_cblogistik->getId()));
   	}


    public function editAction()
   	{
       $this->_edit(null, 'list', 'Peta_Model_CbldepoCrud', array('cbid'=>$this->_cblogistik->getId()));
   	}

   	// mendapatkan koordinat depo
   	public function depoLocationAction()
   	{
           $this->disableViewAndLayout(); //karena ajax
           $id = $this->_request->getPost('id', null);

           $model = new Peta_Model_Pertamina( $id );
           $koordinat = $model->get();

           $data['lon'] = $koordinat['x'];
           $data['lat'] = $koordinat['y'];

           echo json_encode( $data );

   	}

   	// Penyedia data ke Datatables
   	public function dataapiAction()
   	{
        $this->disableViewAndLayout();
   		$dt = new Peta_Model_Datatables_DepoPendukung($this->_request->getParams('cbid'));
   		echo $dt->result();
   	}

    public function delAction()
   	{
   		$this->_del(null, 'list', 'Peta_Model_Crud_DepoPendukung',
   			array('cbid'=>$this->_cblogistik->getId())
   		);
   	}
}