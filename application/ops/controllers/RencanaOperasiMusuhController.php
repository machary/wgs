<?php 
/**
 * Rencana Operasi Musuh
 *
 * 
 *
 * @author Febi
 */
 
class Ops_RencanaOperasiMusuhController extends App_Controller
{
    protected $_rute = array( 'laut', 'udara' );

	// daftar kogas yg bisa dipilih RO-nya
	public function indexAction()
	{
        if($this->isAjax())
        {
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();

            $param = $this->_request->getParams();
            $param['rute'] = $this->_rute;

            $dt = new Ops_Model_Musuh_Skenario($param);
            echo $dt->result();
        }
	}
}