<?php 
/**
 * Rencana Operasi
 *
 * 
 *
 * @author Kanwil
 */
 
class Ops_RencanaOperasiController extends App_Controller
{
	// daftar kogas yg bisa dipilih RO-nya
	public function indexAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if(isset($identity->kogas))
        {
            $this->_redirector->gotoSimple( $identity->kogas, 'rencana.operasi');
        }
	}
	
	private function _kogasAction($method)
	{
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('rencana-operasi/kogas.phtml');
		
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$model = new Ops_Model_Ro($identity->id_team);
		$kogas = $this->_request->getActionName();

		if ($this->_request->isPost()) {
			$model->save($kogas, json_decode($this->_request->getPost('json')));
			$this->view->successAlert = 'Tersimpan';
		}
		
		$data = $model->$method();

		if (!isset($data['cb'])) {
			$this->view->cb = null;
		} else {
			$this->view->cb = $data['cb'];
			unset($data['cb']);
			$this->view->data = $data;
			$this->view->saved = $model->retrieve($kogas);
		}
	}
	
	public function kogasgablaAction()
	{
		$this->_kogasAction('kogasgablaData');
	}
	
	public function kogasgabfibAction()
	{
		$this->_kogasAction('kogasgabfibData');
	}
	
	public function kogasgablinudAction()
	{
		$this->_kogasAction('kogasgablinudData');
	}
	
	public function kogasgabratAction()
	{
		$this->_kogasAction('kogasgabratData');
	}
	
	public function kogasudAction()
	{
		$this->_kogasAction('kogasudData');
	}

	public function pasratAction()
	{
		$this->_kogasAction('pasratData');
	}
}