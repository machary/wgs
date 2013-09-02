<?php
/**
 * CRUD untuk User Login
 * @author Kanwil
 */
class Management_PelakuController extends App_CrudController
{
    public function indexAction()
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
        $id = $this->_getParam( 'teamid' );

        $modelJabatan = new Zend_Db_Table('master.M_JABATAN');
        $jabatan = $modelJabatan->fetchAll($modelJabatan->select())->toArray();

        $kogasData = array();
        foreach($jabatan as $kogas) {
            $kogasData[$kogas['id_jabatan']] = $kogas['nama_jabatan'];
        }

        $model = new Cms_Model_DbTable_ProductStaff();
   		$r = new Management_Model_DbTable_Login();
        $tn = new Management_Model_DbTable_Team();
        $team = $tn->find($id);

        $dataTipeSkenario = $model->tipeskenario($id);

        $this->view->jenisPelaku = $dataTipeSkenario[0]['id'];
        $this->view->team = $team->toArray();
        $this->view->teamID = $id;
        $this->view->itemModel = $r;
        $this->view->kogas = $kogasData;;
   	}

	public function addAction()
	{
		$this->_add(null, 'index', 'Management_Model_Crud_Login');
	}

	public function editAction()
	{
		$this->_edit(null, 'index', 'Management_Model_Crud_Login');
	}

	public function delAction()
	{
		$this->_del(null, 'index', 'Management_Model_Crud_Login');
	}
}