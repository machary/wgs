<?php
class Cms_HelpController extends App_CrudController
{
    public function indexAction()
    {
        $jenis = new Cms_Model_JenisReferensiHelp();
        $this->view->jenis = $jenis;
    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $dt = new Cms_Model_Datatables_Help($this->_request->getParams());
        echo $dt->result();
    }

    public function addAction()
    {
        $crud = new Cms_Model_Help(null);
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
        $this->_del(null, 'index', 'Cms_Model_Help');
    }

    // online PDF viewer
    public function readAction()
    {
        // pakai halaman khusus
        $this->getHelper('layout')->disableLayout();

        $ref = new Cms_Model_Help(null, $this->_request->getParam('id'));
        if ($ref->exists()) {
            $this->view->ref = $ref;
        } else {
            $this->_redirector->gotoSimple('index');
        }
    }

    // CRUD jenis referensi
    public function jenisAction()
    {
        $jenis = new Cms_Model_JenisReferensiHelp();
        if ($this->_request->isPost()) {
            $jenis->setFromPost($this->_request->getPost());
            if ($jenis->isValid()) {
                $jenis->save();
                $this->view->successAlert = 'Tersimpan';
            }
        }

        $this->view->jenis = $jenis;
    }
}