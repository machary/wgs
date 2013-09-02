<?php
/**
 * CRUD untuk Telegram
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_TelegramController extends App_CrudController
{
	// halaman menampilkan inbox telegram
	public function indexAction()
	{
        $folder = new Peta_Model_FolderTelegram();
        $this->view->identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->folder = $folder;
	}

	// halaman menampilkan outbox telegram
	public function outboxAction()
	{
        $folder = new Peta_Model_FolderTelegram();
        $this->view->identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->folder = $folder;
	}

	// halaman menampilkan draft telegram
	public function draftAction()
	{
        $folder = new Peta_Model_FolderTelegram();
        $this->view->identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->folder = $folder;
	}

	// halaman menampilkan draft telegram
	public function kirimDraftAction()
	{
		$obj = new Peta_Model_TelegramCrud(null,$this->_request->getParam('id'));
		$obj->kirimdraf();
		$this->_redirector->gotoSimple('outbox');
	}

	// get data untuk datatables inbox/outbox
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

		$dt = new Peta_Model_Datatables_Telegram($this->_request->getParams(),$this->view);
		echo $dt->result();
	}
	
    //halaman untuk menampilkan form telegram baru
	public function addAction()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$crud = new Peta_Model_TelegramCrud();

		$form = $crud->form();

        $form->setDefaults(array('pengirim_id' => $identity->id));
        $form->setDefaults(array('team_id' => $identity->id_team));

        if ($identity->role_id != 9){
            //$form->setDefaults(array('nomor_telegram' => $crud->generate_NoTel()));
    		$form->setDefaults(array('pengirim' => $identity->username));
        }

		if ($this->_request->isPost()) {

			if ($form->isValid($this->_request->getPost())) {
                $x =$this->_request->getPost();

				$crud->setFromForm($form);
				$crud->save();

				if ($x['isdraft'] == 'true'){
					$this->_redirector->gotoSimple('draft');
				}else{
					$this->_redirector->gotoSimple('outbox');
				}
			}
		}
        $folder = new Peta_Model_FolderTelegram();
        $this->view->identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->folder = $folder;
		$this->view->form = $form;
	}

    public function replayAction()
    {
        //$this->_replay(null, 'index', 'Peta_Model_TelegramCrud');

        $id = $this->_getParam('id');


        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $crud = new Peta_Model_TelegramCrud();
        $model = new Peta_Model_DbTable_SetTelegram();
        $folder = $model->selectSetFolder();

        $dataLoad = $crud->getTelegram($id);
        $form = $crud->form();

        $form->setDefaults(array('folder' => $folder['child_id_folder_telegram']));
        $form->setDefaults(array('pengirim_id' => $identity->id));
        $form->setDefaults(array('team_id' => $identity->id_team));
        $form->setDefaults(array('nomor_telegram' => 're : '.$dataLoad['nomor_telegram']));
        $form->setDefaults(array('kepada' => $dataLoad['pengirim']));
        $form->setDefaults(array('kepada_id' => ','.$dataLoad['pengirim_id'].','));
        $form->setDefaults(array('tembusan' => $dataLoad['tembusan']));
        $form->setDefaults(array('tembusan_id' => $dataLoad['tembusan_id']));

        if ($identity->role_id != 9){
            //$form->setDefaults(array('nomor_telegram' => $crud->generate_NoTel()));
            $form->setDefaults(array('pengirim' => $identity->username));
        }

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $x =$this->_request->getPost();

                $crud->setFromForm($form);
                $crud->save();

                if ($x['isdraft'] == 'true'){
                    $this->_redirector->gotoSimple('draft');
                }else{
                    $this->_redirector->gotoSimple('outbox');
                }
            }
        }
        $this->view->form = $form;
    }
	
	public function editAction()
	{
        $listAction = 'outbox';
       //print_r($_POST);exit;
        if ($this->_request->isPost())
        {
            if($_POST['isdraft'] == 'true'){
                $listAction = 'draft';
            }
        }

        $this->_edit(null, $listAction, 'Peta_Model_TelegramCrud');

        $folder = new Peta_Model_FolderTelegram();
        $this->view->identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->folder = $folder;
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Peta_Model_TelegramCrud');
	}

    public function folderAction()
    {
        $folder = new Peta_Model_FolderTelegram();
        if ($this->_request->isPost()) {
            $folder->setFromPost($this->_request->getPost());
            if ($folder->isValid()) {
                $folder->save();
                $this->view->successAlert = 'Tersimpan';
            }
        }

        $this->view->jenis = $folder;
    }

//    public function setfolderAction()
//    {
//        $request = $this->getRequest();
//        $form = new Peta_Form_SetFolder();
//        $model = new Peta_Model_DbTable_SetTelegram();
//
//        if($request->isPost() AND $form->isValid($this->_request->getPost()))
//        {
//            $data = $model->selectSetFolder();
//            if($data != null)
//            {
//                $model->updateSetFolder($form->getValues());
//                $this->_redirect('peta/telegram');
//            }
//            else
//            {
//                $model->saveSetFolder($form->getValues());
//                $this->_redirect('peta/telegram');
//            }
//        }
//        else
//        {
//            $data = $model->selectSetFolder();
//            if($data != null)
//            {
//                $form->populate($data);
//            }
//        }
//
//        $this->view->form = $form;
//    }

    public function hariAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $latVal = $this->_request->getParam('latihanValue');

        $model = new Peta_Model_TelegramCrud();
        $data = $model->getHari($latVal);

        echo json_encode($data);
    }

    public function kogasAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }

    public function viewAction()
    {
        $obj = new Peta_Model_TelegramCrud(null,$this->_request->getParam('id'));
		$obj->sudahbaca();

        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->obj = $obj;
        $this->view->printMode = $identity->print_mode;
    }

	public function listToHtmlAction()
	{
		$arrval = $this->_request->getParam('arrval');
		$this->getHelper('layout')->disableLayout();

		$dt = new Peta_Model_Datatables_Telegram($this->_request->getParams());

		$this->view->listto = $dt->getListTo($arrval);
	}

    public function refreshAction(){
        $data = Array();
        $this->disableViewAndLayout();
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        array_push($data,array("hitung" => $this->unreadCount($identity->id,true),"detail"=>$this->notification($identity->id)));

        echo json_encode($data);
    }

}