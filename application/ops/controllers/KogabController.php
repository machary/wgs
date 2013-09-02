<?php
/**
 * Kogab
 * 
 * @author Kanwil
 */
 
class Ops_KogabController extends App_Controller
{
	// pilih CB terbaik tiap kogas
	public function indexAction()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();
		$model = new Ops_Model_Kogab($identity->id_team);
		
		if ($this->_request->isPost()) {
			if ($model->isValid($this->_request->getPost())) {
				$model->save();
				$this->view->successAlert = 'Tersimpan';
			}
		}
		
		$this->view->model = $model;
	}
}