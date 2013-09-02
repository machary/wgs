<?php
/**
 * Halaman Beranda
 */
class ArtikelController extends App_Controller
{

	public function init()
	{
		/* Initialize action controller here */
		parent::init();
	}

	public function indexAction()
	{

	}

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Default_Model_Datatables_Artikel();
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

        //get rows
        $jsonString = $model->datatablesJSONApi($sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch, $this->view);

        echo  $jsonString;
    }

    public function detailAction()
    {
        $id = $this->_getParam( 'id', null);
        $newsModel = new Default_Model_DbTable_News();
        $this->view->news = $newsModel->getByID( $id );
    }
}

