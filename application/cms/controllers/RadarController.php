<?php
/**
 * Radar Controller
 * 
 * Radar dan Radar Type
 * 
 * @author Kanwil
 */
 
class Cms_RadarController extends App_Controller
{
	// pilihan menu 
	public function indexAction()
	{
		
	}
	
	public function listRadarAction() 
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiRadarAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		$dt = new Cms_Model_Datatables_Radar($this->_request->getParams());
		echo $dt->result();
	}
	
	public function addRadarAction()
	{
		$form = new Cms_Form_Radar();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$radar = new Cms_Model_Radar();
				$radar->setFromForm($form);
				$radar->save();
				$this->_redirector->gotoSimple('list.radar');
			}
		}
		$this->view->form = $form;
	}
	
	public function editRadarAction()
	{
		$radar = new Cms_Model_Radar($this->_request->getParam('id'));
		if ($radar->exists()) {
			$form = new Cms_Form_Radar();
			$form->setDefaults($radar->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$radar->setFromForm($form);
					$radar->save();
					$this->_redirector->gotoSimple('list.radar');
				}
			}
			$this->view->radar = $radar;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple('list.radar');
		}
	}
	
	public function delRadarAction()
	{
		$radar = new Cms_Model_Radar($this->_request->getParam('id'));
		if ($radar->exists()) {
			$radar->delete();
		}
		$this->_redirector->gotoSimple('list.radar');
	}
	
	public function listTypeAction()
	{

	}

    public function dataapitypeAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //$modelGunCategory = new Cms_Model_DbTable_GunCategory();

        //init Model Penyalur
        $model = new Cms_Model_Datatables_RadarType();
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
	
	public function addTypeAction()
	{
		$type = new Cms_Model_Idname('M_RADAR_TYPE');
		$form = $type->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$type->setFromForm($form);
				$type->save();
				$this->_redirector->gotoSimple('list.type');
			}
		}
		$this->view->form = $form;
	}
	
	public function editTypeAction()
	{
		$type = new Cms_Model_Idname('M_RADAR_TYPE', $this->_request->getParam('id'));
		if ($type->exists()) {
			$form = $type->form();
			$form->setDefaults($type->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$type->setFromForm($form);
					$type->save();
					$this->_redirector->gotoSimple('list.type');
				}
			}
			$this->view->type = $type;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple('list.type');
		}
	}
	
	public function delTypeAction()
	{
		$type = new Cms_Model_Idname('M_RADAR_TYPE', $this->_request->getParam('id'));
		if ($type->exists()) {
			$type->delete();
		}
		$this->_redirector->gotoSimple('list.type');
	}
}