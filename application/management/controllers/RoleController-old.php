<?php
/**
 * CRUD untuk Role (authorization) yang ada
 * @author Kanwil
 */
 
class Management_RoleController extends App_CrudController
{
	public function indexAction()
	{
		$model = new Management_Model_Idname('roles');
		$this->view->items = $model->all();
	}

    public function addAction()
   	{
   		$this->_add(null, 'index', 'Management_Model_Crud_Roles');
   	}

   	public function editAction()
   	{
   		$this->_edit(null, 'index', 'Management_Model_Crud_Roles');
   	}

   	public function delAction()
   	{
   		$this->_del(null, 'index', 'Management_Model_Crud_Roles');
   	}
}
