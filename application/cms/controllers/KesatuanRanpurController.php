<?php
/**
 * Kesatuan Ranpur Controller
 *
 * Suatu Kesatuan memiliki Kendaraan tempur apa saja
 *
 * @author irfan.muslim@sangkuriang.co.id
 */

//class Cms_KesatuanRanpurController extends Zend_Controller_Action
class Cms_KesatuanRanpurController extends App_CrudController
{
	public function init()
	{
		$this->_redirector = $this->_helper->getHelper('Redirector');
	}
	
	// daftar kendaraan tempur yang dimiliki kesatuan
	public function indexAction()
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

		//model untuk list
		$dt = new Cms_Model_Datatables_KesatuanRanpur($this->_request->getParams());
		echo $dt->result();
	}
	
	public function editAction()
	{
		try {
			//model untuk edit
			$ranpur = new Cms_Model_KesatuanRanpur($this->_request->getParam('id'));
		} catch (Exception $e) {
			return $this->_redirector->gotoSimple('index');
		}

		$form = new Cms_Form_KesatuanRanpur();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$ranpur->setFromForm($form);
				$ranpur->save();
				$this->_redirector->gotoSimple('index');
			}
		}

		$this->view->ranpur = $ranpur;
		$this->view->form = $form;
	}
}