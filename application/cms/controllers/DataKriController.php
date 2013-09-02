<?php
/**
 * Data Kri Controller
 *
 * Data Pokok KRI
 * 
 * @author Febi
 */
 
class Cms_DataKriController extends App_CrudController
{

	public function init()
	{
		parent::init();
	}
	// pilihan menu Daftar pokok KRI
	public function indexAction()
	{
		if($this->isAjax())
		{
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$dt = new Cms_Model_Datatables_DataKri($this->_request->getParams());
			echo $dt->result();
		}
	}

	public function detailAction()
	{
		$id = $this->_request->getParam('id');
		$kri = new Cms_Model_DbTable_DataKri();
		$detailMotor = new Cms_Model_DbTable_DataKriMotor();
		$detailListrik = new Cms_Model_DbTable_DataKriListrik();

		$this->view->datakri = $kri->fetchRow("id_kapal = {$id}");
		$this->view->datakrimotor = $detailMotor->fetchAll("id_kapal = {$id}");
		$this->view->datakrilistrik = $detailListrik->fetchAll("id_kapal = {$id}");
	}

    //@auhtor : tajhul.faijin@sangkuriang.co.id
    public function postDispatch(){
        $this->view->id = $this->_request->getParam('id', null);
    }

    public function addAction(){
        $this->_add(null, 'index', 'Cms_Model_DataKriCrud', $opt = array(), $dest = './upload/images/kri/');
    }

    public function editAction(){
        $this->_edit(null, 'index', 'Cms_Model_DataKriCrud', $opt = array(), $dest = './upload/images/kri/');
    }

    public function delAction()
   	{
   		$this->_del(null, 'index', 'Cms_Model_DataKriCrud');
   	}

    //add detail motor listrik kapal
    public function addListrikAction(){
        $this->_add(null, 'detail', 'Cms_Model_DataKriDetailListrikCrud', $opt = array('id' => $this->_request->getParam('id')));
    }
    //add detail motor kapal
    public function addMotorAction(){
        $this->_add(null, 'detail', 'Cms_Model_DataKriDetailMotorCrud', $opt = array('id' => $this->_request->getParam('id')));
    }
}