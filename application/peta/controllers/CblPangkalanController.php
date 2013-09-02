<?php
/**
 * Manajemen Pangkalan Pendukung
 *
 * @author irfan.muslim@sangkuriang.co.id
 */

class Peta_CblPangkalanController extends App_CrudController
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
        $pangkalanModel = new Peta_Model_Datatables_PangkalanPendukung($this->_request->getParams('cbid'));
        $this->view->ppendukung = $pangkalanModel->petaPPendukung();
	}

	// tampilan list
	public function listAction()
	{
	}

	public function addAction()
	{
		$crud = new Peta_Model_Crud_PangkalanPendukung();
		$form = $crud->form();
        $form->getElement('id_cb_logistik')->setValue($this->_cblogistik->getId());
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

		$dt = new Peta_Model_Datatables_PangkalanPendukung($this->_request->getParams('cbid'));
		echo $dt->result();
	}

	public function editAction()
	{
        $this->view->id = $this->_request->getParam('id');
		$this->_edit(null, 'list', 'Peta_Model_Crud_PangkalanPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
		//		$this->_redirector->gotoSimple('list', 'cbl.fasbek', null, array('cbid'=>$this->_cblogistik->getId(),'cblid'=>$this->_cblogistik->getId()));
	}

	public function delAction()
	{
//        $this->view->id = $this->_cblogistik->getId();
		$this->_del(null, 'list', 'Peta_Model_Crud_PangkalanPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
	}

	public function simpanppAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$idmaster = $this->_request->getParam('idmaster');
		$ket = $this->_request->getParam('ket');
		$dermaga = $this->_request->getParam('dermaga');
		$labuh_udara = $this->_request->getParam('labuh_udara');
		$dock = $this->_request->getParam('dock');
		$bengkel = $this->_request->getParam('bengkel');
		$rumah = $this->_request->getParam('rumah');
		$mess = $this->_request->getParam('mess');
		$rumah_sakit = $this->_request->getParam('rumah_sakit');
		$umum = $this->_request->getParam('umum');
		$listrik = $this->_request->getParam('listrik');
		$kendaraan_bermotor = $this->_request->getParam('kendaraan_bermotor');
		$tanah = $this->_request->getParam('tanah');
		$perbekalan = $this->_request->getParam('perbekalan');
		$idcblog = $this->_cblogistik->getId();

		$theModele = new Peta_Model_Crud_PangkalanPendukung();
		if ($this->_request->getParam('cek'))
		{
			$data = array(
				'id_pangkalan'      => $idmaster,
				'keterangan' => $ket,
				'dermaga' => $dermaga,
				'labuh_udara' => $labuh_udara,
				'dock' => $dock,
				'bengkel' => $bengkel,
				'rumah' => $rumah,
				'mess' => $mess,
				'rumah_sakit' => $rumah_sakit,
				'umum' => $umum,
				'listrik' => $listrik,
				'kendaraan_bermotor' => $kendaraan_bermotor,
				'tanah' => $tanah,
				'perbekalan' => $perbekalan,
				'id_cb_logistik'      => $idcblog
			);
			$aa['dd'] = $theModele->simpanpp($data);
		}
		else
		{
			$where = array(
				'id_pangkalan = ?'      => $idmaster,
				'id_cb_logistik = ?'      => $idcblog
			);

			$aa['dd'] = $theModele->deletepp($where);
		}

		$theModel = new Peta_Model_Crud_PangkalanPendukung();
		$aa['pp'] = $theModel->petaPPendukung($idcblog);
		echo json_encode($aa);
	}

    // mendapatkan koordinat pangkalan
   	public function pangkalanLocationAction()
   	{
           $this->disableViewAndLayout(); //karena ajax
           $id = $this->_request->getPost('id', null);

           $Pmodel = new Peta_Model_DbTable_PangkalanPendukung();
           $Prow = $Pmodel->getByID( $id );

           $model = new Peta_Model_Pangkalan( $Prow['id_pangkalan'] );
           $koordinat = $model->get();


           $data['lon'] = $koordinat['x'];
           $data['lat'] = $koordinat['y'];

           echo json_encode( $data );

   	}
}