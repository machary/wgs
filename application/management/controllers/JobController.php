<?php
class Management_JobController extends Zend_Controller_Action
{
    public function init()
    {

    }

    public function indexAction()
    {

    }

    public Function addAction()
    {

    }

    public function adddataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Job();
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

        $no_cb = $this->_getParam('param_nocb');

        //get rows
        $jsonString = $model->datatablesJSONApiadd($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view, $no_cb);

        echo  $jsonString;
    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Management_Model_Job();
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

        $no_cb = $this->_getParam('param_nocb');

        //get rows
        $jsonString = $model->datatablesJSONApi($areaLevel, $areaID,
            $sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch,
            $this->view, $no_cb);

        echo  $jsonString;
    }

    public function addjobAction()
    {
        $id = array();
        $jabatan = array();
        $jab = explode(',', $this->_getParam('jabatan_value'));
        $subjabatan = explode(',', $this->_getParam('subjabatan_value'));
        $id_skenario = explode(',', $this->_getParam('skenario_value'));
        $count = count($jab);

        $modelLogin = new Management_Model_DbTable_Login();
        $modelJob = new Management_Model_DbTable_Job();

        for($x=0;$x<=$count-1;$x++)
        {
            list($id[$x],$jabatan[$x]) = explode('-', $jab[$x]);

            $datlog = $modelLogin->getnrp($id[$x]);
            $modelJob->addJob($datlog, $jabatan[$x], $subjabatan[$x], $id_skenario[$x]);
        }

        $this->_redirect('management/job');
    }

    public function editjobAction()
    {
        $request = $this->getRequest();
        $id = $this->_getParam('id');

        $model = new Management_Model_DbTable_Job();
        $form = new Management_Form_Job_Edit();

        $result = $model->getdatajob($id);

        if($request->isPost() AND $model->isValid($this->_request->getPost())){
            $model->updateJob($form->getValues(), $id);
            $this->_redirect('management/job');
        }else{
            $form->populate($result);
        }

        $this->view->form = $form;
    }

    public function deletejobAction()
    {
        $id = $this->_getParam('id');
    }
}