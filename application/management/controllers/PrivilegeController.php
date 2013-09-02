<?php
/**
 * CRUD untuk User Privilege
 * @author Kanwil
 */
class Management_PrivilegeController extends App_CrudController
{
	public function indexAction()
	{}

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $dt = new Management_Model_Privilege($this->_request->getParams());
        echo $dt->result();
    }

	public function addAction()
	{
		$this->_add(null, 'index', 'Management_Model_Crud_Privilege');
	}

	public function editAction()
	{
		$this->_edit(null, 'index', 'Management_Model_Crud_Privilege');
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Management_Model_Crud_Privilege');
	}
}