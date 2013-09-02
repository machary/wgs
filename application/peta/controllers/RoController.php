<?php
/**
 * Ro = Rencana Operasi
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_RoController extends App_CrudController
{
	public function indexAction()
	{
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		$cbTable = new Zend_Db_Table('latihan.pilihan_cb_kogab');
		$this->view->cbsel = $cbTable->find(1)->current();

		$this->view->items = Peta_Model_RuteOperasional::allObjects(3);
		$this->view->items2 = Peta_Model_RuteOperasional::allObjects(7);
	}

}
