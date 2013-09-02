<?php
/*
 * @auhtor : Febi Fajar S
 * */
class Ops_SituasiController extends App_CrudController
{
    private $_identity = null;
    public function init()
    {
        parent::init();
        $this->_identity = Zend_Auth::getInstance()->getStorage()->read();
    }

    public function indexAction()
    {
    }

    public function adddaratAction()
    {
        $request = $this->getRequest();
        $form = new Ops_Form_Situasi_DaratForm();
        $model = new Ops_Model_DbTable_Situasi();
	    $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
				$idTaktisArr = array();
	            $jumKekuatanArr = array();
	            $keteranganArr = array();
	            foreach($_POST['detail'] as $detail)
	            {
		            array_push($idTaktisArr, $detail['taktis_id']);
		            array_push($jumKekuatanArr, $detail['jumlah']);
		            array_push($keteranganArr, $detail['keterangan']);
	            }

	            $idTaktis = implode("|", $idTaktisArr);
	            $jumKekuatan = implode("|", $jumKekuatanArr);
	            $identity = Zend_Auth::getInstance()->getStorage()->read();
	            $user = $model->getSkenAndLog($identity->id);
	            $model->insertmatra($_POST, $idTaktis, $jumKekuatan, 'Darat', $user, $keteranganArr);
	            $this->_redirect('ops/situasi');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

	    $this->view->jenis = $simTaktis->jenisSimbol();
	    $this->view->simTaktis = $simTaktis;
	    $this->view->form = $form;
    }

    public function addlautAction()
    {
	    $request = $this->getRequest();
	    $form = new Ops_Form_Situasi_LautForm();
	    $model = new Ops_Model_DbTable_Situasi();
	    $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
	            $idTaktisArr = array();
	            $jumKekuatanArr = array();
	            $keteranganArr = array();
	            foreach($_POST['detail'] as $detail)
	            {
		            array_push($idTaktisArr, $detail['taktis_id']);
		            array_push($jumKekuatanArr, $detail['jumlah']);
		            array_push($keteranganArr, $detail['keterangan']);
	            }

	            $idTaktis = implode("|", $idTaktisArr);
	            $jumKekuatan = implode("|", $jumKekuatanArr);
	            $identity = Zend_Auth::getInstance()->getStorage()->read();
	            $user = $model->getSkenAndLog($identity->id);
	            $model->insertmatra($_POST, $idTaktis, $jumKekuatan, 'Laut', $user, $keteranganArr);
	            $this->_redirect('ops/situasi');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

	    $this->view->jenis = $simTaktis->jenisSimbol();
	    $this->view->simTaktis = $simTaktis;
	    $this->view->form = $form;
    }

    public function addudaraAction()
    {
	    $request = $this->getRequest();
	    $form = new Ops_Form_Situasi_UdaraForm();
	    $model = new Ops_Model_DbTable_Situasi();
	    $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
	            $idTaktisArr = array();
	            $jumKekuatanArr = array();
	            $keteranganArr = array();
	            foreach($_POST['detail'] as $detail)
	            {
		            array_push($idTaktisArr, $detail['taktis_id']);
		            array_push($jumKekuatanArr, $detail['jumlah']);
		            array_push($keteranganArr, $detail['keterangan']);
	            }

	            $idTaktis = implode("|", $idTaktisArr);
	            $jumKekuatan = implode("|", $jumKekuatanArr);
	            $identity = Zend_Auth::getInstance()->getStorage()->read();
	            $user = $model->getSkenAndLog($identity->id);
	            $model->insertmatra($_POST, $idTaktis, $jumKekuatan, 'Udara', $user, $keteranganArr);
	            $this->_redirect('ops/situasi');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

	    $this->view->jenis = $simTaktis->jenisSimbol();
	    $this->view->simTaktis = $simTaktis;
	    $this->view->form = $form;
    }

    public function cogAction()
    {}

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Ops_Model_Situasi();
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

    public function polygonAction()
    {
        if( $this->_request->isXmlHttpRequest() ){
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();

            $layer_id = $_POST['layer_id'];
            $model = new Ops_Model_DbTable_Situasi();

            switch($layer_id)
            {
                case 1:
                    $layer_name = 'Udara';
                    break;
                case 2:
                    $layer_name = 'Laut';
                    break;
                case 3:
                    $layer_name = 'Darat';
                    break;
            }
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $user = $model->getSkenAndLog($identity->id);
            $lonlat = $model->getPolygon($layer_name, $user);

            //print_r($lonlat);

            $geomArr = array();
            if( !empty($lonlat) ){
                foreach( $lonlat as $data )
                {
                    array_push($geomArr, explode('|', $data['geom']));
                }

                $x=0;
                $poly = array();
                $count = count($geomArr)-1;
                for($y=0;$y<=$count;$y++)
                {
                    $counter = count($geomArr[$x])-1;
                    for($z=0;$z<=$counter;$z++)
                    {
                        if(!empty($geomArr[$y][$z])) $poly[$z]['geom'] = $geomArr[$y][$z];
                    }
                }
            }

            echo json_encode($poly);
        }
    }

    public function markersAction()
    {
        if( $this->_request->isXmlHttpRequest() ){
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();

            $layer_id = $_POST['layer_id'];
            $model = new Ops_Model_DbTable_Situasi();

            switch($layer_id)
            {
                case 1:
                    $layer_name = 'Udara';
                    break;
                case 2:
                    $layer_name = 'Laut';
                    break;
                case 3:
                    $layer_name = 'Darat';
                    break;
            }

            $lonlat = $model->getLonlat($layer_name);

            $markers = array();


            if( !empty($lonlat) ){
                foreach( $lonlat as $key=>$data )
                {
                    $filepathArr = array();
                    $markers[$key]['negara'] = $data['negara'];
                    $markers[$key]['jumlah_kekuatan'] = $data['jumlah_kekuatan'];
	                $idSimTakExp = explode('|', $data['id_simbol_taktis']);
	                $countIdSimTakExp = count($idSimTakExp) - 1;
	                for($x=0;$x<=$countIdSimTakExp;$x++)
	                {
		                $dataSimTak = $model->getSimbolTaktis($idSimTakExp[$x]);
		                array_push($filepathArr, $dataSimTak['filepath']);
	                }
                    $markers[$key]['point'] = $data['point'];
                    $markers[$key]['filepath'] = implode('|', $filepathArr);
                    $markers[$key]['keterangan'] = $data['keterangan'];
                }
            }

            echo json_encode($markers);
        }
    }

    public function addcogAction()
    {
        $id_intelijen = explode(',', $this->_getParam( 'id_intelijen' ));
        $count = count($id_intelijen);
        $status = explode(',', $this->_getParam('status'));

        $model = new Ops_Model_DbTable_Situasi();

        for($x = 1;$x <= $count; $x++)
        {
            $model->updatecog($id_intelijen[$x-1], $status[$x]);
        }
        $this->_redirect('ops/situasi');
    }

    public function editinformasiAction()
    {
        $request = $this->getRequest();
        $id = $this->_getParam('id');

        $userForm = new Ops_Form_Situasi_Editcog();
        $userModel = new Ops_Model_DbTable_Situasi();

        $result = $userModel->selectCOG($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $userModel->updateKet($userForm->getValues(), $id);
            $this->_redirect('ops/situasi/cog');
        }else{
            if($result != null)
            {
                $userForm->populate($result);
            }
        }

        $this->view->form = $userForm;
    }

    public function editAction()
    {}

    public function dataapieditAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Ops_Model_Situasi();
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

        $registry = Zend_Auth::getInstance()->getStorage()->read();

        //get rows
        $jsonString = $model->datatableseditJSONApi($registry->id, $areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view);

        echo  $jsonString;
    }

    public function editsituasiAction()
    {
        $request = $this->getRequest();
        $id = $this->_getParam('id');
        $matra = $this->_getParam('matra');

        if($matra == 'Laut')
        {
            $form = new Ops_Form_Situasi_LautForm();
        }
        else if($matra == 'Udara')
        {
            $form = new Ops_Form_Situasi_UdaraForm();
        }
        else if($matra == 'Darat')
        {
            $form = new Ops_Form_Situasi_DaratForm();
        }

        $model = new Ops_Model_DbTable_Situasi();
        $result = $model->getSituasi($id);
	    $simTaktis = new Latihan_Model_DbTable_Simboltaktis();

        if($request->isPost() AND $form->isValid($this->_request->getPost())){
	        $idTaktisArr = array();
	        $jumKekuatanArr = array();
	        $keteranganArr = array();
	        foreach($_POST['detail'] as $detail)
	        {
		        array_push($idTaktisArr, $detail['taktis_id']);
		        array_push($jumKekuatanArr, $detail['jumlah']);
		        array_push($keteranganArr, $detail['keterangan']);
	        }

	        $idTaktis = implode("|", $idTaktisArr);
	        $jumKekuatan = implode("|", $jumKekuatanArr);
	        $identity = Zend_Auth::getInstance()->getStorage()->read();
	        $user = $model->getSkenAndLog($identity->id);
	        $model->updateSituasi($_POST, $idTaktis, $jumKekuatan, $id, $matra, $keteranganArr);
	        $this->_redirect('ops/situasi');


            //$this->_redirect('ops/situasi');
        }else{
            if( !empty($result) ){
                $expSimTak = explode('|', $result['id_simbol_taktis']);
	            $expJmlKekuatan = explode('|', $result['jumlah_kekuatan']);
	            $expKeterangan = explode('|', $result['keterangan']);

	            $count = count($expSimTak) - 1;

	            $kekuatanArr = array();
	            $ketArr = array();
	            $jenisArr = array();
	            $unsurArr = array();
	            $filepathArr = array();
	            for($g=0;$g<=$count;$g++)
	            {
		            $datSimTak = $model->getSimbolTaktis($expSimTak[$g]);

		            array_push($jenisArr, $datSimTak['jenis']);
		            array_push($unsurArr, $datSimTak['id']);
		            array_push($kekuatanArr, $expJmlKekuatan[$g]);
		            array_push($ketArr, $expKeterangan[$g]);
		            array_push($filepathArr, $datSimTak['filepath']);
	            }

	            $dataSimTak['jenis'] = $jenisArr;
	            $dataSimTak['unsur'] = $unsurArr;
	            $dataSimTak['kekuatan'] = $kekuatanArr;
	            $dataSimTak['keterangan'] = $ketArr;
	            $dataSimTak['filepath'] = $filepathArr;

//                echo '<pre>';
//                print_r($dataSimTak);
//                echo '</pre>';
//                exit;

	            $this->view->dataSimTak = $dataSimTak;

            }
	        $lonlat = explode(', ', $result['point']);
	        $result['longitude'] = $lonlat[0];
	        $result['latitude'] = $lonlat[1];

            $form->populate($result);
        }

	    $this->view->jenis = $simTaktis->jenisSimbol();
	    $this->view->simTaktis = $simTaktis;
        $this->view->form = $form;
        $this->view->matra = $matra;
    }

    public function deletesituasiAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = new Ops_Model_DbTable_Situasi();

        $model->deletesituasi($id);
        $this->_redirect('ops/situasi/edit');
    }

    public function pergerakanAction()
    {
        $request = $this->getRequest();
        $form = new Ops_Form_Situasi_Pergerakan();
        $model = new Ops_Model_DbTable_Situasi();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                parent::disableViewAndLayout();
                $valueForm = $form->getValues();
                parent::printArray( $valueForm );
                $idSimTak = explode('|', $valueForm['simbol_value']);
                $point = explode('|', $valueForm['lonlat']);
                $rotate = explode('|', $valueForm['rotation']);
                $size = explode('|', $valueForm['size']);
                $count = count($idSimTak)-1;

                for($x=0;$x<=$count;$x++)
                {
                    $model->saveSimbolPergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $this->_identity->id_team);
                }
                $this->_redirect('ops/situasi');
            }
        }

        $this->view->form = $form;
    }

    public function simbolpergerakanAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $id = $this->_getParam('id');
        $model = new Ops_Model_DbTable_Situasi();

        if($id != '')
        {
            $url = $model->simbolPergerakan($id);
        }

        echo json_encode($url['filepath']);
    }

    public function movementAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Ops_Model_DbTable_Situasi();

        $data = $model->getPergerakan( $this->_identity->id_team );

        $simbol = array();
        if( !empty($data) ){
            foreach( $data as $key=>$data )
            {
                $simbol[$key]['id_simbol_pergerakan'] = $data['id_simbol_pergerakan'];
                $simbol[$key]['point'] = $data['point'];
                $simbol[$key]['rotation'] = $data['rotation'];
                $simbol[$key]['size'] = $data['size'];
                $simbol[$key]['filepath'] = $data['filepath'];
            }
        }

        echo json_encode($simbol);
    }

    public function editpergerakanAction()
    {
        $request = $this->getRequest();
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Ops_Model_DbTable_Situasi();
        $form = new Ops_Form_Situasi_Pergerakan();
        $dataDb = $model->editpergerakan($identity->id_team);

        $data = $model->editpergerakan($identity->id_team);

        if($request->isPost() AND $form->isValid($this->_request->getPost()))
        {
            $dataDb = $model->editpergerakan($identity->id_team);
            $countId = count($dataDb);
            $simbolValue = explode('|',$form->getValue('simbol_value'));
            $editCount = count($simbolValue);

            $valueForm = $form->getValues();
            $idSimTak = explode('|', $valueForm['simbol_value']);
            $point = explode('|', $valueForm['lonlat']);
            $rotate = explode('|', $valueForm['rotation']);
            $size = explode('|', $valueForm['size']);

            if($countId == null) //baru
            {
                $count = count($idSimTak)-1;
                for($x=0;$x<=$count;$x++)
                {
                    $model->saveSimbolPergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $identity->id_team);

                }
                $this->_redirect('ops/situasi');
            }
            elseif($editCount == $countId) //update
            {
                $x=0;
                foreach($dataDb as $dat)
                {
                    $model->updatepergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $dat['id']);
                    $x++;
                }
                $this->_redirect('ops/situasi');
            }
            elseif($editCount != $countId) //delete
            {
	            $model->deletepergerakan($identity->id_team);

	            $count = count($idSimTak)-1;
	            for($x=0;$x<=$count;$x++)
	            {
		            $model->saveSimbolPergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $identity->id_team);
	            }
                $this->_redirect('ops/situasi');
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
                        $pergerakan[$key]['id_team'] = $data['id_team'];

                        array_push($id_pergerakan, $data['id_simbol_pergerakan']);
                        array_push($point, $data['point']);
                        array_push($rotation, $data['rotation']);
                        array_push($size, $data['size']);
                    }
                    $all = array(
                        'simbol_value'  => implode('|', $id_pergerakan),
                        'lonlat'  => implode('|', $point),
                        'rotation'  => implode('|', $rotation),
                        'size'  => implode('|', $size)
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

/*    public function editpergerakanAction()
    {
        $request = $this->getRequest();
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Ops_Model_DbTable_Situasi();
        $form = new Ops_Form_Situasi_Pergerakan();

        $data = $model->editpergerakan($identity->id_team);

        if($request->isPost())
        {
            parent::disableViewAndLayout();
            $dataDb = $model->editpergerakan($identity->id_team);
            $countId = count($dataDb);
            $simbolValue = explode('|',$form->getValue('simbol_value'));
            $editCount = count($simbolValue);

            $valueForm = $_POST;

            $idSimTak = explode('|', $valueForm['simbol_value']);
            $point = explode('|', $valueForm['lonlat']);
            $rotate = explode('|', $valueForm['rotation']);
            $size = explode('|', $valueForm['size']);

            if($editCount == $countId)
            {
                $x=0;
                foreach($dataDb as $dat)
                {
                    $model->updatepergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $dat['id']);
                    $x++;
                }
                $this->_redirect('ops/situasi');
            }
            elseif($editCount < $countId)
            {
                $x=0;
                foreach($dataDb as $dat)
                {
                    if(count($idSimTak) == 0)
                    {
                        $model->deletepergerakan($dat['id']);
                    }
                    else
                    {
                        $model->updatepergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $dat['id']);
                        $x++;
                    }
                }
                $this->_redirect('ops/situasi');
            }
            elseif($editCount > $countId)
            {
                $x=0;
                foreach($dataDb as $dat)
                {
                    $model->updatepergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $dat['id']);
                    unset($idSimTak[$x], $point[$x], $rotate[$x], $size[$x]);
                    $x++;
                }
                $countIdSimTak = count($idSimTak)-1;
                for($x;$x<=key($idSimTak);$x++)
                {
                    $model->saveSimbolPergerakan($idSimTak[$x], $point[$x], $rotate[$x], $size[$x], $identity->id_team);
                }
                $this->_redirect('ops/situasi');
            }
        }
        else
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
                    $pergerakan[$key]['id_team'] = $data['id_team'];

                    array_push($id_pergerakan, $data['id_simbol_pergerakan']);
                    array_push($point, $data['point']);
                    array_push($rotation, $data['rotation']);
                    array_push($size, $data['size']);
                }
                $all = array(
                    'simbol_value'  => implode('|', $id_pergerakan),
                    'lonlat'  => implode('|', $point),
                    'rotation'  => implode('|', $rotation),
                    'size'  => implode('|', $size)
                );

                $form->populate($all);
            }
        }

        $form->getElement('simbol_pergerakan')->setRequired(false);

        $form->setLegend('Edit Data Pergerakan');
        $this->view->data = $pergerakan;
        $this->view->form = $form;
    }*/

    /*
     * <untuk mendapatkan data marker intelijen>
     * @author : tajhul.faijin
     * */
    public function markerIntelAction()
    {
        parent::disableViewAndLayout();
        $matra = $this->_request->getParam('matra', null);

        $model = new Ops_Model_DbTable_Situasi();
        $lonlat = $model->getLonlat( ucfirst($matra) );

        $markers = array();
        if( !empty($lonlat) ){
            foreach( $lonlat as $key=>$data )
            {
                $markers[$key]['matra'] = strtolower($data['matra']);
                $markers[$key]['negara'] = $data['negara'];
                $markers[$key]['jumlah_kekuatan'] = $data['jumlah_kekuatan'];
                $markers[$key]['point'] = $data['point'];
                $markers[$key]['filepath'] = $data['filepath'];
            }
        }
        echo json_encode($markers);
    }
}