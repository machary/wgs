<?php
class LogController extends App_Controller
{
    public function indexAction()
    {
        $modLogin = new Management_Model_DbTable_Login();
        $dataLogin = $modLogin->getAllLogin();

        $this->view->dataLogin = $dataLogin;
    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //$modelGunCategory = new Cms_Model_DbTable_GunCategory();

        //init Model Penyalur
        $modelGunCategory = new Default_Model_Datatables_Log();
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

        $id = $this->_getParam( 'value' );

        //get rows
        $jsonString = $modelGunCategory->datatablesJSONApi($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view, $id);

        echo  $jsonString;
    }
}