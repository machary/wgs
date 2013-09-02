<?php
class Cms_JarakPangkalanController extends App_Controller
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
		$modelGunCategory = new Cms_Model_JarakPangkalan();
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

	public function addjarakpelabuhanAction()
	{
		$request = $this->getRequest();
		$form = new Cms_Form_JarakPangkalan();
		$model = new Cms_Model_DbTable_JarakPangkalan();

		if($request->isPost())
		{
			if($request->isPost() AND $form->isValid($this->_request->getPost()))
			{
				$model->addJarak($form->getValues());
				$this->_redirect('cms/jarak.pangkalan');
			}
		}
		else
		{
			$this->errorMessage = 'Penyimpanan Gagal Dilakukan';
		}

		$this->view->form = $form;
	}

	public function editjarakpelabuhanAction()
	{
		$request = $this->getRequest();
		$id = $this->_getParam('id');

		$userForm = new Cms_Form_JarakPangkalan();
		$userModel = new Cms_Model_DbTable_JarakPangkalan();

		$result = $userModel->selectJarak($id);

		if($request->isPost() AND $userForm->isValid($this->_request->getPost())){
			$userModel->updateJarak($userForm->getValues(), $id);
			$this->_redirect('cms/jarak.pangkalan');
		}else{
			$userForm->populate($result);
		}

		$this->view->form = $userForm;
	}

	public function deletejarakpelabuhanAction()
	{
		$id = $this->getRequest()->getParam('id');

		$model = new Cms_Model_DbTable_JarakPangkalan();

		$model->deleteJarak($id);
		$this->_redirect('cms/jarak.pangkalan');
	}
}