<?php
/**
 * List of Kogas for nilai
 *
 * @author Febi
 */

class Latihan_PenilaianProdukNaskahController extends App_CrudController
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
        $teamid = $this->_request->getParam('team_id');
        $modelJabatan = new Cms_Model_DbTable_Jabatan();
        $model = new Latihan_Model_DbTable_MateriProdukNaskah();

        $modelNilai = new Latihan_Model_Crud_PenilaianProdukNaskah();
        $data = $this->_request->getPost();

        $items = array();
        $i = 1;
        foreach($model->getParentDetil($teamid) as $det) {

            $det['rowspan'] = count($model->getMateriByParent($teamid, $det['id'])) + 1;
            foreach($model->getMateriByParent($teamid, $det['id']) as $mat) {
                $j = 1;
                foreach($model->getMateriByParent($teamid, $mat['id']) as $submat) {
                    $submat['numbering'] = $this->numtochars($j, 97, 122);
                    $mat['the_submateri'][] = $submat;
                    $mat['rowspan'] = count($model->getMateriByParent($teamid, $mat['id'])) + 1;
                    $j++;
                }
                $det['the_materi'][] = $mat;
                $det['rowspan'] += count($model->getMateriByParent($teamid, $mat['id']));
            }
            $det['numbering'] = $this->numtochars($i);
            array_push($items, $det);
            $i++;
        }

        $kogasIndex = $kogas;
        if ($this->_request->isPost()) {
            foreach($model->getParentDetil($teamid) as $det) {
                foreach($model->getMateriByParent($teamid, $det['id']) as $mat) {
                    $modelNilai->save( array( 'detil' => $det['id'], 'materi' => $mat['id'], 'nilai' => $data['mako'.$det['id'].$mat['id']], 'kogas' => $kogasIndex, 'parameter' => 1, 'team_id' => $teamid));
                    $modelNilai->save( array( 'detil' => $det['id'], 'materi' => $mat['id'], 'nilai' => $data['lant1'.$det['id'].$mat['id']], 'kogas' => $kogasIndex, 'parameter' => 2, 'team_id' => $teamid));
                    $modelNilai->save( array( 'detil' => $det['id'], 'materi' => $mat['id'], 'nilai' => $data['lant2'.$det['id'].$mat['id']], 'kogas' => $kogasIndex, 'parameter' => 3, 'team_id' => $teamid));
                    $modelNilai->save( array( 'detil' => $det['id'], 'materi' => $mat['id'], 'nilai' => $data['lant3'.$det['id'].$mat['id']], 'kogas' => $kogasIndex, 'parameter' => 4, 'team_id' => $teamid));
                    $modelNilai->save( array( 'detil' => $det['id'], 'materi' => $mat['id'], 'nilai' => $data['lant4'.$det['id'].$mat['id']], 'kogas' => $kogasIndex, 'parameter' => 5, 'team_id' => $teamid));
                }
            }

            $this->_redirector->gotoSimple('penilaian', null, null, array('team_id' => $teamid, 'kogas' => $kogas));
        }

        $this->view->items = $items;
        $this->view->teamId = $teamid;
        $this->view->kogas = ucfirst($modelJabatan->getNama($kogas));
        $this->view->model = $modelNilai;
        $this->view->kogasIndex = $kogasIndex;
    }

    public function materiAction()
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
        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $modelTeam = new Zend_Db_Table('user.Team');

        $items = array();
        $i = 1;
        foreach($model->getParentDetil($teamid) as $det) {

            $det['rowspan'] = count($model->getMateriByParent($teamid, $det['id'])) + 1;
            foreach($model->getMateriByParent($teamid, $det['id']) as $mat) {
                $j = 1;
                foreach($model->getMateriByParent($teamid, $mat['id']) as $submat) {
                    $submat['numbering'] = $this->numtochars($j, 97, 122);
                    $mat['the_submateri'][] = $submat;
                    $mat['rowspan'] = count($model->getMateriByParent($teamid, $mat['id'])) + 1;
                    $j++;
                }
                $det['the_materi'][] = $mat;
                $det['rowspan'] += count($model->getMateriByParent($teamid, $mat['id']));
            }
            $det['numbering'] = $this->numtochars($i);
            array_push($items, $det);
            $i++;
        }

        $this->view->items = $items;
        $this->view->teamId = $teamid;

        $this->view->team = $modelTeam->fetchAll($modelTeam->select()->where('id <> ?', $teamid)->order('team_name ASC'))->toArray();
    }

    public function addDetilAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_add(null, 'list', 'Latihan_Model_Crud_DetilProduk', array('teamid' => $teamid));
        $this->view->teamId = $teamid;
    }

    public function addMateriAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_add(null, 'list', 'Latihan_Model_Crud_MateriProduk', array('teamid' => $teamid));
        $this->view->teamId = $teamid;

        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $this->view->parents = $model->getParentDetil($teamid);
    }

    public function addSubmateriAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_add(null, 'list', 'Latihan_Model_Crud_SubmateriProduk', array('teamid' => $teamid));
        $this->view->teamId = $teamid;

        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $this->view->parents = $model->getParentMateri($teamid);
    }


    public function editDetilAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_edit(null, 'list', 'Latihan_Model_Crud_DetilProduk', array('teamid' => $teamid));
    }

    public function editMateriAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_edit(null, 'list', 'Latihan_Model_Crud_MateriProduk', array('teamid' => $teamid));

        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $this->view->parents = $model->getParentDetil($teamid);
    }

    public function editSubmateriAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_edit(null, 'list', 'Latihan_Model_Crud_SubmateriProduk', array('teamid' => $teamid));

        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $this->view->parents = $model->getParentMateri($teamid);
    }

    public function delAction()
    {
        $teamid = $this->_request->getParam('teamid');
        $this->_del(null, 'list', 'Latihan_Model_Crud_ProdukNaskah', array('teamid' => $teamid));
    }

    public function printAction()
    {
        $this->_helper->_layout->setLayout('print-layout');

        $kogas = $this->_request->getParam('kogas');
        $teamid = $this->_request->getParam('team_id');
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $modelJabatan = new Cms_Model_DbTable_Jabatan();
        $model = new Latihan_Model_DbTable_MateriProdukNaskah();

        $modelUser = new Management_Model_DbTable_Login();
        $modelSkenario = new Management_Model_DbTable_Team();
        $skenario = $modelSkenario->gaetidteam($teamid);

        $modelNilai = new Latihan_Model_Crud_PenilaianProdukNaskah();
        $kogasIndex = $kogas;

        $items = array();
        $i = 1;
        $k = 0;
        $l = 0;
        $matI = 1;
        foreach($model->getParentDetil($teamid) as $det) {
            $parameter = array();
            $parameterPembagi = 0;
            $mako = 0;
            $lant1 = 0;
            $lant2 = 0;
            $lant3 = 0;
            $lant4 = 0;
            if(++$k % 22 == 0) {
                $l++;
                $items[$l][$det['id']]['rowspan'] = 1;
                $items[$l][$det['id']]['numbering'] = $this->numtochars($i);
            }
            $items[$l][$det['id']] = $det;
            $items[$l][$det['id']]['rowspan'] = 1;
            $items[$l][$det['id']]['numbering'] = $this->numtochars($i);
            foreach($model->getMateriByParent($teamid, $det['id']) as $mat) {
                if(++$k % 22 == 0) {
                    $l++;
                    $items[$l][$det['id']] = $det;
                    $items[$l][$det['id']]['rowspan'] = 2;
                    $items[$l][$det['id']]['numbering'] = $this->numtochars($i);
                    $items[$l][$det['id']]['the_materi'][$mat['id']]['rowspan'] = 1;
                }
                $items[$l][$det['id']]['the_materi'][$mat['id']] = $mat;
                $items[$l][$det['id']]['the_materi'][$mat['id']]['rowspan'] = 1;
                $items[$l][$det['id']]['the_materi'][$mat['id']]['numbering'] = $matI;
                $items[$l][$det['id']]['rowspan'] = $items[$l][$det['id']]['rowspan'] + 1;

                array_push( $parameter, $mat['point'] . ' ' . $mat['singkatan']);
                $parameterPembagi += $mat['point'];
                $mako += $modelNilai->getByData( $det['id'], $mat['id'], $kogasIndex, 1, $teamid, 'nilai') *  $mat['point'];
                $lant1 += $modelNilai->getByData( $det['id'], $mat['id'], $kogasIndex, 2, $teamid, 'nilai') *  $mat['point'];
                $lant2 += $modelNilai->getByData( $det['id'], $mat['id'], $kogasIndex, 3, $teamid, 'nilai') *  $mat['point'];
                $lant3 += $modelNilai->getByData( $det['id'], $mat['id'], $kogasIndex, 4, $teamid, 'nilai') *  $mat['point'];
                $lant4 += $modelNilai->getByData( $det['id'], $mat['id'], $kogasIndex, 5, $teamid, 'nilai') *  $mat['point'];


                $j = 1;
                foreach($model->getMateriByParent($teamid, $mat['id']) as $submat) {
                    if(++$k % 22 == 0) {
                        $l++;
                        $items[$l][$det['id']]['rowspan'] = 2;
                        $items[$l][$det['id']]['numbering'] = $this->numtochars($i);
                        $items[$l][$det['id']]['the_materi'][$mat['id']]['rowspan'] = 1;
                        $items[$l][$det['id']]['the_materi'][$mat['id']]['numbering'] = $matI;
                    }
                    $items[$l][$det['id']]['the_materi'][$mat['id']]['the_submateri'][$j] = $submat;
                    $items[$l][$det['id']]['the_materi'][$mat['id']]['the_submateri'][$j]['numbering'] = $this->numtochars($j, 97, 122);
                    $items[$l][$det['id']]['rowspan'] = $items[$l][$det['id']]['rowspan'] + 1;
                    $items[$l][$det['id']]['the_materi'][$mat['id']]['rowspan'] = $items[$l][$det['id']]['the_materi'][$mat['id']]['rowspan'] + 1;
                    $items[$l][$det['id']]['the_materi'][$mat['id']]['numbering'] = $matI;
                    $j++;
                }
                $items[$l][$det['id']]['the_materi'][$mat['id']]['numbering'] = $matI++;

            }
            if(++$k % 22 == 0) {
                $l++;
            }
            $items[$l][$det['id']]['nilai_naskah'] = array('top' => implode(' + ', $parameter), 'bottom' => $parameterPembagi,
                                                            'mako' => round($mako / $parameterPembagi, 0),
                                                            'lant1' => round($lant1 / $parameterPembagi, 0),
                                                            'lant2' => round($lant2 / $parameterPembagi, 0),
                                                            'lant3' => round($lant3 / $parameterPembagi, 0),
                                                            'lant4' => round($lant4 / $parameterPembagi, 0));
            $i++;
        }


        $this->view->items = $items;
        $this->view->teamId = $teamid;
        $this->view->kogas = ucfirst($modelJabatan->getNama($kogas));
        $this->view->model = $modelNilai;
        $this->view->kogasIndex = $kogasIndex;

        $this->view->scenario = strtoupper($skenario['nomor']);
        $this->view->penilai = $modelUser->getByLogin( $identity->id);
    }

    function numtochars($num,$start=65,$end=90)
    {
        $sig = ($num < 0);
        $num = abs($num);
        $str = "";
        $cache = ($end-$start);
        while($num != 0)
        {
            $str = chr(($num%$cache)+$start-1).$str;
            $num = ($num-($num%$cache))/$cache;
        }
        if($sig)
        {
            $str = "-".$str;
        }
        return $str;
    }

    public function copyAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $teamID = $this->_getParam('teamid');
        $copyID = $this->_getParam('copyid');

        $model = new Latihan_Model_DbTable_MateriProdukNaskah();
        $model->copy( $teamID, $copyID);
        $this->_redirector->gotoSimple('list', null, null, array('teamid' => $teamID));
    }
}