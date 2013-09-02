<?php
class Latihan_ReportController extends App_Controller
{
    public function indexAction()
    {
        $model = new Latihan_Model_DbTable_Report();
        $modLog = new Management_Model_Crud_Login();

        $data = $model->getreport();

        $active = array();
		$team = array();
        foreach($data as $dat)
        {
            $act = $modLog->getisactive($dat['id_team']);
            if($act['is_active'] != null)
            {
                array_push($active, array('is_active' => $act['is_active'], 'id_team' => $act['id_team']));
            }
            else
            {
                array_push($active, null);
            }
			$team[$dat['id_team']] = $dat['team_name'];
        }

        $this->view->active = $active;
        $this->view->data = $data;
        $this->view->team = $team;
    }

    public function editAction()
    {
        $id = $this->_getParam('id');

        $model = new Latihan_Model_DbTable_Report();

        $data = $model->getidreport($id);

        $this->view->data = $data;
    }

    public function updateAction()
    {
        $value = $this->_request->getPost();

        $model = new Latihan_Model_DbTable_Report();

        $model->updatereport($value['id'], $value[$value['id_team'].'_'.$value['id_ps']]);

        $this->_redirect('latihan/report/index');
    }

    public function viewallAction()
    {
        $model = new Latihan_Model_DbTable_Report();
        $data = $model->getreport();
        $ps = $model->getPS();

        $this->view->ps = $ps;
        $this->view->data = $data;
    }
}