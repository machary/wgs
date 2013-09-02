<?php
/**
 * Pangkalan Controller
 *
 * Lantamal, Lanal, Lanudal, Lanmar
 *
 * @author Kanwil
 */
 
class Cms_PangkalanController extends App_CrudController
{
	// halaman berisi Datatables
	public function indexAction()
	{
		
	}
	
	// Penyedia data ke Datatables
	public function dataapiAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
		
		/**/
		$dt = new Cms_Model_Datatables_Pangkalan($this->_request->getParams());
		echo $dt->result();
		/**/
	}
	
	public function addAction()
	{
		$this->_add(null, 'index', 'Cms_Model_Pangkalan');
	}
	
	public function editAction()
	{
		$this->_edit(null, 'index', 'Cms_Model_Pangkalan');
	}
	
	public function delAction()
	{
		$this->_del(null, 'index', 'Cms_Model_Pangkalan');
	}

	/**
	 * @author irfan.muslim@sangkuriang.co.id
	 */
	public function viewAction()
	{
		$obj = new Cms_Model_PangkalanDetail($this->_request->getParam('id'));
		$this->view->obj = $obj;
	}

	/**
	 * @author irfan.muslim@sangkuriang.co.id
	 */
	public function viewrAction()
	{
		$this->getHelper('layout')->disableLayout();

		// get koordinat
		$longitude = $this->_request->getParam('lon');
		$latitude = $this->_request->getParam('lat');

		// ambil dari database

		if (isset($longitude)){
			$map = new Peta_Model_Map();
			$idpangkalan = $map->getIdLanalFromCoord2($longitude, $latitude);
		}

		$obj = new Cms_Model_PangkalanDetail($idpangkalan);
		$this->view->obj = $obj;
		$this->view->pp = $obj->getPp($this->_request->getParam('cbid'));
	}

}