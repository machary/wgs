<?php
/**
 * Kogasgabfib
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgabfibController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgabfib';
	protected $_cbTableName = 'ops.gabfib_cb';
	protected $_cbCrudClass = 'Ops_Model_Gabfib_Cb';
	protected $_ruteClass = array(
		'laut' => 'Ops_Model_Gabfib_Laut',
		'udara' => 'Ops_Model_Gabfib_Udara',
	);
	protected $_perbandinganClass = 'Ops_Model_Gabfib_Perbandingan';
	
	// fitur latum
	public function lautEditAction() {
		parent::lautEditAction();
		$this->getHelper('viewRenderer')->setViewScriptPathSpec('kogasgabfib/laut-edit.phtml');
		
	}
}
