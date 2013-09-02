<?php
class Peta_ApiController extends Zend_Controller_Action
{
    public function init()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }

    public function indexAction()
    {

    }

    public function referensiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $id_divisi = $this->_request->getParam('id_divisi');

        $model = new Peta_Model_DbTable_TitikReferensi();

        $data = $model->getTitikReferensi($id_divisi);

        echo json_encode($data);
    }

}