<?php
/**
 * Ship Weaponry Controller
 *
 * Suatu kapal memiliki persenjataan apa saja
 *
 * @author Kanwil
 */
 
class Cms_ShipWeaponryController extends App_Controller
{
	// daftar kapal yang ada plus rangkuman jumlah senjata
	public function indexAction()
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		$dt = new Cms_Model_Datatables_ShipWeaponry($this->_request->getParams());
		echo $dt->result();
	}
	
	public function editAction()
	{
		try {
			$weaponry = new Cms_Model_ShipWeaponry($this->_request->getParam('id'));
		} catch (Exception $e) {
			return $this->_redirector->gotoSimple('index');
		}
		$form = new Cms_Form_ShipWeaponry();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$weaponry->setFromForm($form);
				$weaponry->save();
				$this->_redirector->gotoSimple('index');
			}
		}
		
		$this->view->weaponry = $weaponry;
		$this->view->form = $form;
	}
}