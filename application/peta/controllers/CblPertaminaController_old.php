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
		$this->_cblogistik = $cbl;
	}

	public function postDispatch()
	{
		$this->view->cbid = $this->_cblogistik;
	}

	// tampilan peta dan tombol2
	public function indexAction()
	{
        $aModel = new Peta_Model_Datatables_DepoPendukung($this->_request->getParams('cbid'));
        $this->view->apendukung = $aModel->petaPendukung();
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

		$dt = new Peta_Model_Datatables_PangkalanPendukung($this->_request->getParams('cbid'));
		echo $dt->result();
	}

	public function editAction()
	{
		$this->_edit(null, 'list', 'Peta_Model_Crud_PangkalanPendukung',
			array('cbid'=>$this->_cblogistik->getId())
		);
		//		$this->_redirector->gotoSimple('list', 'cbl.fasbek', null, array('cbid'=>$this->_cblogistik->getId(),'cblid'=>$this->_cblogistik->getId()));
	}

	public function delAction()
	{
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

	/**
	 * @author irfan.muslim@sangkuriang.co.id
	 */
	public function viewrAction()
	{
		$this->getHelper('layout')->disableLayout();

		// get koordinat
		$longitude = $this->_request->getParam('lon');
		$latitude = $this->_request->getParam('lat');

		// ambil dari database

		if (isset($longitude)){
			$map = new Peta_Model_Map();
			$idpangkalan = $map->getIdDepoFromCoord($longitude, $latitude);
		}

		$obj = new Peta_Model_Pertamina($idpangkalan);
		$this->view->obj = $obj;
//		$this->view->pp = $obj->all();
	}


}