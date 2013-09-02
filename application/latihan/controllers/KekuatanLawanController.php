<?php
/**
 * Manajemen Kekuatan Musuh dalam Skenario Latihan
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Latihan_KekuatanLawanController extends App_CrudController
{
	protected $_skenario = null;
	protected $_matra = null;

	/**
	 * @override pastikan ada skenario_id
	 */
	public function init()
	{
		parent::init();

		//menyimpan skenario id dari url
		$skenarioId = $this->_request->getParam('skenario_id');
//		if (!$skenarioId) {
//			return $this->_redirector->gotoSimple('index', 'skenario');
//		}
		$skenario = new Latihan_Model_Crud_Skenario(null, $skenarioId);
//		if (!$skenario->exists()) {
//			return $this->_redirector->gotoSimple('index', 'skenario');
//		}
		$this->_skenario = $skenario;

		$matra = $this->_request->getParam('matra');
		if (isset($matra)){
			$this->_matra = $matra;
		}

	}

    /*
     * note :
     * matra 1 = darat
     * matra 2 = laut
     * matra 3 = udara
     * */
	public function indexAction()
	{
		$this->view->skenario = $this->_skenario;
		$this->view->modelDarat = new Latihan_Model_KekuatanMusuh($this->_skenario,null, 1);
		$this->view->modelLaut = new Latihan_Model_KekuatanMusuh($this->_skenario,null, 2);
		$this->view->modelUdara = new Latihan_Model_KekuatanMusuh($this->_skenario,null, 3);
	}

	// daftar kekuatan laut (koordinat-kekuatan)
	public function lautAction()
	{
		$this->getHelper('layout')->disableLayout();
		$model = new Latihan_Model_KekuatanMusuh($this->_skenario,null,2);
		$this->view->items = $model->allWithKekuatan();
		$this->view->skenarioid = $this->_skenario->getId();
	}

	// daftar kekuatan darat (koordinat-kekuatan)
	public function daratAction()
	{
		$this->getHelper('layout')->disableLayout();
		$model = new Latihan_Model_KekuatanMusuh($this->_skenario,null,1);
		$this->view->items = $model->allWithKekuatan();
		$this->view->skenarioid = $this->_skenario->getId();
	}

	// daftar kekuatan udara (koordinat-kekuatan)
	public function udaraAction()
	{
		$this->getHelper('layout')->disableLayout();
		$model = new Latihan_Model_KekuatanMusuh($this->_skenario,null,3);
		$this->view->items = $model->allWithKekuatan();
		$this->view->skenarioid = $this->_skenario->getId();
	}

	// untuk add & edit darat
	public function daratEditAction()
	{
		$this->getHelper('layout')->disableLayout();

		$model = new Latihan_Model_KekuatanMusuh($this->_skenario, $this->_request->getParam('id'),1);
		if ($this->_request->isPost()) {

			if ($model->isValid($this->_request->getPost())) {
                //parent::disableViewAndLayout();
				$model->save();
				$this->getHelper('viewRenderer')->setNoRender(true);
				$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,1);
				echo json_encode($x->allWithKekuatan());

			} else { $this->view->model = $model; }

		} else { $this->view->model = $model; }
	}

	// untuk add & edit laut
	public function lautEditAction()
	{
		$this->getHelper('layout')->disableLayout();

		$model = new Latihan_Model_KekuatanMusuh($this->_skenario, $this->_request->getParam('id'),2);
		if ($this->_request->isPost()) {

			if ($model->isValid($this->_request->getPost())) {

				$model->save();
				$this->getHelper('viewRenderer')->setNoRender(true);
				$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,2);
				echo json_encode($x->allWithKekuatan());

			} else { $this->view->model = $model; }

		} else { $this->view->model = $model; }
	}

	// untuk add & edit udara
	public function udaraEditAction()
	{
		$this->getHelper('layout')->disableLayout();

		$model = new Latihan_Model_KekuatanMusuh($this->_skenario, $this->_request->getParam('id'),3);
		if ($this->_request->isPost()) {

			if ($model->isValid($this->_request->getPost())) {

				$model->save();
				$this->getHelper('viewRenderer')->setNoRender(true);
				$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,3);
				echo json_encode($x->allWithKekuatan());

			} else { $this->view->model = $model; }

		} else { $this->view->model = $model; }
	}

	public function simbolKekuatanLautAction()
	{
		$parentid = $this->_request->getParam('parentid');

		$this->getHelper('layout')->disableLayout();
		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,2);
		$this->view->simbol = $x->jenisOptionSimbol(2);
		$this->view->parentid = $parentid;
		$y = new Latihan_Model_KekuatanMusuh($this->_skenario,$parentid,2);
		$this->view->detail = $y->details();
		$this->view->matra = 2;
	}

	public function simbolKekuatanDaratAction()
	{
		$parentid = $this->_request->getParam('parentid');

		$this->getHelper('layout')->disableLayout();
		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,1);
		$this->view->simbol = $x->jenisOptionSimbol(1);
		$this->view->parentid = $parentid;
		$y = new Latihan_Model_KekuatanMusuh($this->_skenario,$parentid,1);
		$this->view->detail = $y->details();
		$this->view->matra = 1;
	}

	public function simbolKekuatanUdaraAction()
	{
		$parentid = $this->_request->getParam('parentid');

		$this->getHelper('layout')->disableLayout();
		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,3);
		$this->view->simbol = $x->jenisOptionSimbol(3);
		$this->view->parentid = $parentid;
		$y = new Latihan_Model_KekuatanMusuh($this->_skenario,$parentid,3);
		$this->view->detail = $y->details();
		$this->view->matra = 3;
	}

	public function saveDetailAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$arr['parent_id'] = (int)$this->_request->getParam('parentid');
		$arr['id_symbol'] = (int)$this->_request->getParam('idsimbol');
        $arr['jumlah'] = (int)$this->_request->getParam('jumlah');
        $arr['keterangan'] = $this->_request->getParam('keterangan');

		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,$this->_request->getParam('matra'));

		$arr['dd'] = $x->simpank_detail($arr);
		$arr['allmusuh'] = $x->allWithKekuatan();

		echo json_encode($arr);
	}

	public function hapusDetailAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$model = new Latihan_Model_KekuatanMusuh($this->_skenario);
		$model->deletek_detail($this->_request->getParam('id'));

		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,$this->_request->getParam('matra'));

		echo json_encode($x->allWithKekuatan());
	}

	// menghapus kekuatan Darat/Laut/Udara
	public function hapusKekuatanAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$model = new Latihan_Model_KekuatanMusuh($this->_skenario, $this->_request->getParam('id'),$this->_request->getParam('matra'));
		if ($model->exists()) {
			$model->delete();
		}

		$x = new Latihan_Model_KekuatanMusuh($this->_skenario,null,$this->_request->getParam('matra'));

		echo json_encode($x->allWithKekuatan());
	}


}