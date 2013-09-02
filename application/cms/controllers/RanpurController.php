<?php
/**
 * CRUD untuk Ranpur
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_RanpurController extends App_CrudController
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
		$dt = new Cms_Model_Datatables_Ranpur($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_Ranpur');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_Ranpur');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_Ranpur');
	}

    public function viewAction()
    {
        $obj = new Cms_Model_Ranpur(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }

}