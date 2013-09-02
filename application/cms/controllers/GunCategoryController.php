<?php
class Cms_GunCategoryController extends App_Controller
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
        //parent::disableView();
        //$this->getHelper('viewRenderer')->setNoRenderer(true);
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //init Model Penyalur
        $modelGunCategory = new Cms_Model_GunCategory();
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
        $jsonString = $modelGunCategory->datatablesJSONApi($areaLevel, $areaID,
                                                                $sEcho, $limit, $offset,
                                                                $sortColumn, $order,
                                                                $filter, $sSearch,
                                                                $this->view);
        echo  $jsonString;
    }
}