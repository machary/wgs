<?php
/**
 * CRUD untuk Role (authorization) yang ada
 * @author Kanwil
 */
 
class Management_RoleController extends App_CrudController
{
	public function indexAction()
	{

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //$modelGunCategory = new Cms_Model_DbTable_GunCategory();

        //init Model Penyalur
        $modelGunCategory = new Management_Model_Roles();
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
           $form = new Management_Form_Role_Role();
           $model = new Management_Model_DbTable_Role();

           if($request->isPost())
           {
               if($request->isPost() AND $form->isValid($this->_request->getPost()))
               {
                   $model->addRole($form->getValues());
                   $this->_redirect('management/role');
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

           $userForm = new Management_Form_Role_Role();
           $userModel = new Management_Model_DbTable_Role();

           $result = $userModel->selectRole($id);

           if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
               $userModel->updateRole($userForm->getValues(), $id);
               $this->_redirect('management/role');
           }else{
               $userForm->populate($result);
           }

           $this->view->form = $userForm;
   	}

   	public function deleteAction()
   	{
           $id = $this->getRequest()->getParam('id');

           $model = new Management_Model_DbTable_Role();

           $model->deleteRole($id);
           $this->_redirect('management/role');
   	}
}
