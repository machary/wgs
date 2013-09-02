<?php
/**
 * List of Kogas for nilai
 *
 * @author Febi
 */

class Ops_PenilaianController extends App_Controller
{
	public function indexAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $jabatan = $modelPenilai->selectAllwithlogin($identity->id);

        if(count($jabatan) > 1) {
            $kogasData = array();
            foreach($jabatan as $kogas) {
                array_push($kogasData, $kogas['nama_jabatan']);
            }
            $this->view->kogas = $kogasData;
        } else {
            $this->_redirector->gotoSimple('penilaian', strtolower($jabatan[0]['nama_jabatan']));
        }
	}

	public function rekapAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $modelPenilai = new Management_Model_DbTable_Penilai();
        $jabatan = $modelPenilai->selectAllwithlogin($identity->id);

        if(count($jabatan) > 1) {
            $kogasData = array();
            foreach($jabatan as $kogas) {
                array_push($kogasData, $kogas['nama_jabatan']);
            }
            $this->view->kogas = $kogasData;
        } else {
            $this->_redirector->gotoSimple('rekap.index', strtolower($jabatan[0]['nama_jabatan']));
        }
        $this->getHelper('viewRenderer')->setViewScriptPathSpec('penilaian/rekap-index.phtml');
	}
}