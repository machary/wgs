<?php 
/**
 * Rencana Operasi Sendiri
 *
 * 
 *
 * @author Febi
 */
 
class Ops_RencanaOperasiSendiriController extends App_Controller
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

            $dt = new Ops_Model_Sendiri_Skenario($param);
            echo $dt->result();
        }
	}
}