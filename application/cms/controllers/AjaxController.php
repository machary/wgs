<?php
class Cms_AjaxController extends App_Controller
{
    public function init()
    {
        parent::init();
        //parent::desableView();
    }

    public function indexAction()
    {

    }

    public function checkgunnameAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $db = new Cms_Model_DbTable_Gun();

        $cek = $db->cekName($_POST['GUN_NAME']);

        if($cek > 0)
        {
            $data['status'] = true;
        }
        else
        {
            $data['status'] = false;
        }
        echo json_encode($data);
    }
}