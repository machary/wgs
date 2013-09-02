<?php
/**
 * Fasilitas Perbekalan Controller
 *
 * Mengatur pemasukan informasi fasilitas perbekalan per pangkalan
 *
 * @author Kanwil
 */
 
class Cms_FasbekController extends App_Controller
{
	// datatables daftar pangkalan beserta link untuk edit Fasbeknya
	public function indexAction()
	{
	}
	
	// backend datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		/**/
		$dt = new Cms_Model_Datatables_Fasbek($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	// memasang banyak fasbek ke 1 pangkalan
	public function editAction()
	{
		try {
			$fasbek = new Cms_Model_Fasbek($this->_request->getParam('id'));
		} catch (Exception $e) {
			return $this->_redirector->gotoSimple('index');
		}
		$form = $fasbek->form();
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$fasbek->setFromForm($form);
				// print_r($fasbek);
				$fasbek->save();
				$this->_redirector->gotoSimple('index');
			}
		}
		$this->view->fasbek = $fasbek;
		$this->view->form = $form;
	}
}