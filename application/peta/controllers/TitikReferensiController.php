<?php
class Peta_TitikReferensiController extends App_CrudController
{
    public function indexAction()
    {

    }

    public function operasiAction()
    {

    }

    public function logistikAction()
    {

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_TitikReferensi();
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
            $this->view, 1);

        echo  $jsonString;
    }

    public function dataapioperasiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_TitikReferensi();
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
            $this->view, 2);

        echo  $jsonString;
    }

    public function dataapilogistikAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_TitikReferensi();
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
            $this->view, 3);

        echo  $jsonString;
    }

    public function addintelAction()
    {
        $request = $this->getRequest();
        $form = new Peta_Form_Intelijen_TitikReferensi();
        $model = new Peta_Model_DbTable_TitikReferensi();
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $data = $form->getValues();
                $data['id_login'] = $identity->id;
                $model->addTitikReferensi($data, 1);
                $this->_redirect('peta/titik.referensi');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $this->view->form = $form;
    }

    public function addoperasiAction()
    {
        $request = $this->getRequest();
        $form = new Peta_Form_Operasional_TitikReferensi();
        $model = new Peta_Model_DbTable_TitikReferensi();
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $data = $form->getValues();
                $data['id_login'] = $identity->id;
                $model->addTitikReferensi($data, 2);
                $this->_redirect('peta/titik.referensi/operasi');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $this->view->form = $form;
    }

    public function addlogistikAction()
    {
        $request = $this->getRequest();
        $form = new Peta_Form_Logistik_TitikReferensi();
        $model = new Peta_Model_DbTable_TitikReferensi();
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $data = $form->getValues();
                $data['id_login'] = $identity->id;
                $model->addTitikReferensi($data, 3);
                $this->_redirect('peta/titik.referensi/logistik');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $id = $this->_getParam('id');
        $id_divisi = $this->_getParam('id_divisi');

        switch($id_divisi)
        {
            case 1:
                $userForm = new Peta_Form_Intelijen_TitikReferensi();
                break;
            case 2:
                $userForm = new Peta_Form_Operasional_TitikReferensi();
                break;
            case 3:
                $userForm = new Peta_Form_logistik_TitikReferensi();
                break;
        }

        $model = new Peta_Model_DbTable_TitikReferensi();

        $result = $model->selectTitikReferensi($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $model->updateTitikReferensi($userForm->getValues(), $id);
            $id_divisi = $userForm->getValue('id_divisi');
            switch($id_divisi)
            {
                case 1:
                    $this->_redirect('peta/titik.referensi');
                    break;
                case 2:
                    $this->_redirect('peta/titik.referensi/operasi');
                    break;
                case 3:
                    $this->_redirect('peta/titik.referensi/logistik');
                    break;
            }
        }else{
            $userForm->populate($result);
        }

        $this->view->form = $userForm;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $id_divisi = $this->getRequest()->getParam('id_divisi');

        $model = new Peta_Model_DbTable_TitikReferensi();

        $model->deleteTitikReferensi($id);

        switch($id_divisi)
        {
            case 1:
                $this->_redirect('peta/titik.referensi');
                break;
            case 2:
                $this->_redirect('peta/titik.referensi/operasi');
                break;
            case 3:
                $this->_redirect('peta/titik.referensi/logistik');
                break;
        }
    }
}