<?php 
/**
 * Rencana Operasi Gabungan
 *
 * @author Febi
 */
 
class Ops_RencanaOperasiGabWithMusuhController extends App_Controller
{
	// daftar kogas yg bisa dipilih RO-nya

    public function indexAction()
   	{
           $data = new Management_Model_DbTable_Team();
           $this->view->items = $data->getallteam();
   	}

    public function simulasiAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();


        $teamID = $this->_getParam('team_id');

        $model = new Ops_Model_Ro($teamID);

        if($identity->id_team == 0)
        {
            $this->getHelper('viewRenderer')->setViewScriptPathSpec('rencana-operasi-gab-with-musuh/warning.phtml');
            return false;
        }

        $kogas = array(
                        'kogasgabla' => 'kogasgablaData',
                        'kogasgabfib' => 'kogasgabfibData',
                        'kogasgablinud' => 'kogasgablinudData',
                        'pasrat' => 'pasratData',
                        'kogasgabrat' => 'kogasgabratData',
                        'kogasud' => 'kogasudData'
                 );

        if ($this->_request->isPost()) {
            $model->saveGabungan(json_decode($this->_request->getPost('json')));
            $this->view->successAlert = 'Tersimpan';
        }

        $team = new Management_Model_DbTable_Team();
        $teamDet = $team->gaetidteam($teamID);
        $musuh = $model->musuhData($teamDet['kode_skenario']);

        $dataKogas = $kogas;
        $startTime = 0;
        $endTime = 0;
        $status = false;
        foreach( $kogas as $kogasName => $kogasData)
        {
            $new = $dataKogas[$kogasName] = (object) $kogasName;
            $data = $model->$kogasData();

            if (!isset($data['cb'])) {
                $new->cb = null;
                $status = ($status == true) ? true : false;
            } else {
                if($data['cb']->get('waktu_mulai') AND $data['cb']->get('waktu_selesai'))
                {
                    $startTime = ($data['cb']->get('waktu_mulai') < $startTime) ? ceil($data['cb']->get('waktu_mulai')) : floor($startTime);
                    $endTime = ($data['cb']->get('waktu_selesai') > $endTime) ? floor($data['cb']->get('waktu_selesai')) : ceil($endTime);
                }
                $new->cb = $data['cb'];
                unset($data['cb']);
                $new->data = $data;
                $new->saved = $model->retrieve($kogasName);
                $new->savedGabungan = $model->retrieveGabungan($kogasName);
                $status = true;
            }
        }

        $this->view->status = $status;
        $this->view->musuh = $musuh;
        $this->view->dataKogas = $dataKogas;
        $this->view->startTime = $startTime;
        $this->view->endTime = $endTime;
	}

}