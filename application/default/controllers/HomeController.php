<?php
/**
 * Halaman Beranda
 *
 */
class HomeController extends App_Controller
{

	public function init()
	{
		/* Initialize action controller here */
		parent::init();
	}

	public function indexAction()
	{
        $newsModel = new Default_Model_DbTable_News();
        $sliderModel = new Default_Model_DbTable_Slider();
        $this->view->news = $newsModel->topNews( $limit = 3 );
        $this->view->slider = $sliderModel->topSlider( $limit = 5 );
	}

    public function detailAction()
    {
        $id = $this->_getParam( 'id', null);
        $newsModel = new Default_Model_DbTable_News();
        $this->view->news = $newsModel->getByID( $id );
    }
}

