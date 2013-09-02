<?php
/**
 * Kogasgabla
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgablaController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgabla';
	protected $_cbTableName = 'ops.gabla_cb';
	protected $_cbCrudClass = 'Ops_Model_Gabla_Cb';
	protected $_ruteClass = array(
		'laut' => 'Ops_Model_Gabla_Laut',
		'udara' => 'Ops_Model_Gabla_Udara',
	);
	protected $_perbandinganClass = 'Ops_Model_Gabla_Perbandingan';
}