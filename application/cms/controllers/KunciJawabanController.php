<?php
class Cms_KunciJawabanController extends App_CrudController
{
    public function indexAction()
    {

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $dt = new Cms_Model_Datatables_KunciJawaban($this->_request->getParams());
        echo $dt->result();
    }

    public function addAction()
    {
        $crud = new Cms_Model_KunciJawaban(null);
        $form = $crud->form();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                try {
                    $form->filepath->receive();
                } catch (Exception $e) {
                    return $this->_redirector->gotoSimple('index');
                }
                $crud->setFromForm($form);
                $crud->save();
                $this->_redirector->gotoSimple('index');
            }
        }
        $this->view->form = $form;
    }

    public function delAction()
    {
        $this->_del(null, 'index', 'Cms_Model_KunciJawaban');
    }

    // online PDF viewer
    public function readAction()
    {
        // pakai halaman khusus
        $this->getHelper('layout')->disableLayout();

        $ref = new Cms_Model_KunciJawaban(null, $this->_request->getParam('id'));
        if ($ref->exists()) {
            $this->view->ref = $ref;
        } else {
            $this->_redirector->gotoSimple('index');
        }
    }

    // download lewat sini
    public function downloadAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
        $ref = new Cms_Model_KunciJawaban(null, $this->_request->getParam('id'));
        if ($ref->exists()) {
            $this->_redirector->gotoUrl($ref->get('filepath'));
        } else {
            $this->_redirector->gotoSimple('index');
        }
    }
}