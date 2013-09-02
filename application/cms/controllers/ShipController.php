<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

//class Cms_ShipController extends Zend_Controller_Action
class Cms_ShipController extends App_CrudController
{
	public function init()
	{
		parent::init();
	}

	//list row
	public function indexAction()
	{
	}

	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		/**/
		$dt = new Cms_Model_Datatables_Ship($this->_request->getParams());
		echo $dt->result();
		/**/
	}

	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_ShipCrud');
	}

	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_ShipCrud');
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_ShipCrud');
	}

	public function viewAction()
	{
		$obj = new Cms_Model_ShipCrud(null,$this->_request->getParam('id'));
		$this->view->obj = $obj;
	}

}