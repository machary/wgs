<?php
class Management_TeamController extends App_Controller
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Team();
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
        $jsonString = $model->datatablesJSONApi($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view);

        echo  $jsonString;
    }

    public function addAction()
    {
        $model = new Latihan_Model_Crud_Skenario();
        $data = $model->getDataSkenario();
        $this->view->data = $data;
    }

    public function dataapiaddAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Team();
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
        $jsonString = $model->datatablesJSONApiadd($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view);

        echo  $jsonString;
    }

    public function addteamAction()
    {
        $tim = $this->_getParam('team_name_value');
        $skenario = $this->_getParam('skenario_value');
        $id_siswa = explode(',', $this->_getParam('id_siswa'));
        $status = explode(',', $this->_getParam('status'));
        $count = count($id_siswa);

        $modLogin = new Management_Model_DbTable_Login();
        $modTeam = new Management_Model_DbTable_Team();

        if($tim != '' || $tim != null || $skenario != '' || $skenario != null)
        {
            $modTeam->saveteam($tim, $skenario);
            $team = $modTeam->gaetteam($tim);

            for($x=0;$x<=$count-1;$x++)
            {
                $modLogin->updatelogin($status[$x], $id_siswa[$x], $team['id']);
            }
        }
        $this->_redirect('management/team');
    }

    public function editteamAction()
    {
        $idTeam = $this->_getParam('id');

        $model = new Management_Model_DbTable_Team();
        $modSken = new Latihan_Model_Crud_Skenario();
        $team = $model->gaetidteam($idTeam);
        $allSken = $modSken->getDataSkenario();

        $this->view->data = $team;
        $this->view->idTeam = $idTeam;
        $this->view->allSken = $allSken;
    }

    public function updateteamAction()
    {
        $idtim = $this->_getParam('id_team');
        $skenario = $this->_getParam('id_skenario');
        $nama_team = $this->_getParam('tim_name');
        $id_siswa = explode(',', $this->_getParam('id_siswa'));
        $status = explode(',', $this->_getParam('status'));
        $count = count($id_siswa)-1;

        $modLogin = new Management_Model_DbTable_Login();
        $modTeam =  new Management_Model_DbTable_Team();

        for($x=0;$x<=$count;$x++)
        {
            $modLogin->updatelogin($status[$x], $id_siswa[$x], $idtim);
        }
        $modTeam->updateskenteam($idtim, $skenario, $nama_team);

        $this->_redirect('management/team');
    }

    public function dataapieditAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Team();
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

        $idTeam = $this->_getParam('idTeam');

        //get rows
        $jsonString = $model->datatablesJSONApiedit($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view, $idTeam);

        echo  $jsonString;
    }

    public function deleteteamAction()
    {
        $idTeam = $this->_getParam('id');
        $modTeam = new Management_Model_DbTable_Team();
        $modLogin = new Management_Model_DbTable_Login();

        $modTeam->deleteteam($idTeam);

        foreach($modLogin->getteam($idTeam) as $user)
        {
            $modLogin->updatelogin('F', $user['id'], null);
        }

        $this->_redirect('management/team');
    }
}