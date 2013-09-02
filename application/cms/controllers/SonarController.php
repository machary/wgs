<?php
/**
 * Sonar Controller
 * 
 * Sonar dan Sonar Category
 * 
 * @author Kanwil
 */
 
class Cms_SonarController extends App_Controller
{
	// pilihan menu 
	public function indexAction()
	{
		
	}
	
	public function listSonarAction() 
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiSonarAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		$dt = new Cms_Model_Datatables_Sonar($this->_request->getParams());
		echo $dt->result();
	}
	
	public function addSonarAction()
	{
		/*
		$crud = new Cms_Model_Crud('master.M_SONAR');
		$form = $crud->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$crud->setFromForm($form);
				$crud->save();
				$this->_redirector->gotoSimple('list.sonar');
			}
		}
		$this->view->form = $form;
		return;// testing CRUD
		*/
		$form = new Cms_Form_Sonar();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$sonar = new Cms_Model_Sonar();
				$sonar->setFromForm($form);
				$sonar->save();
				$this->_redirector->gotoSimple('list.sonar');
			}
		}
		$this->view->form = $form;
	}
	
	public function editSonarAction()
	{
		/*
		$crud = new Cms_Model_Crud('master.M_SONAR', $this->_request->getParam('id'));
		if ($crud->exists()) {
			$form = $crud->form();
			$form->setDefaults($crud->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$crud->setFromForm($form);
					$crud->save();
					$this->_redirector->gotoSimple('list.sonar');
				}
			}
			$this->view->sonar = $crud;
			$this->view->form = $form;
		}
		return;
		*/
		$sonar = new Cms_Model_Sonar($this->_request->getParam('id'));
		if ($sonar->exists()) {
			$form = new Cms_Form_Sonar();
			$form->setDefaults($sonar->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$sonar->setFromForm($form);
					$sonar->save();
					$this->_redirector->gotoSimple('list.sonar');
				}
			}
			$this->view->sonar = $sonar;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple('list.sonar');
		}
	}
	
	public function delSonarAction()
	{
		$sonar = new Cms_Model_Sonar($this->_request->getParam('id'));
		if ($sonar->exists()) {
			$sonar->delete();
		}
		$this->_redirector->gotoSimple('list.sonar');
	}
	
	public function listCategoryAction()
	{

	}

    public function dataapicategoryAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //$modelGunCategory = new Cms_Model_DbTable_GunCategory();

        //init Model Penyalur
        $model = new Cms_Model_Datatables_SonarCategory();
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
	
	public function addCategoryAction()
	{
		$cat = new Cms_Model_Idname('M_SONAR_CATEGORY');
		$form = $cat->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$cat->setFromForm($form);
				$cat->save();
				$this->_redirector->gotoSimple('list.category');
			}
		}
		$this->view->form = $form;
	}
	
	public function editCategoryAction()
	{
		$cat = new Cms_Model_Idname('M_SONAR_CATEGORY', $this->_request->getParam('id'));
		if ($cat->exists()) {
			$form = $cat->form();
			$form->setDefaults($cat->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$cat->setFromForm($form);
					$cat->save();
					$this->_redirector->gotoSimple('list.category');
				}
			}
			$this->view->cat = $cat;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple('list.category');
		}
	}
	
	public function delCategoryAction()
	{
		$cat = new Cms_Model_Idname('M_SONAR_CATEGORY', $this->_request->getParam('id'));
		if ($cat->exists()) {
			$cat->delete();
		}
		$this->_redirector->gotoSimple('list.category');
	}
}