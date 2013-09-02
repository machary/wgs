<?php
/**
 * Tampilan Peta Pakai OpenLayers
 * @author Kanwil
 */
class Cms_MapController extends App_Controller
{
	public function init()
	{
		parent::init();
	}

	// coba-coba openlayers
	public function indexAction()
	{

	}
	
	// mengembalikan potongan HTML berisi data terhadap titik tertentu
	public function getInfoAction()
	{
		$this->getHelper('layout')->disableLayout();
		// @TODO:
		// get koordinat
		// get jenis data (pangkalan/lain2)
		// ambil dari database
		// tampilkan
		// SELECT * FROM public.lanal_area WHERE geomfromtext('POINT(101, -4)') && geom
	}
	
	// mengembalikan info fasilitas terhadap suatu lanal
	public function getInfoLanalAction()
	{
		$this->getHelper('layout')->disableLayout();
		// get koordinat
		$longitude = $this->_request->getParam('lon');
		$latitude = $this->_request->getParam('lat');
		// ambil dari database
		$map = new Peta_Model_Map();
		$idpangkalan = $map->getIdLanalFromCoord($longitude, $latitude);
		// tampilkan
		if ($idpangkalan) {
			$pangkalan = $map->getPangkalanDetail($idpangkalan);
			$this->view->pangkalan = $pangkalan;
		}
		$this->view->model = $map;
	}

    // mengembalikan info fasilitas terhadap suatu lanal
    public function getInfoLanalPointAction()
    {
        $this->getHelper('layout')->disableLayout();
        // get koordinat
        $longitude = $this->_request->getParam('lon');
        $latitude = $this->_request->getParam('lat');
        // ambil dari database
        $map = new Peta_Model_Map();
        $idpangkalan = $map->getIdLanalFromCoord2($longitude, $latitude);
        // tampilkan
        if ($idpangkalan) {
            $pangkalan = $map->getPangkalanDetail($idpangkalan);
            $this->view->pangkalan = $pangkalan;
        }
        $this->view->model = $map;
    }
	
}