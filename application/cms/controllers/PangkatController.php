<?php
class Cms_PangkatController extends App_CrudController
{
    public function indexAction()
    {
    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        /**/
        $dt = new Cms_Model_Datatables_Pangkat($this->_request->getParams());
        echo $dt->result();
    }

    public function addAction()
    {
        $this->_add('master.pangkat', 'index', 'Cms_Model_Pangkat');
    }

    public function editAction()
    {
        $this->_edit('master.pangkat', 'index', 'Cms_Model_Pangkat');
    }

    public function delAction()
    {
        $this->_del('master.pangkat', 'index', 'Cms_Model_Pangkat');
    }
}