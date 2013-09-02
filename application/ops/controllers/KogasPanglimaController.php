<?php 
/**
 * Rencana Operasi Gabungan
 *
 * @author Febi
 */
 
class Ops_KogasPanglimaController extends App_Controller
{
	// daftar kogas yg bisa dipilih RO-nya

	public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if(isset($identity->kogas) AND $this->checkOpsKogas($identity->kogas))
        {
            $this->_redirector->gotoSimple('index', strtolower($identity->kogas));
        }
    }

	public function listAction()
	{
        $post = array();
        if ($this->_request->isPost()) {
            $post = $this->_request->getPost();
        } else {
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            if(isset($identity->kogas))
            {
                return $this->_redirector->gotoSimple('parbandingan.panglima', $identity->kogas);
            }
            else
            {
                return $this->_redirector->gotoSimple('index', 'index');
            }
        }

        $this->getHelper('viewRenderer')->setViewScriptPathSpec('partials/rute-perbandingan-panglima-detail.phtml');

        $dataPanglima = array();
        $data = array();
        foreach(json_decode($post['ruteclass']) as $rute => $ruteclass) {

            $cbId = $post['cb_pilihan'];
            $cbTable = new Zend_Db_Table($post['cbcrudclass']);
            $satuan = json_decode($post['satuan']);

            $dataPanglima[$cbId][$rute]['satuan'] = $satuan->$rute;
            $dataPanglima[$cbId]['cb'] = $cbTable->find($cbId)->current();
            $dataPanglima[$cbId][$rute]['items'] = $ruteclass::allObjects($cbId);

            foreach($post['cbnotp_pilihan'] as $cbnotp) {
                $cbId = $cbnotp;
                $cbTable = new Zend_Db_Table($post['cbcrudclass']);
                $satuan = json_decode($post['satuan']);

                $data[$cbId][$rute]['satuan'] = $satuan->$rute;
                $data[$cbId]['cb'] = $cbTable->find($cbId)->current();
                $data[$cbId][$rute]['items'] = $ruteclass::allObjects($cbId);
            }
        }

        $this->view->data = $data;
        $this->view->dataPanglima = $dataPanglima;
        $this->view->kogas = $post['kogas'];
    }

}