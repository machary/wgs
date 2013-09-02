<?php

class Latihan_RolController extends App_CrudController
{
    public function indexAction()
    {
        //load daftar scenario
        $this->view->scenarioList = $this->getActiveScenario();
        //load daftar jabatan
        $this->view->jabatanList = $this->getJabatan();
        //check privileges, show warning if can edit
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $tablePrivileges = new Latihan_Model_DbTable_Privileges();
            if($tablePrivileges->checkRolesPrivileges('latihan', 'rol', 'edit', $identity->role_id) >= 1)
            {
                $this->view->showEditWarning = true;
            }
        }
    }

    public function eventfeedAction()
    {
        //disable view and set header
        $this->disableView();
        $this->setCustomHeader('json');
        //get params
        $startTime = $this->_getParam('start');
        $endTime = $this->_getParam('end');
        $idJabatan = $this->_getParam('id_jabatan');
        $idSkenario = $this->_getParam('id_skenario');
        //get list of events
        $listEvent = $this->getEventFeed($startTime, $endTime, $idJabatan, $idJabatan);
        //initialize empty array
        $arrayEvent = array();
        //loop through the list of the events
        if(count($listEvent))
        {
            foreach($listEvent as $event)
            {
                //converting date time format
                $asumsiDayStart  = date('d', strtotime($event['asumsi_start']));
                $asumsiDayEnd    = date('d', strtotime($event['asumsi_end']));
                $asumsiDateStart = date('d/F/Y', strtotime($event['asumsi_start']));
                $asumsiDateEnd   = date('d/F/Y', strtotime($event['asumsi_end']));
                $asumsiTimeStart = date('H:i:s', strtotime($event['asumsi_start']));
                $asumsiTimeEnd   = date('H:i:s', strtotime($event['asumsi_end']));
                //if a one day events only
                if( $asumsiDayStart == $asumsiDayEnd )
                {
                    //print the date only once
                    $title  = $event['nama_kegiatan'] . ' waktu asumsi ' . $asumsiDateStart . ', ';
                }else{
                    //print only the start and end date
                    $title  = $event['nama_kegiatan'] . ' waktu asumsi ' . $asumsiDateStart . ' - ' . $asumsiDateEnd . ', ';
                }
                //print the timeline
                $title .= $asumsiTimeStart . ' - ' . $asumsiTimeEnd;
                //print assumption time
                $title .= '  Perbandingan Sebenarnya : Asumsi = ' . $event['asumsi_perbandingan'];
                //add edit links only if user can access the edit forms: latihan-rol-edit
                if (Zend_Auth::getInstance()->hasIdentity()) {
                    $identity = Zend_Auth::getInstance()->getStorage()->read();
                    $tablePrivileges = new Latihan_Model_DbTable_Privileges();
                    if($tablePrivileges->checkRolesPrivileges('latihan', 'rol', 'edit', $identity->role_id) >= 1)
                    {
                        $tempArray = array(
                            'id'     => $event['id_rol'],
                            'start'  => $event['realtime_start'],
                            'end'    => $event['realtime_end'],
                            'url'    => $this->view->baseUrl('latihan/rol/edit/id/') . $event['id_rol'] ,
                            'title'  => $title,
                            'allDay' => false
                        );
                    }else{
                        $tempArray = array(
                            'id'     => $event['id_rol'],
                            'start'  => $event['realtime_start'],
                            'end'    => $event['realtime_end'],
                            'title'  => $title,
                            'allDay' => false
                        );
                    }
                }
                //form into fullcalendar json object format
                array_push($arrayEvent, $tempArray);
            }
            echo json_encode($arrayEvent);
        }
    }

    public function addAction()
    {
        //load daftar scenario
        $this->view->scenarioList = $this->getActiveScenario();
        //load daftar jabatan
        $this->view->jabatanList = $this->getJabatan();
    }

    public function saveAction()
    {
        $this->disableView();
        $flag = true;
        $params = $this->_request->getParams();
        //manual check none of the field is empty
        //todo:rewrite the code using Zend_Form and Zend_Validate
        foreach($params as $param => $value)
        {
            //if any field is empty then return false
            if(!$value){
                $flag = false;
            }
        }

        //if field check is true
        if($flag == true){
            $realStart = strtotime(str_replace('/', '-', $params['realtime_start']));
            $realEnd   = strtotime(str_replace('/', '-', $params['realtime_end']));
            $asumsiStart   = strtotime(str_replace('/', '-', $params['asumsi_start']));
            $asumsiEnd     = strtotime(str_replace('/', '-', $params['asumsi_end']));
            //if end time is less then start time then redirect to add page
            if($asumsiStart >= $asumsiEnd AND $realStart >= $realEnd)
            {
                //todo: add flash message
                $this->_redirector->gotoSimple('add');
            }

            $tableRol = new Latihan_Model_DbTable_Rol();
            $params['realtime_start']   = date('m/d/Y H:i:s', $realStart);
            $params['realtime_end']     = date('m/d/Y H:i:s', $realEnd);
            $params['asumsi_start']     = date('m/d/Y H:i:s', $asumsiStart);
            $params['asumsi_end']       = date('m/d/Y H:i:s', $asumsiEnd);
            $action = $params['do'];
            if($action)
            {
                $rolID = $this->_getParam('rolid');
                switch($action){
                    case 'update':
                        $tableRol->updateRol($params, $rolID);
                        break;
                    case 'delete':
                        $tableRol->deleteRol($rolID);
                        break;
                }
                $this->_redirector->gotoSimple('index');
            }
            $tableRol->addRol($params);
            //$this->_redirector->gotoSimple('index');
        }else{
            //todo: add flash message
            $this->_redirector->gotoSimple('add');
        }
    }

    public function deleteAction()
    {}

    public function editAction()
    {
        //load daftar scenario
        $this->view->scenarioList = $this->getActiveScenario();
        //load daftar jabatan
        $this->view->jabatanList = $this->getJabatan();
        //send rol ID ke view
        $this->view->rolID = $this->_getParam('id');
        //load rol
        //todo: ganti manual form pake Zend_Form (crud nya kanwil)
        $rolID = $this->_getParam('id');
        if($rolID)
        {
            $tableRol = new Latihan_Model_DbTable_Rol();
            $rowData = $tableRol->getRolByRolID($rolID);
            $formattedField = array('realtime_start', 'realtime_end', 'asumsi_start', 'asumsi_end');
            foreach($rowData as $key => $value)
            {
                if(in_array($key, $formattedField)){
                    $rowData[$key] = date('d/m/y H:i:s', strtotime($value));
                }
            }
            $this->view->rowData = $rowData;
        }else{
            $this->_redirector->gotoSimple('add');
        }
    }

    protected function disableView()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }

    public function setCustomHeader($key)
    {
        $contentType = array(
            'json' => 'application/json',
            'js'   => 'text/javascript',
            'html' => 'text/html',
            'xml'  => 'text/xml',
            'css'  => 'text/css',
        );
        $type = ($contentType[$key]) ? $contentType[$key] : $key;
        $this->getResponse()->setHeader('Content-Type', $type);
    }

    protected function getEventFeed( $startDate, $endDate, $skenarioID, $jabatanID )
    {
        $rolTable = new Latihan_Model_DbTable_Rol();
        return $rolTable->getRol( $startDate, $endDate, $skenarioID, $jabatanID );
    }

    protected function getJabatan()
    {
        $tableJabatan = new Latihan_Model_DbTable_List();
        $listJabatan = $tableJabatan->listAllJabatan();
        if(count($listJabatan))
        {
            return $listJabatan;
        }else{
            return null;
        }
    }

    protected function getActiveScenario()
    {
        $tableSkenario = new Latihan_Model_DbTable_List();
        $listSkenario = $tableSkenario->listSkenarioAktif();
        if(count($listSkenario))
        {
            return $listSkenario;
        }else{
            return null;
        }
    }

}

?>