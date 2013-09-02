<?php 
/**
 * Rencana Operasi Gabungan
 *
 * @author Febi
 */
 
class Ops_RencanaOperasiGabunganController extends App_Controller
{
	// daftar kogas yg bisa dipilih RO-nya

	public function indexAction()
	{
//        $this->getHelper('viewRenderer')->setViewScriptPathSpec('rencana-operasi/kogas.phtml');

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Ops_Model_Ro($identity->id_team);

        if($identity->id_team == 0)
        {
            $this->getHelper('viewRenderer')->setViewScriptPathSpec('rencana-operasi-gabungan/warning.phtml');
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
        $this->view->dataKogas = $dataKogas;
        $this->view->startTime = $startTime;
        $this->view->endTime = $endTime;
	}

}