<?php
/**
 * CRUD untuk User Privilege
 * @author Kanwil
 */
class Management_RolePrivilegeController extends App_Controller
{
	// tampilkan semua privilege dan semua role
	public function indexAction()
	{
		$roleTable = new Zend_Db_Table('user.roles');
		$privTable = new Zend_Db_Table('user.privileges');
		$pivotTable = new Zend_Db_Table('user.roles_privileges');
		
		// penyimpanan
		if ($this->_request->isPost()) {
			$pivots = $this->_request->getParam('previlages');
			$roleId = $this->_request->getParam('id_role');

			// hapus data lama
			$pivotTable->delete("role_id = {$roleId}");
			// simpan data baru
			foreach ($pivots as $tt => $privId)
            {
				$pivotTable->insert(array(
					'role_id' => $roleId,
					'privilege_id' => $privId,
				));
			}
			$this->view->successAlert = 'Data Tersimpan';
			$this->_redirector->gotoSimple('index');
		}
		
		$rawPivot = $pivotTable->fetchAll();
		$pivots = array();
		foreach ($rawPivot as $r) {
			$pivots[$r->privilege_id][$r->role_id] = true;
		}

		if($this->isAjax()) {
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->getHelper('layout')->disableLayout();

			$rolId = intval( $this->_getParam( 'rolId' ) );

			$result = array();
			$i = 0;
			foreach($privTable->fetchAll($privTable->select()->order('name ASC')) as $p) {

				$result[$i]['id'] = $p->id;
				$result[$i]['name'] = $p->name;
				$result[$i]['actions'] = $p->actions;
				$result[$i]['controller'] = $p->controller;
				$result[$i]['module'] = $p->module;
				if (isset($pivots[$p->id][$rolId])) $result[$i]['checked'] = true;
				$i++;
			}
			echo json_encode($result);
		}

		$this->view->privileges = $privTable->fetchAll($privTable->select()->order('name ASC'));
		$this->view->roles = $roleTable->fetchAll($roleTable->select()->order('name ASC'));
		$this->view->pivots = $pivots;
	}

    public function otherRoleAction() {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $rolId = intval( $this->_getParam( 'rolId' ) );
        $roleTable = new Zend_Db_Table('user.roles');

        $result = $roleTable->fetchAll($roleTable->select()->where('id <> ?', $rolId)->order('name ASC'))->toArray();
        echo json_encode($result);
    }

    public function copyAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $sourceID = $this->_getParam('sourceid');
        $copyID = $this->_getParam('copyid');

        $pivotTable = new Zend_Db_Table('user.roles_privileges');

        $pivotTable->delete("role_id = {$sourceID}");
        $pivotData = $pivotTable->fetchAll("role_id = {$copyID}")->toArray();
        foreach($pivotData as $data) {
            $data['role_id'] = $sourceID;
            $pivotTable->insert($data);
        }
        $this->_redirector->gotoSimple('index');
    }
}