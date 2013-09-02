<?php
/**
 * Menyediakan method-method yang biasa dipakai untuk CRUD
 *
 * @author Kanwil
 */
 
class App_CrudController extends App_Controller
{
	public function init()
	{
		parent::init();
		if (!isset($this->_redirector)) {
			$this->_redirector = $this->_helper->getHelper('Redirector');
		}
        $alert =  $this->_helper->flashMessenger->getMessages();

        if (count($alert)) {

            $specAlert = explode( "-", $alert[0]);

            if(strtolower($specAlert[0]) == 'error')
            {
                unset($specAlert[0]);
                $this->view->errorAlert = implode( "", $specAlert);
            }
            else
            {
                $this->view->successAlert = $alert[0];
            }
        }
	}
	
	/**
	 * Membantu addAction
	 * @param string $tableName kalau pakai Cms_Model_Crud harus dimasukkan nama tabel
	 *                kalau pakai class turunan biasanya sudah punya nama table, jadi parameter ini bisa null
	 * @param string $listAction kalau selesai menyimpan, redirect ke halaman ini
	 * @param string $crudClass kalau mau pakai class selain Cms_Model_Crud
	 * @param array $options option untuk redirect
     * @param array $dest destinasi untuk file upload
	 */
	protected function _add($tableName, $listAction, $crudClass = 'Cms_Model_Crud', $options = array(), $dest = null)
	{
		$crud = new $crudClass($tableName);
		$form = $crud->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {

                //edited by : tajhul.faijin@sangkuriang.co.id
                if( isset($_FILES) && !is_null($dest) ){
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination($dest);
                    $upload->receive();
                }
                //------------------

				$crud->setFromForm($form);
				$crud->save();
                $this->_helper->flashMessenger->addMessage('Data Tersimpan');

                //jika listAction tidak = string kosong, redirect!
                if($listAction != ''){
					$this->_redirector->gotoSimple($listAction, null, null, $options);
                }
			}

		}
		$this->view->form = $form;

	}

	/**
	 * Membantu editAction
	 * @param string $tableName kalau pakai Cms_Model_Crud harus dimasukkan nama tabel
	 * @param string $listAction kalau selesai menyimpan, redirect ke halaman ini
	 * @param string $crudClass kalau mau pakai class selain Cms_Model_Crud
     * @param array $options option untuk redirect
     * @param array $dest destinasi untuk file upload
	 */
	protected function _edit($tableName, $listAction, $crudClass = 'Cms_Model_Crud', $options = array(), $dest = null)
	{
		$crud = new $crudClass($tableName, $this->_request->getParam('id'));
		if ($crud->exists())
        {
			$form = $crud->form();
			$form->setDefaults($crud->toFormArray());
			if ($this->_request->isPost())
            {
				if ($form->isValid($this->_request->getPost())) {

                    //edited by : tajhul.faijin@sangkuriang.co.id
                    if( isset($_FILES) && !is_null($dest) ){
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->setDestination($dest);
                        $upload->receive();
                    }
                    //------------------
					$crud->setFromForm($form);
					$crud->save();
                    $this->_helper->flashMessenger->addMessage('Data berhasil diubah');
                    //jika listAction tidak = string kosong, redirect!
                    if($listAction != ''){
    					$this->_redirector->gotoSimple($listAction, null, null, $options);
                    }
				}

			}
			$this->view->crud = $crud;
			$this->view->form = $form;
		} else {
			$this->_redirector->gotoSimple($listAction, null, null, $options);
		}
	}

    protected function _replay($tableName, $listAction, $crudClass = 'Cms_Model_Crud', $options = array(), $dest = null)
    {
        $crud = new $crudClass($tableName, $this->_request->getParam('id'));
        if ($crud->exists())
        {
            $form = $crud->form();
            $dataCrud = $crud->toFormArray();
            $pengirim = $dataCrud['pengirim'];
            $kepada = $dataCrud['kepada'];
            $kepada_id = $dataCrud['kepada_id'];
            $pengirim_id = $dataCrud['pengirim_id'];

            $dataCrud['nomor_telegram'] = '';
            $dataCrud['pengirim'] = $kepada;
            $dataCrud['kepada'] = $pengirim;
            $dataCrud['tembusan'] = '';
            $dataCrud['rahasia'] = '';
            $dataCrud['segera'] = '';
            $dataCrud['isi_berita'] = '';
            $dataCrud['kepada_id'] = $pengirim_id;
            $dataCrud['tembusan_id'] = '';
            $dataCrud['pengirim_id'] = $kepada_id;
            $dataCrud['datetiem'] = '';
            $dataCrud['jadwal'] = '';
            $dataCrud['team_id'] = '';
            $dataCrud['isdraft'] = '';
			$dataCrud['datetime'] = '';

            $form->setDefaults($dataCrud);
            if ($this->_request->isPost())
            {
                if ($form->isValid($this->_request->getPost())) {

                    //edited by : tajhul.faijin@sangkuriang.co.id
                    if( isset($_FILES) && !is_null($dest) ){
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->setDestination($dest);
                        $upload->receive();
                    }
                    //------------------
                    $crud->setFromForm($form);
                    $crud->save();
                    $this->_helper->flashMessenger->addMessage('Data berhasil diubah');
                    //jika listAction tidak = string kosong, redirect!
                    if($listAction != ''){
                        $this->_redirector->gotoSimple($listAction, null, null, $options);
                    }
                }

            }
            $this->view->crud = $crud;
            $this->view->form = $form;
        } else {
            $this->_redirector->gotoSimple($listAction, null, null, $options);
        }
    }
	
	/**
	 * Membantu delAction
	 * @param string $tableName kalau pakai Cms_Model_Crud harus dimasukkan nama tabel
	 * @param string $listAction kalau selesai menyimpan, redirect ke halaman ini
	 * @param string $crudClass kalau mau pakai class selain Cms_Model_Crud
	 * @param array $options option untuk redirect
	 */
	protected function _del($tableName, $listAction, $crudClass = 'Cms_Model_Crud', $options = array())
	{
		$crud = new $crudClass($tableName, $this->_request->getParam('id'));
		if ($crud->exists()) {
			$crud->delete();
		}
        $this->_helper->flashMessenger->addMessage('Data berhasil dihapus');
		$this->_redirector->gotoSimple($listAction, null, null, $options);
	}
}