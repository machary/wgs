<?php
class Cms_TorpedoController extends App_Controller
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

        //$modelGunCategory = new Cms_Model_DbTable_GunCategory();

        //init Model Penyalur
        $modelTorpedoCategory = new Cms_Model_Torpedo();
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
        $jsonString = $modelTorpedoCategory->datatablesJSONApi($areaLevel, $areaID,
                                                                $sEcho, $limit, $offset,
                                                                $sortColumn, $order,
                                                                $filter, $sSearch,
                                                                $this->view);

        echo  $jsonString;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Cms_Form_Torpedo_TorpedoForm();
        $gun = new Cms_Model_DbTable_Torpedo();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $gun->addGun($form->getValues());
                $this->_redirect('cms/torpedo');
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

        $userForm = new Cms_Form_Torpedo_EditTorpedoForm();
        $userModel = new Cms_Model_DbTable_Torpedo();

        $result = $userModel->selectTorpedo($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $userModel->updateTorpedo($userForm->getValues(), $id);
            $this->_redirect('cms/torpedo');
        }else{
            $userForm->populate($result);
        }

        $this->view->form = $userForm;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = new Cms_Model_DbTable_Torpedo();

        $model->deleteTorpedo($id);
        $this->_redirect('cms/torpedo');
    }
}