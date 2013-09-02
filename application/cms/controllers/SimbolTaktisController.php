<?php
/**
 * CMS Simbol Taktis
 * @author Kanwil
 */
 
class Cms_SimbolTaktisController extends App_CrudController
{
	// list
	public function indexAction()
	{
	}
	
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$dt = new Cms_Model_Datatables_SimbolTaktis($this->_request->getParams());
		echo $dt->result();
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_SimbolTaktis');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_SimbolTaktis');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_SimbolTaktis');
	}
}