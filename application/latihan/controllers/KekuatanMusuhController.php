<?php
/**
 * Manajemen Kekuatan Musuh dalam Skenario Latihan
 * 
 * @author Kanwil
 * @edited irfan.muslim@sangkuriang.co.id
 */
 
class Latihan_KekuatanMusuhController extends App_CrudController
{
	protected $_skenario = null;
	
	/**
	 * @override pastikan ada skenario_id
	 */
	public function init()
	{
		parent::init();
		$skenarioId = $this->_request->getParam('skenario_id');
		if (!$skenarioId) {
			return $this->_redirector->gotoSimple('index', 'skenario');
		}
		$skenario = new Latihan_Model_Crud_Skenario(null, $skenarioId);
		if (!$skenario->exists()) {
			return $this->_redirector->gotoSimple('index', 'skenario');
		}
		$this->_skenario = $skenario;
	}
	
	public function postDispatch()
	{
		$this->view->skenario = $this->_skenario;
	}
	
	// tampilan peta dan tombol2
	public function indexAction()
	{
	}
	
    /**
   	 * Kekuatan musuh darat
   	 */
	public function daratAction()
	{
        if($this->isAjax())
        {
            $this->disableViewAndLayout();
            $dt = new Latihan_Model_Datatables_KekuatanMusuh_Darat($this->_request->getParams());
            echo $dt->result();
        }
    }

    public function daratAddAction()
    {
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Darat($this->_skenario, null);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/darat/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function daratEditAction()
    {
        $id = $this->_request->getParam('id');
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Darat($this->_skenario, $id);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/darat/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function daratDelAction()
   	{
        $this->disableViewAndLayout();
   		$id = $this->_request->getParam('id');
   		$model = new Latihan_Model_KekuatanMusuh_Darat($this->_skenario,  $id);
   		if ($model->exists()) {
   			$model->delete();
            $model->deleteDetail();
   		}
        $this->redirectTo('latihan/kekuatan.musuh/darat/skenario_id/'. $this->_skenario->getId());
   	}
    /**
   	 * === End Kekuatan musuh darat ===
   	 */

	// gotoSimple include skenario_id
	protected function _redirectTo($action)
	{
		$this->_redirector->gotoSimple($action, null, null, array('skenario_id'=>$this->_skenario->getId()));
	}
	
    /**
   	 * Kekuatan musuh udara
   	 */
	public function udaraAction()
	{
        if($this->isAjax())
        {
            $this->disableViewAndLayout();
            $dt = new Latihan_Model_Datatables_KekuatanMusuh_Udara($this->_request->getParams());
            echo $dt->result();
        }
    }

    public function udaraAddAction()
    {
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Udara($this->_skenario, null);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();

            parent::printArray( $_POST );
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/udara/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function udaraEditAction()
    {
        $id = $this->_request->getParam('id');
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Udara($this->_skenario, $id);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/udara/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function udaraDelAction()
   	{
        $this->disableViewAndLayout();
   		$id = $this->_request->getParam('id');
   		$model = new Latihan_Model_KekuatanMusuh_Udara($this->_skenario,  $id);
   		if ($model->exists()) {
   			$model->delete();
            $model->deleteDetail();
   		}
        $this->redirectTo('latihan/kekuatan.musuh/udara/skenario_id/'. $this->_skenario->getId());
   	}
    /**
   	 * === End Kekuatan musuh udara ===
   	 */

    /**
   	 * Kekuatan musuh laut
   	 */
	public function lautAction()
	{
        if($this->isAjax())
        {
            $this->disableViewAndLayout();
            $dt = new Latihan_Model_Datatables_KekuatanMusuh_Laut($this->_request->getParams());
            echo $dt->result();
        }
    }

    public function lautAddAction()
    {
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Laut($this->_skenario, null);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();

            parent::printArray( $_POST );
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/laut/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function lautEditAction()
    {
        $id = $this->_request->getParam('id');
        $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        $model = new Latihan_Model_KekuatanMusuh_Laut($this->_skenario, $id);
        $jenis = $simTaktis->jenisSimbol();
        $unsur = $simTaktis->getAll();

        if ($this->_request->isPost()) {
            parent::disableViewAndLayout();
            $model->setFromPost( $_POST );
            $model->save();
            $this->redirectTo('latihan/kekuatan.musuh/laut/skenario_id/'. $this->_skenario->getId());
        }

        $this->view->jenis = $jenis;
        $this->view->simTaktis = $simTaktis; //model
        $this->view->model = $model;
    }

    public function lautDelAction()
   	{
        $this->disableViewAndLayout();
   		$id = $this->_request->getParam('id');
   		$model = new Latihan_Model_KekuatanMusuh_Laut($this->_skenario,  $id);
   		if ($model->exists()) {
   			$model->delete();
            $model->deleteDetail();
   		}
        $this->redirectTo('latihan/kekuatan.musuh/laut/skenario_id/'. $this->_skenario->getId());
   	}
    /**
   	 * === End Kekuatan musuh laut ===
   	 */

    public function pergerakanAction()
   	{
   		if ($this->_request->isXmlHttpRequest()) {
   			$this->getHelper('viewRenderer')->setNoRender(true);
   			$this->getHelper('layout')->disableLayout();

   			$dt = new Latihan_Model_Datatables_KekuatanMusuh_Pergerakan($this->_request->getParams());
   			echo $dt->result();
   		}
   	}

   	public function pergerakanAddAction()
   	{
   		// add
   		$request = $this->getRequest();
   		$identity = Zend_Auth::getInstance()->getStorage()->read();
   		$model = new Latihan_Model_DbTable_KekuatanMusuhPergerakan();
   		$form = new Latihan_Form_KekuatanMusuhPergerakan();
   		$dataDb = $model->editpergerakan($request->getParam('skenario_id'));

   		$data = $model->editpergerakan($request->getParam('skenario_id'));

   		if($request->isPost() AND $form->isValid($this->_request->getPost()))
   		{
   			$dataDb = $model->editpergerakan($request->getParam('skenario_id'));
   			$countId = count($dataDb);
   			$simbolValue = explode('|',$form->getValue('simbol_value'));
   			$editCount = count($simbolValue);

   			$valueForm = $form->getValues();
   			$idSimTak = explode('|', $valueForm['simbol_value']);
   			$point = explode('|', $valueForm['point']);
   			$rotate = explode('|', $valueForm['rotation']);
   			$size = explode('|', $valueForm['size']);

   			if($countId == null) //baru
   			{
   				$count = count($idSimTak)-1;
   				for($x=0;$x<=$count;$x++)
   				{
   					$model->saveSimbolPergerakan($valueForm['tanggal'], $valueForm['waktu'], $idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $request->getParam('skenario_id'), $valueForm['keterangan']);

   				}
   				$this->_redirect('latihan/kekuatan.sendiri/skenario_id/'.$request->getParam('skenario_id'));
   			}
   			elseif($editCount == $countId) //update
   			{
   				$x=0;
   				foreach($dataDb as $dat)
   				{
   					$model->updatepergerakan($valueForm['tanggal'], $valueForm['waktu'], $idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $dat['id'], $valueForm['keterangan']);
   					$x++;
   				}
   				$this->_redirect('latihan/kekuatan.sendiri/skenario_id/'.$request->getParam('skenario_id'));
   			}
   			elseif($editCount != $countId) //delete and insert
   			{
   				$model->deletepergerakan($request->getParam('skenario_id'));

   				$count = count($idSimTak)-1;
   				for($x=0;$x<=$count;$x++)
   				{
   					$model->saveSimbolPergerakan($valueForm['tanggal'], $valueForm['waktu'], $idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $request->getParam('skenario_id'), $valueForm['keterangan']);

   				}

   				$this->_redirect('latihan/kekuatan.sendiri/skenario_id/'.$request->getParam('skenario_id'));
   			}
   		}
   		else
   		{
   			if($dataDb != null)
   			{
   				$pergerakan = array();
   				if(!empty($data))
   				{
   					$id_pergerakan = array();
   					$point = array();
   					$rotation = array();
   					$size = array();
   					foreach($data as $key=>$data)
   					{
   						$pergerakan[$key]['id_simbol_pergerakan'] = $data['id_simbol_pergerakan'];
   						$pergerakan[$key]['point'] = $data['point'];
   						$pergerakan[$key]['rotation'] = $data['rotation'];
   						$pergerakan[$key]['size'] = $data['size'];
   						$pergerakan[$key]['filepath'] = $data['filepath'];
   						$pergerakan[$key]['id_skenario'] = $data['id_skenario'];

   						array_push($id_pergerakan, $data['id_simbol_pergerakan']);
   						array_push($point, $data['point']);
   						array_push($rotation, $data['rotation']);
   						array_push($size, $data['size']);
   						$tanggal = $data['tanggal'];
   						$waktu = $data['waktu'];
   						$keterangan = $data['keterangan'];
   					}
   					$all = array(
   						'tanggal' => $tanggal,
   						'waktu' => $waktu,
   						'simbol_value'  => implode('|', $id_pergerakan),
   						'point'  => implode('|', $point),
   						'rotation'  => implode('|', $rotation),
   						'size'  => implode('|', $size),
   						'keterangan' => $keterangan
   					);
   					$form->populate($all);
   				}
   			}
   		}

   		if($dataDb != null)
   		{
   			$this->view->data = $pergerakan;
   		}

   		$this->view->form = $form;
   	}

   	public function pergerakanEditAction()
   	{
   		$this->_edit(null, 'pergerakan', 'Latihan_Model_Crud_KekuatanMusuh_Pergerakan',
   			array('skenario_id'=>$this->_skenario->getId())
   		);
   	}

   	public function pergerakanDelAction()
   	{
   		$this->_del(null, 'pergerakan', 'Latihan_Model_Crud_KekuatanMusuh_Pergerakan',
   			array('skenario_id'=>$this->_skenario->getId())
   		);
   	}
	
	public function simbolpergerakanAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $id = $this->_getParam('id');
        $model = new Peta_Model_DbTable_Intelijen();

        if($id != '')
        {
            $url = $model->simbolPergerakan($id);
        }

        echo json_encode($url['filepath']);
    }
}