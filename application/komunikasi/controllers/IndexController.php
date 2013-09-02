<?php
/**
 * Author : Hermanet Lay
 */

class Komunikasi_IndexController extends App_CrudController{

    private $bbbClass;
	private $defaultModPassword = '123123';
	private $defaultAttPassword = '123123';

	public function init()
    {
        parent::init();
        //todo: pindahin server dan salt ke config.ini
        $server = 'http://10.1.1.102/bigbluebutton/api/';
        $salt = '092cff234fe33cb31acf100fc010090d';

        $this->view->defaultPassword = $this->defaultAttPassword;
		$this->bbbClass = new Komunikasi_Model_BBBAPI($salt, $server);
    }

	protected function _add($tableName, $listAction, $submitLabel = 'simpan', $crudClass = 'Cms_Model_Crud',
							$options = array())
	{
		$crud = new $crudClass($tableName);
		$form = $crud->form();
		$form->removeElement('submit');
		$form->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => $submitLabel,
		));

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				//get creator id
				if( Zend_Auth::getInstance()->hasIdentity() ){
					$identity = Zend_Auth::getInstance()->getStorage()->read();
				}
				//save meeting to database
				$meetingID = SHA1($this->values['room_name'] . time());
				$crud->setFromForm($form, $meetingID, $identity->username);
				$crud->save();
				//Send request to BBB Server to create meeting
				$params = $this->_request->getPost();
				$roomOption = array(
					'name' 		  		=> $params['room_name'],
					'attendeePW'  		=> $params['attendeePW'],
					'meetingID'   		=> $meetingID,
					'moderatorPW' 		=> $params['moderatorPW'],
					'welcome'			=> 'Seskoal Wargaming Meeting Room'
				);
				$response = $this->bbbClass->createRoom($roomOption);
				if($response['status'] == true){
					$this->_helper->flashMessenger->addMessage('Ruang diskusi telah dibuat');
				}else{
					$this->_helper->flashMessenger->addMessage('Maaf, Ruang diskusi gagal dibuat, mohon ulangi proses pembuatan ruangan');
				}
				$this->_redirector->gotoSimple($listAction, null, null, $options);
			}
		}
		$this->view->form = $form;
	}

	public function indexAction()
    {
		//get room list from database
		$tableRoom = new Komunikasi_Model_DbTable_Room();
		$roomList = $tableRoom->getAllRoom();
		if($roomList)
		{
			foreach($roomList as $index => $room){
				$option = array(
					'meetingID' => $room['room_id'],
					'password' 	=> $room['moderatorPW']
				);

                $roomInfo = $this->bbbClass->doAction('info_meeting', $option);
                $roomList[$index]['room_info'] = $roomInfo;
			}
		}

		$this->view->roomList = $roomList;
	}

	public function joinAction()
	{
		$meetingID 	= $this->_getParam('meetingid');
		$role 		= $this->_getParam('role');
		//get room credential information
		$tableRoom 	= new Komunikasi_Model_DbTable_Room();
		$roomInfo 	= $tableRoom->getRoomInfoByID($meetingID);
		//send meeting id for form url, please take a look at join.phtml form action
		$this->view->meetingID = $meetingID;
		$this->view->role = $role;
		//apabila ruangan tidak tercatat di database
		if(!$roomInfo){
			$this->_helper->flashMessenger->addMessage('Permintaan anda untuk bergabung gagal diproses, mohon ulangi proses untuk bergabung');
			$this->_redirector->gotoSimple('index', null, null);
		}else{
			if($roomInfo['status'] == 'open' AND $role == 'attendee'){
				//disable view
				$this->getHelper('viewRenderer')->setNoRender(true);
				//get creator id
				$identity = Zend_Auth::getInstance()->getStorage()->read();
				$nickName = 'unknown';
				$password = $this->defaultAttPassword;
				$option = array(
					'fullName'  => $identity->username,
					'meetingID' => $roomInfo['room_id'],
					'password'  => $password,
				);
				$roomOption = array(
					'name' 		  		=> $roomInfo['room_name'],
					'attendeePW'  		=> $roomInfo['attendeePW'],
					'meetingID'   		=> $meetingID,
					'moderatorPW' 		=> $roomInfo['moderatorPW'],
					'welcome'			=> 'Selamat Datang Di Seskoal Wargaming Meeting Room'
				);
				$response = $this->bbbClass->createRoom($roomOption);
				$this->_redirector->gotoUrl($this->bbbClass->getJoinMeetingUrl($option));
			}
		}
	}

	public function validatemoderatorAction()
	{
        //disable view
		$this->getHelper('viewRenderer')->setNoRender(true);
		$meetingID = $this->_getParam('meetingid');
		$password = $this->_getParam('password');
		$role = $this->_getParam('role');


		$identity = Zend_Auth::getInstance()->getStorage()->read();
        $tableRoom 	= new Komunikasi_Model_DbTable_Room();
        $roomInfo 	= $tableRoom->getRoomInfoByID($meetingID);

        if(!$roomInfo){
            $this->_helper->flashMessenger->addMessage('Permintaan anda untuk bergabung gagal diproses, mohon ulangi proses untuk bergabung');
            $this->_redirector->gotoSimple('index', null, null);
        }else{
            $option = array(
                'fullName'  => $identity->username,
                'meetingID' => $roomInfo['room_id'],
                'password'  => $password,
            );
            $roomOption = array(
                'name' 		  		=> $roomInfo['room_name'],
                'attendeePW'  		=> $roomInfo['attendeePW'],
                'meetingID'   		=> $meetingID,
                'moderatorPW' 		=> $roomInfo['moderatorPW'],
                'welcome'			=> 'Selamat Datang Di Seskoal Wargaming Meeting Room'
            );

            if( ($role == 'attendee' AND $password == $roomInfo['attendeePW']) OR ($role == 'moderator' AND $password == $roomInfo['moderatorPW']) ){
                if($this->bbbClass->checkValidJoin($option) == true){
                    $meetingUrl = $this->bbbClass->getJoinMeetingUrl($option);
                    $this->_redirector->gotoUrl($this->bbbClass->getJoinMeetingUrl($option));
                }else{
                    $response = $this->bbbClass->createRoom($roomOption);
                    $this->_redirector->gotoUrl($this->bbbClass->getJoinMeetingUrl($option));
                }
            } else {
                $this->_helper->flashMessenger->addMessage('Maaf, Anda gagal terhubung karena password anda salah atau kegagalan sistem, silakan mencoba untuk bergabung kembali dengan ruang diskusi!');
                $this->_redirector->gotoSimple('index', null, null);
            }
        }
	}

	public function endAction()
	{
		$meetingID = $this->_getParam('meetingid');

        if ($this->_request->isPost()) {
            //Send request to BBB Server to create meeting
            $params = $this->_request->getPost();
            $roomOption = array(
                'meetingID' => $meetingID,
                'password' 	=> $params['password']
            );

            $roomInfo = $this->bbbClass->doAction('info_meeting', $roomOption);
            if($roomInfo['response']['returncode'] == 'FAILED') {
                $this->_del($meetingID, 'index');

            } else {
                $response = $this->bbbClass->endMeeting($roomOption);
                if($response == true){
                    $this->_del($meetingID, 'index');
                }else{
                    $this->_helper->flashMessenger->addMessage('Maaf, Anda gagal menutup ruang diskusi karena password anda salah atau kegagalan sistem, silakan mencoba untuk menutup ruang diskusi!');
                    $this->_redirector->gotoSimple( 'index', null, null);
                }
            }
        }
        $this->view->meetingID = $meetingID;
	}

	public function createAction()
	{
		//generate room
		$this->_add(null, 'index', 'Buat Ruangan','Komunikasi_Model_Crud_Room');
	}

	public function saveroomAction()
	{
		//save post
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
		$this->_add(null, 'index', 'Buat Ruangan','Komunikasi_Model_Crud_Room');
	}

	public function enterpasswordAction()
    {
        //get params
    }

	public function endmeetingAction()
	{

	}

    protected function _del($meetingID, $listAction, $options = array())
    {
        $crud = new Komunikasi_Model_DbTable_Room();
        if (count($crud->getRoomInfoByID($meetingID))) {
            $crud->deleteRoom($meetingID);
        }
        $this->_helper->flashMessenger->addMessage('Ruang diskusi berhasil ditutup');
        $this->_redirector->gotoSimple($listAction, null, null, $options);
    }
}