<?php
/**
 * CRUD untuk User Login
 * @author Kanwil
 */
class Management_LoginController extends App_CrudController
{
	public function indexAction()
	{
		/*$table = new Zend_Db_Table('user.logins');
		$this->view->items = $table->fetchAll(); */

        $r = new Management_Model_DbTable_Login();
        $r_list = $r->getAllPlusRole();

        $this->view->items = $r_list;

        $this->_add(null, 'index', 'Management_Model_Crud_Login');
	}

	public function editAction()
	{
		$this->_edit(null, 'index', 'Management_Model_Crud_Login');
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Management_Model_Crud_Login');
	}
}