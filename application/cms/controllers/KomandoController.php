<?php
/**
 * CRUD untuk Fasilitas Komando
 *
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_KomandoController extends App_CrudController
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
		$dt = new Cms_Model_Datatables_Komando($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_Komando');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_Komando');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_Komando');
	}

    public function viewAction()
    {
        $obj = new Cms_Model_Komando(null,$this->_request->getParam('id'));
        $this->view->obj = $obj;
    }

}