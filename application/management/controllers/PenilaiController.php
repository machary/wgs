<?php
class Management_PenilaiController extends App_Controller
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Penilai();
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

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Management_Form_Penilai_Penilai();
        $gun = new Management_Model_DbTable_Penilai();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $gun->addpenilai($form->getValues());
                $this->_redirect('management/penilai');
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

        $userForm = new Management_Form_Penilai_Penilai();
        $userModel = new Management_Model_DbTable_Penilai();

        $result = $userModel->selectpenilai($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $userModel->updatepenulis($userForm->getValues(), $id);
            $this->_redirect('management/penilai');
        }else{
            $userForm->populate($result);
        }

        $this->view->form = $userForm;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = new Management_Model_DbTable_Penilai();

        $model->deletepenilai($id);
        $this->_redirect('management/penilai');
    }
}