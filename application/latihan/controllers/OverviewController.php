<?php
class Latihan_OverviewController extends App_Controller
{
	public function indexAction()
	{
		$request = $this->getRequest();
		$this->view->skenario = $request->getParam('skenario_id');
	}
}