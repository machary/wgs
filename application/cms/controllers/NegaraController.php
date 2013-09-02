<?php
/**
 * CRUD untuk Data Negara
 *
 * @author Kanwil
 */
 
class Cms_NegaraController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		/**/
		$dt = new Cms_Model_Datatables_Negara($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add('master.M_COUNTRY', 'index');
	}
	
	public function editAction()
	{
		$this->_edit('master.M_COUNTRY', 'index');
	}
	
	public function delAction()
	{
		$this->_del('master.M_COUNTRY', 'index');
	}
	
	public function viewAction()
	{
		$this->view->item = new Cms_Model_Crud('master.M_COUNTRY', $this->_request->getParam('id'));
	}
}