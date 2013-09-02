<?php
/**
 * List of Kogas for nilai
 *
 * @author Febi
 */

class Latihan_PenilaianPaparanNaskahController extends App_CrudController
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
                        $kogasData[$team['id']] = $team['team_name'];
                    }
                }
            }
            $this->view->kogas = $kogasData;
        } else {
            $this->_redirector->gotoSimple('penilaian', null, null, array( 'team_id' => $jabatan[0]['id_team']));
        }
    }

    public function penilaianAction(){
        $team_id = $this->_getParam('team_id');

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Latihan_Model_Crud_PenilaianPaparanNaskah();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $modelAspek = new Latihan_Model_DbTable_AspekPaparanNaskah();
        $modelTeam = new Zend_Db_Table('user.Team');

        $team = $modelTeam->find($team_id)->toArray();
        $jabatan = $modelPenilai->selectAllwithlogin($identity->id);

        $kogasData = array();
        foreach($jabatan as $kogas) {
            $kogasData[$kogas['id_jabatan']] = $kogas['nama_jabatan'];
        }

        $data = $this->_request->getPost();
        if ($this->_request->isPost()) {
            $index = $data['item_index'];
            $indexAspek = $data['item_aspek_index'];
            unset($data['item_index']);
            unset($data['item_aspek_index']);
            for($i=1;$i<=$index;$i++){
                for($j=1;$j<=$indexAspek;$j++){
                    $model->save( $data['aspekid'.$i.$j], $data['kogas'.$i.$j], array( 'aspek_id' => $data['aspekid'.$i.$j], 'kogas' => $data['kogas'.$i.$j], 'nilai' => $data['nilai'.$i.$j], 'keterangan' => $data['keterangan'.$i.$j]));
                }
            }
            $this->_redirector->gotoSimple('penilaian', null, null, array('team_id' => $team_id));
        }

        $this->view->kogas = $kogasData;
        $this->view->team = $team[0]['team_name'];
        $this->view->aspek = $modelAspek->allByTeamId($team[0]['id']);
        $this->view->model = $model;
    }

    public function aspekAction()
    {
        if($this->isAjax())
        {
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();

            $model = new Management_Model_Pelaku();
            // Param
            $sEcho = intval( $this->_getParam( 'sEcho' ) );
            $sSearch = $this->_getParam( 'sSearch' );
            // Paging
            $offset = $this->_getParam( 'iDisplayStart' );
            $limit = $this->_getParam( 'iDisplayLength' );
            // Sort Order
            $sortColumn = $this->_getParam( 'iSortCol_0' );
            $order = $this->_getParam( 'sSortDir_0' );
            //custom filter
            $filter = $this->_getParam( 'filter' );
            $areaLevel = $this->_getParam( 'arealevel' );
            $areaID = $this->_getParam( 'areaid' );

            //get rows
            $jsonString = $model->datatablesTeamJSONApi($areaLevel, $areaID,
                $sEcho, $limit, $offset,
                $sortColumn, $order,
                $filter, $sSearch,
                $this->view);

            echo  $jsonString;
        }
    }
    public function listAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $model = new Latihan_Model_DbTable_AspekPaparanNaskah();
        $modelTeam = new Zend_Db_Table('user.Team');

        $this->view->items = $model->allByTeamId($teamid);

        $this->view->team = $modelTeam->fetchAll($modelTeam->select()->where('id <> ?', $teamid)->order('team_name ASC'))->toArray();
    }

    public function addAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_add(null, 'list', 'Latihan_Model_Crud_AspekNaskah', array('teamid' => $teamid));
        $this->view->teamId = $teamid;
    }

    public function editAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_edit(null, 'list', 'Latihan_Model_Crud_AspekNaskah', array('teamid' => $teamid));
    }

    public function delAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_del(null, 'list', 'Latihan_Model_Crud_AspekNaskah', array('teamid' => $teamid));
    }

    public function printAction()
    {
        $this->_helper->_layout->setLayout('print-layout');
        $team_id = $this->_getParam('team_id');

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Latihan_Model_Crud_PenilaianPaparanNaskah();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $modelAspek = new Latihan_Model_DbTable_AspekPaparanNaskah();
        $modelTeam = new Zend_Db_Table('user.Team');

        $team = $modelTeam->find($team_id)->toArray();
        $jabatan = $modelPenilai->selectAllwithlogin($identity->id);

        $modelUser = new Management_Model_DbTable_Login();
        $modelSkenario = new Management_Model_DbTable_Team();
        $skenario = $modelSkenario->gaetidteam($team[0]['id']);

        $aspekData = $modelAspek->allByTeamId($team[0]['id']);

        $kogasData = array();
        foreach($jabatan as $kogas) {
            $kogasIndex = $kogas['id_jabatan'];
            $nilai = null;
            foreach($aspekData as $asp){
                $nilai += $model->getByAspekKogas( $asp['id'],$kogasIndex, 'nilai');
            }
            array_push($kogasData, array( 'id' => $kogasIndex, 'nama' => $kogas['nama_jabatan'], 'totalNilai' => $nilai));
        }

        $data = array();
        $i = 0;
        $j = 0;
        $k = 1;
        foreach($kogasData as $kog){
            $data[$i][$k] = $kog;
            foreach($aspekData as $index => $asp){
                $asp['nilai'] = $model->getByAspekKogas( $asp['id'], $kog['id'], 'nilai');
                $asp['keterangan'] = $model->getByAspekKogas( $asp['id'], $kog['id'], 'keterangan');
                $data[$i][$k]['aspek'][] = $asp;
                $j++;
                if($j % 16 == 0) {
                    $i++;
                    if((count($aspekData) - 1 != $index)){
                        $data[$i][$k] = $kog;
                    }
                }
            }
            $k++;
        }

        $this->view->kogas = $kogasData;
        $this->view->team = $team[0]['team_name'];
        $this->view->aspek = $data;
        $this->view->model = $model;


        $this->view->scenario = strtoupper($skenario['nomor']);
        $this->view->penilai = $modelUser->getByLogin( $identity->id);
    }

    public function copyAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $teamID = $this->_getParam('teamid');
        $copyID = $this->_getParam('copyid');

        $model = new Latihan_Model_DbTable_AspekPaparanNaskah();
        $model->copy( $teamID, $copyID);
        $this->_redirector->gotoSimple('list', null, null, array('teamid' => $teamID));
    }

}