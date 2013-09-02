<?php
class Cms_MissileController extends App_Controller
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

        //init Model Penyalur
        $modelGunCategory = new Cms_Model_Missile();
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
        $jsonString = $modelGunCategory->datatablesJSONApi($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view);

        echo  $jsonString;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Cms_Form_Missile_MissileForm();
        $missile = new Cms_Model_DbTable_Missile();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $missile->addMissile($form->getValues());
                $this->_redirect('cms/missile');
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

        $userForm = new Cms_Form_Missile_MissileForm();
        $userModel = new Cms_Model_DbTable_Missile();

        $result = $userModel->selectMissile($id);

        if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
            $userModel->updateMissile($userForm->getValues(), $id);
            $this->_redirect('cms/missile');
        }else{
            $userForm->populate($result);
        }

        $this->view->form = $userForm;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = new Cms_Model_DbTable_Missile();

        $model->deleteMissile($id);
        $this->_redirect('cms/missile');
    }
}