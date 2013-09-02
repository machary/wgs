<?php 
/**
 * Rencana Operasi
 *
 * 
 *
 * @author Kanwil
 */
 
class Ops_RencanaOperasiDenganMusuhController extends App_Controller
{
	// daftar kogas yg bisa dipilih RO-nya
	public function indexAction()
	{
        $data = new Management_Model_DbTable_Team();
        $this->view->items = $data->getallteam();

	}

    public function simulasiAction()
	{
        $this->view->teamID = $this->_getParam('team_id');

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if(isset($identity->kogas))
        {
            $this->_redirector->gotoSimple($identity->kogas, 'rencana.operasi.dengan.musuh', 'ops', array('team_id' => $this->_getParam('team_id')));
        }

//        /rencana.operasi.dengan.musuh/kogasgabla/team_id/22

	}

	private function _kogasAction($method)
	{
        $this->getHelper('viewRenderer')->setViewScriptPathSpec('rencana-operasi-dengan-musuh/kogas.phtml');


        $teamID = $this->_getParam('team_id');

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Ops_Model_Ro($teamID);
        $kogas = $this->_request->getActionName();

        if ($this->_request->isPost()) {
            $model->save($kogas, json_decode($this->_request->getPost('json')));
            $this->view->successAlert = 'Tersimpan';
        }

        $data = $model->$method();

        $team = new Management_Model_DbTable_Team();
        $teamDet = $team->gaetidteam($teamID);
        $musuh = $model->musuhData($teamDet['kode_skenario']);

        if (!isset($data['cb'])) {
            $this->view->cb = null;
        } else {
            $this->view->cb = $data['cb'];
            unset($data['cb']);
            $this->view->data = $data;
            $this->view->musuh = $musuh;
            $this->view->saved = $model->retrieve($kogas);
        }
	}
	
	public function kogasgablaAction()
	{
		$this->_kogasAction('kogasgablaData');
	}
	
	public function kogasgabfibAction()
	{
		$this->_kogasAction('kogasgabfibData');
	}
	
	public function kogasgablinudAction()
	{
		$this->_kogasAction('kogasgablinudData');
	}
	
	public function kogasgabratAction()
	{
		$this->_kogasAction('kogasgabratData');
	}
	
	public function kogasudAction()
	{
		$this->_kogasAction('kogasudData');
	}
}