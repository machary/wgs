<?php
class Cms_TabulasiWaktuController extends App_Controller
{
	public function indexAction()
	{
		$this->view->form = new Cms_Form_TabulasiWaktu();
	}

	public function getjarakAction()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();

		$model = new Cms_Model_DbTable_JarakPangkalan();
		$list = new Cms_Model_DbTable_List();

		$asal = $_POST['asal'];
		$tujuan = $_POST['tujuan'];

		$result = $model->getJarak($asal, $tujuan);

		$result['pelabuhan'] = $list->selectAllPelabuhan();

		echo json_encode($result);
	}
}