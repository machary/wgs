<?php
class Cms_FasilitasUmumController extends App_CrudController
{
    public function indexAction()
    {
    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        /**/
        $dt = new Cms_Model_Datatables_FasilitasUmum($this->_request->getParams());
        echo $dt->result();
    }

    public function addAction()
    {
        $this->_add(null, 'index', 'Cms_Model_FasilitasUmum');
    }

    public function editAction()
    {
        $this->_edit(null, 'index', 'Cms_Model_FasilitasUmum');
    }

    public function delAction()
    {
        $this->_del(null, 'index', 'Cms_Model_FasilitasUmum');
    }
}