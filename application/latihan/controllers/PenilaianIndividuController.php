<?php
/**
 * List of Kogas for nilai
 *
 * @author Febi
 */

class Latihan_PenilaianIndividuController extends App_Controller
{
	public function indexAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $modelTeam = new Zend_Db_Table('user.Team');

        $jabatan = $modelPenilai->selectAllJabTeamwithlogin($identity->id);
        $allTeam = $modelTeam->fetchAll($modelTeam->select())->toArray();

        if(count($jabatan) > 1) {
            $kogasData = array();
            foreach($allTeam as $team)
            {
                foreach($jabatan as $jab)
                {
                    if($team['id'] == $jab['id_team'])
                    {
                        $kogasData[$team['id']]['team_name'] = $team['team_name'];
                        $kogasData[$team['id']]['kogas'][$jab['id_jabatan']] = $jab['nama_jabatan'];
                    }
                }
            }
            $this->view->kogas = $kogasData;
        } else {
            $this->_redirector->gotoSimple('penilaian', null, null, array( 'team_id' => $jabatan[0]['id_team'], 'kogas' => $jabatan[0]['id_jabatan']));
        }
	}

    public function penilaianAction()
    {
        $kogas = $this->_request->getParam('kogas');
        $team = $this->_request->getParam('team_id');

        $model = new Latihan_Model_Crud_PenilaianIndividu();
        $modelJabatan = new Cms_Model_DbTable_Jabatan();
        $data = $this->_request->getPost();

        if ($this->_request->isPost()) {

            $index = $data['item_index'];
            unset($data['item_index']);
            for($i=1;$i<=$index;$i++){
                $model->save( $data['id'.$i], array( 'login_id' => $data['id'.$i], 'nilai' => $data['nilai'.$i], 'keterangan' => $data['keterangan'.$i]));
            }
            $this->_redirector->gotoSimple('penilaian', null, null, array('team_id' => $team, 'kogas' => $kogas));
        }

        $this->view->pelaku = $model->getKogas($kogas, $team);
        $this->view->kogas = ucfirst($modelJabatan->getNama($kogas));
    }

	public function printAction()
	{
        $this->_helper->_layout->setLayout('print-layout');

        $kogasID = $this->_request->getParam('kogas');
        $team = $this->_request->getParam('team_id');

        $modelJabatan = new Cms_Model_DbTable_Jabatan();
        $kogasName = $modelJabatan->getNama($kogasID);

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $modelUser = new Management_Model_DbTable_Login();
        $skenario = $modelPenilai->getScenarioWithIdKogas( $identity->id, $kogasID);

        $model = new Latihan_Model_Crud_PenilaianIndividu();

        $pelaku = array();
        $i = 0;
        $j = 1;
        foreach( $model->getKogas($kogasID, $team) as $pel){
            $pelaku[$i][] = $pel;
            if($j % 23 == 0) $i++;
            $j++;
        }

        $this->view->pelaku = $pelaku;
        $this->view->kogas = ucfirst($kogasName);
        $this->view->scenario = strtoupper($skenario['nomor']);
        $this->view->penilai = $modelUser->getByLogin( $identity->id);
	}
}