<?php
class Latihan_PenilaianController extends App_Controller
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $model = new Latihan_Model_Crud_Penilaian();

        $produkstaff = $model->getallps($identity->id);

        if($this->isAjax())
        {
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();

            $param = $this->_request->getParams();
            $param['log_id'] = $identity->id;

            $dt = new Latihan_Model_Datatables_Penilaian($param);
            echo $dt->result();
        }

        $this->view->produkstaff = $produkstaff;
    }

    public function nilaiAction()
    {
        $this->getHelper('layout')->setLayout('layout-less');
        $id = $this->_getParam('id');

        $model = new Latihan_Model_Crud_Penilaian();
        $ref = new Cms_Model_ProdukStaff(null, $this->_request->getParam('id'));
        if ($ref->exists()) {
            $this->view->ref = $ref;
        } else {
            $this->_redirector->gotoSimple('index');
        }

        $data = $model->getprodstaf($id);

        $this->view->data = $data;

        $nilai = new Latihan_Model_DbTable_Report();
        $datanilai = $nilai->getreportcustom( $data['idm_product_staff'], $data['id_team']);
        $this->view->nilai = $datanilai;

    }

    // download lewat sini
    public function downloadAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Latihan_Model_Crud_Penilaian();
        $data = $model->getUrl($this->_request->getParam('id'));

        $this->_redirector->gotoUrl($data['filepath']);
    }

    public function saveAction()
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Latihan_Model_Crud_Penilaian();
        $value = $this->_request->getPost();

        $model->addreport($value['id_ps'], $value['id_team'], $value['nilai'], $value['keterangan']);

        $this->_redirect('latihan/report/index');
    }

    public function individuAction()
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        $model = new Latihan_Model_Crud_Penilaian();

        $data = $model->getlogteam($identity->id);
        $team = $model->getteam($identity->id);

        //        print_r($data);
        //        break;

        $this->view->team = $team;
        $this->view->model = $data;
    }

    public function saveindividu()
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Latihan_Model_Crud_Penilaian();
        $value = $this->_request->getPost();

        $data = $model->getlogteam($identity->id);

        foreach($data as $dat)
        {
            $model->addindividu($dat['log_id'], $dat['id'], $value[$dat['id'].'_'.$dat['log_id']]);
        }
        //$this->_redirect('latihan/report/index');
    }
}