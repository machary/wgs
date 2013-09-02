<?php
/**
 * Kogasud
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasudController extends Ops_KogasController
{
	protected $_kogas = 'Kogasud';
	protected $_cbTableName = 'ops.ud_cb';
	protected $_cbCrudClass = 'Ops_Model_Ud_Cb';
	protected $_ruteClass = array(
		'udara' => 'Ops_Model_Ud_Udara',
	);
	protected $_perbandinganClass = 'Ops_Model_Ud_Perbandingan';
}