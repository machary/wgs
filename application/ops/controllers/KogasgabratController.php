<?php
/**
 * Kogasgabrat
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgabratController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgabrat';
	protected $_cbTableName = 'ops.gabrat_cb';
	protected $_cbCrudClass = 'Ops_Model_Gabrat_Cb';
	protected $_ruteClass = array(
		'darat' => 'Ops_Model_Gabrat_Darat',
	);
	protected $_perbandinganClass = 'Ops_Model_Gabrat_Perbandingan';
}