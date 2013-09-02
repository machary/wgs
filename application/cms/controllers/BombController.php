<?php
/**
 * Bomb Controller
 * 
 * Bomb dan Bomb Type
 * 
 * @author Kanwil
 */
 
class Cms_BombController extends App_Controller
{
	// pilihan menu Daftar Bom dan Daftar Tipe Bom
	public function indexAction()
	{
		
	}
	
	public function listBombAction() 
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiBombAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		$dt = new Cms_Model_Datatables_Bomb($this->_request->getParams());
		echo $dt->result();
	}
	
	public function addBombAction()
	{
		$form = new Cms_Form_Bomb();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$bomb = new Cms_Model_Bomb();
				$bomb->setFromForm($form);
				$bomb->save();
				$this->_redirector->gotoSimple('list.bomb');
			}
		}
		$this->view->form = $form;
	}
	
	public function editBombAction()
	{
		$bomb = new Cms_Model_Bomb($this->_request->getParam('id'));
		if ($bomb->exists()) {
			$form = new Cms_Form_Bomb();
			$form->setDefaults($bomb->toFormArray());
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					$bomb->setFromForm($form);
					$bomb->save();
					$this->_redirector->gotoSimple('list.bomb');
				}
			}
			$this->view->bomb = $bomb;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple('list.bomb');
		}
	}
	
	public function delBombAction()
	{
		$bomb = new Cms_Model_Bomb($this->_request->getParam('id'));
		if ($bomb->exists()) {
			$bomb->delete();
		}
		$this->_redirector->gotoSimple('list.bomb');
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
        $model = new Cms_Model_Datatables_BombType();
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
		$type = new Cms_Model_Idname('M_BOMB_TYPE');
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
		$type = new Cms_Model_Idname('M_BOMB_TYPE', $this->_request->getParam('id'));
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
		$type = new Cms_Model_Idname('M_BOMB_TYPE', $this->_request->getParam('id'));
		if ($type->exists()) {
			$type->delete();
		}
		$this->_redirector->gotoSimple('list.type');
	}
}